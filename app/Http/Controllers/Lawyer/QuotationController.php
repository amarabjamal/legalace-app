<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuotationRequest;
use App\Models\BankAccount;
use App\Models\CaseFile;
use App\Models\Quotation;
use App\Models\WorkDescription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class QuotationController extends Controller
{
    protected $casefile;
    protected $bankaccount;
    protected $quotation;

    public function __construct(CaseFile $casefile, BankAccount $bankaccount, Quotation $quotation)
    {
        $this->casefile = $casefile;
        $this->bankaccount = $bankaccount;
        $this->quotation = $quotation;
    }

    public function create(CaseFile $casefile) {
        if($casefile->quotation()->exists()) {
            return redirect()->route('lawyer.quotation.edit', $casefile);
        }

        return Inertia::render('Lawyer/Quotation/CreateQuotation', [
            'case_file' => [
                'id' => $casefile->id,
                'file_number' => $casefile->file_number,
            ],
            'client_bank_accounts' => $this->bankaccount->clientAccountOptions(),
        ]);
        
    }

    public function generateQuotation($caseFileId) {

        if($this->quotation->verifyCaseFileHasQuotation($caseFileId)) {
            return redirect()->route('lawyer.edit.quotation', $this->quotation->getQuotationByFileCaseId($caseFileId));
        }

        return Inertia::render('Lawyer/Quotation/Create', [
            'case_file' => $this->casefile->getCaseFileByIdWithAddress($caseFileId),
            'client_bank_accounts' => $this->bankaccount->clientAccountOptions(),
        ]);
    }

    public function store(StoreQuotationRequest $request, CaseFile $casefile) {
        
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

        //dd($workDescriptions);

        $quotation = Quotation::create($validated);

        $quotation->workDescriptions()->saveMany($workDescriptions);

        return redirect()->route('lawyer.quotation.edit', $casefile);
    }

    public function edit(CaseFile $casefile) {
        if(!$casefile->quotation()->exists()) {
            return redirect()->route('lawyer.quotation.create', $casefile);
        }

        $casefile->quotation;

        return inertia('Lawyer/Quotation/EditQuotation', [
            'case_file' => $casefile,
            'client_bank_accounts' => $this->bankaccount->clientAccountOptions(),
        ]);
    }

    public function update(Request $request, CaseFile $casefile, Quotation $quotation) 
    {
        
        $validated = $request->validate([
            'deposit_amount' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'bank_account_id' => ['required', 'exists:bank_accounts,id']
        ]);

        $quotation->update($validated);

        return back()->with('successMessage', 'Successfully update!');
    }
}
