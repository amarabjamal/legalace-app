<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;
use App\Models\BankAccount;
use App\Models\CaseFile;
use App\Models\Quotation;
use App\Models\WorkDescription;
use Exception;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Brick\Money\Money;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Expr\Cast\Object_;

class QuotationController extends Controller
{
    protected $casefile;
    protected $bankaccount;
    protected $quotation;
    protected $workdescription;

    public function __construct(
        CaseFile $casefile, 
        BankAccount $bankaccount, 
        Quotation $quotation, 
        WorkDescription $workdescription
    )
    {
        $this->casefile = $casefile;
        $this->bankaccount = $bankaccount;
        $this->quotation = $quotation;
        $this->workdescription = $workdescription;
    }

    public function create(CaseFile $case_file) 
    {
        if($case_file->hasQuotation()) {
            return redirect()->route('lawyer.quotation.edit', $case_file);
        }

        return Inertia::render('Lawyer/Quotation/Create', [
            'case_file' => [
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'client_bank_accounts' => $this->bankaccount->clientAccountOptions(),
        ]);
    }

    public function store(StoreQuotationRequest $request, CaseFile $case_file) 
    {
        try {
            DB::beginTransaction();

            $quotationInputs = $request->except('work_descriptions');
            $quotationInputs['deposit_amount'] = Money::of($quotationInputs['deposit_amount'], 'MYR');

            $quotation = Quotation::create($quotationInputs);

            $workDescriptions = $request->work_descriptions;

            foreach($workDescriptions as &$workDescription) {
                $workDescription['company_id'] = auth()->user()->company_id;
                $workDescription['fee'] = Money::of($workDescription['fee'], 'MYR');
            }

            $quotation->workDescriptions()->createMany($workDescriptions);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            dd($e);
            return back()->with('errorMessage', 'Failed to create the quotation. Please try again.');
        }

        return redirect()->route('lawyer.quotation.edit', $case_file)->with('successMessage', 'The quotation has been created.');
    }

    public function edit(CaseFile $case_file) 
    {
        if(!$case_file->hasQuotation()) {
            return redirect()->route('lawyer.quotation.create', $case_file);
        }

        $quotation = $case_file->quotation;

        return inertia('Lawyer/Quotation/Edit', [
            'case_file' => [
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'quotation' => [
                'deposit_amount' => $quotation->deposit_amount,
                'bank_account_id' => $quotation->bank_account_id,
                'work_descriptions' => $quotation->workDescriptions->map(fn ($workDescription) => [
                    'description' => $workDescription->description,
                    'fee' => $workDescription->fee->getAmount(),
                ])
            ],
            'client_bank_accounts' => $this->bankaccount->clientAccountOptions(),
        ]);
    }

    public function update(UpdateQuotationRequest $request, CaseFile $case_file) 
    {
        if(!$case_file->hasQuotation()) {
            return redirect()->route('lawyer.quotation.create', $case_file);
        }

        try {
            DB::beginTransaction();
            
            $quotation = $case_file->quotation;
            $quotation->deposit_amount = Money::of($request->deposit_amount, 'MYR');
            $quotation->bank_account_id = $request->bank_account_id;
            $quotation->save();

            $workDescriptions = $quotation->workDescriptions();
            $workDescriptions->delete();
            $workDescriptionArray = $request->work_descriptions;

            foreach($workDescriptionArray as &$workDescription) {
                $workDescription['company_id'] = auth()->user()->company_id;
                $workDescription['fee'] = Money::of($workDescription['fee'], 'MYR');
            }

            $workDescriptions->createMany($workDescriptionArray);
            
            DB::commit();

            return back()->with('successMessage', 'Successfully update!');
        } catch (Exception $e) {
            DB::rollback();
        }

        return back()->with('errorMessage', 'Failed to update!');
    }

    public function show(CaseFile $case_file) 
    {
        if(!$case_file->hasQuotation()) {
            return redirect()->route('lawyer.quotation.create', $case_file);
        }

        $quotation = $case_file->quotation;
        $bankAccount = $quotation->bankAccount;
        $workDescriptions = $quotation->workDescriptions;

        return inertia('Lawyer/Quotation/Show', [
            'case_file' => [
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'quotation' => [
                'deposit_amount' => $quotation->deposit_amount->formatTo('en_MY'),
                'bank_account_id' => $quotation->bank_account_id,
                'bank_account' => [
                    'label' => $bankAccount->label,
                    'bank_name' => $bankAccount->bank_name,
                    'account_name' => $bankAccount->account_name,
                    'account_number' => $bankAccount->account_number,
                    'type' => $bankAccount->bankAccountType->name,
                ],
                'work_descriptions' => $workDescriptions->map(fn ($workDescription) => [
                    'description' => $workDescription->description,
                    'fee' => $workDescription->fee->formatTo('en_MY'),
                ])
            ],
        ]);
    }

    public function viewPdf(CaseFile $case_file)
    {
        $data = [
            'workdescriptions' => $case_file->workDescriptions()->get()->toArray(),
            'deposit_amount' =>  $case_file->quotation()->pluck('deposit_amount')->toArray()[0],
        ];

        //dd($data);
        
        $pdf = PDF::loadView('templates.quotation', $data)->setPaper('a4', 'portrait');

        return $pdf->stream();
    }

    public function sendEmail(CaseFile $case_file) 
    {

        $data = [
            'workdescriptions' => $case_file->workDescriptions()->get()->toArray(),
            'deposit_amount' =>  $case_file->quotation()->pluck('deposit_amount')->toArray()[0],
        ];
        
        $pdf = PDF::loadView('templatesquotation', $data);

        $email = [
            'client_email' => 'client@example.com',
            'subject' => 'Quotation: Matter',
            'body' => 'Please find the attached quotation.',
        ];

        Mail::send('emails.quotation', $email, function ($message) use ($email, $pdf) {
            $message->to($email["client_email"], $email["client_email"])
                ->subject($email["subject"])
                ->attachData($pdf->output(), "quotation.pdf");
        });

        echo "email send successfully !!";
    }
}
