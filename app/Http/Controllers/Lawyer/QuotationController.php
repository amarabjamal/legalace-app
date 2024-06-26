<?php

namespace App\Http\Controllers\Lawyer;

use App\Enums\PaymentMethodEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;
use App\Jobs\CalculateQuotationTotal;
use App\Mail\SendQuotation;
use App\Models\BankAccount;
use App\Models\CaseFile;
use App\Models\Quotation;
use App\Models\WorkDescription;
use Exception;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Brick\Money\Money;
use Illuminate\Support\Facades\Mail;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelOptions\Options;

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
        if(!$case_file->no_conflict_checked) {
            return redirect()->route('lawyer.case-files.show', $case_file)->with('warningMessage', 'The case file conflict check is pending. Resolve the case file conflict check first before proceed.');
        }

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

            dispatch_sync(new CalculateQuotationTotal($quotation));            

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            dd($e);
            return back()->with('errorMessage', 'Failed to create the quotation. Please try again.');
        }

        return redirect()->route('lawyer.quotation.show', $case_file)->with('successMessage', 'The quotation has been created.');
    }

    public function edit(CaseFile $case_file) 
    {
        $quotation = $case_file->quotation;

        if(!$case_file->hasQuotation()) {
            return redirect()->route('lawyer.quotation.create', $case_file);
        } else if($quotation->isPaid()) {
            return redirect()->route('lawyer.quotation.show', $case_file)->with('warningMessage', 'This quotation is already paid and no longer editable.');
        }


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

            dispatch_sync(new CalculateQuotationTotal($quotation));
            
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
        $payment = $quotation->payment;

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
                ]),
                'subtotal' => $quotation->subtotal->formatTo('en_MY'),
                'tax' => $quotation->tax_amount->formatTo('en_MY'),
                'total' => $quotation->total->formatTo('en_MY'),
                'sent_at' => $quotation->sent_at? $quotation->sent_at : 'N/A',
                'is_paid' => $quotation->isPaid(),
                'payment' => [
                    'date' => $payment?->date->format('d F Y'),
                    'method' => $payment?->payment_method_display_text,
                ],
            ],
            'modal_props' => [
                'payment_methods' => Options::forEnum(PaymentMethodEnum::class),
                'client_bank_accounts' => BankAccount::clientAccount()->get(['id', 'label']),
            ],
        ]);
    }

    public function emailQuotation(CaseFile $case_file)
    {
        try { 
            DB::transaction(function() use ($case_file) {
                $quotation = $case_file->quotation;
                $client = $case_file->client;

                $quotation->sent_at = now();
                $quotation->save();    

                $pdf = $this->generatePdf($case_file);

                Mail::to($client->email)
                    ->send(new SendQuotation($quotation, $pdf, $client->name));
            });
        } catch (\Exception $e) {
            dd($e);
            return back()->with('errorMessage', 'Failed to send the quotation');
        }

        return back()->with('successMessage', 'The quotation is emailed to the client.');
    }

    public function markSent(CaseFile $case_file)
    {
        try { 
            DB::transaction(function() use ($case_file) {
                $quotation = $case_file->quotation;

                $quotation->sent_at = now();
                $quotation->save();    
            });
        } catch (\Exception $e) {
            dd($e);
            return back()->with('errorMessage', 'Failed to mark sent the quotation. Please try again.');
        }

        return back()->with('successMessage', 'The quotation is marked sent.');
    }

    public function downloadPdf(CaseFile $case_file)
    {
        $pdf = $this->generatePdf($case_file);

        return response()->stream(function () use ($pdf) {
            echo $pdf;
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename=quotation.pdf',
        ]);
    }

    private function generatePdf(CaseFile $caseFile)
    {
        $quotation = $caseFile->quotation;

        $data = [
            'case_file' => [ 
                'number' => $caseFile->file_number,
                'matter' => $caseFile->matter,
                'company' => $caseFile->company->only('name', 'address'),
                'client' => $caseFile->client->only('name', 'address'),
            ],
            'date' => today()->format('d/m/Y'),
            'items' => $quotation->workDescriptions,
            'subtotal' => $quotation->subtotal->formatTo('en_MY'),
            'tax' => $quotation->tax_amount->formatTo('en_MY'),
            'total' => $quotation->total->formatTo('en_MY'),
            'deposit_amount' =>  $quotation->deposit_amount->formatTo('en-MY'),
            'bank_account' => $quotation->bankAccount,
        ];

        $html = view('templates.quotation', $data)->render();

        $footer = '<div class="text-gray-500 text-xs p-2 border-t">This invoice is generated electronically and does not require a physical signature.<div class="text-center mt-4 font-bold">Generated by Legal Ace &copy; 2023</div></div>';

        return Browsershot::html($html)
                ->margins(18, 18, 18, 18)
                ->format('A4')
                ->showBackground()
                ->pdf();
    }
}
