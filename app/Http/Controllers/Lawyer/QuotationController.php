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
use Illuminate\Support\Facades\Mail;

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
        if($case_file->quotation()->exists()) {
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

    public function store(
        StoreQuotationRequest $request, 
        CaseFile $case_file
    ) 
    {
        
        $validated = $request->validated();

        $workDescriptions = [];

        foreach($validated['work_descriptions'] as $workDescription) {
            array_push(
                $workDescriptions, 
                new WorkDescription([
                        'description' => $workDescription['description'],
                        'fee' => $workDescription['fee']
                    ]
                )
            );
        }

        $quotation = Quotation::create($validated);

        $quotation->workDescriptions()->saveMany($workDescriptions);

        return redirect()->route('lawyer.quotation.edit', $case_file);
    }

    public function show(CaseFile $case_file) 
    {
        $case_file->quotation;
        $case_file->workDescriptions;

        return inertia('Lawyer/Quotation/Show', [
            'case_file' => $case_file,
            'client_bank_accounts' => $this->bankaccount->clientAccountOptions(),
        ]);
    }

    public function edit(CaseFile $case_file) 
    {
        if(!$case_file->quotation()->exists()) {
            return redirect()->route('lawyer.quotation.create', $case_file);
        }

        $case_file->quotation;
        $case_file->workDescriptions;

        return inertia('Lawyer/Quotation/Edit', [
            'case_file' => $case_file,
            'client_bank_accounts' => $this->bankaccount->clientAccountOptions(),
        ]);
    }

    public function update(UpdateQuotationRequest $request, CaseFile $case_file) 
    {
        if(!$case_file->quotation()->exists()) {
            return redirect()->route('lawyer.quotation.create', $case_file);
        }

        $quotation = $this->quotation->getQuotationByFileCaseId($case_file->id);

        try {
            DB::beginTransaction();
    
            $quotation->update($request->safe()->only(['deposit_amount', 'bank_account_id']));
            
            $deleteStatus = $this->workdescription->deleteAll($quotation->id);
            
            if(!$deleteStatus) {
                DB::rollback();

                return back()->with('errorMessage', 'Failed to delete old work descriptions');
            }

            $workDescriptions = [];
            
            foreach($request->safe()->only(['work_descriptions'])['work_descriptions'] as $workDescription) {
                array_push(
                    $workDescriptions, 
                    new WorkDescription([
                            'description' => $workDescription['description'],
                            'fee' => $workDescription['fee']
                        ]
                    )
                );
            }
            
            $quotation->workDescriptions()->saveMany($workDescriptions);
            
            DB::commit();

            return back()->with('successMessage', 'Successfully update!');
        } catch (Exception $e) {
            DB::rollback();
        }

        return back()->with('errorMessage', 'Failed update!');
    }

    public function viewPDF(CaseFile $case_file)
    {
        $data = [
            'workdescriptions' => $case_file->workDescriptions()->get()->toArray(),
            'deposit_amount' =>  $case_file->quotation()->pluck('deposit_amount')->toArray()[0],
        ];

        //dd($data);
        
        $pdf = PDF::loadView('templates.quotation.quotation', $data)->setPaper('a4', 'portrait');

        return $pdf->stream();
    }

    public function sendEmail(CaseFile $case_file) 
    {

        $data = [
            'workdescriptions' => $case_file->workDescriptions()->get()->toArray(),
            'deposit_amount' =>  $case_file->quotation()->pluck('deposit_amount')->toArray()[0],
        ];
        
        $pdf = PDF::loadView('templates.quotation.quotation', $data);

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
