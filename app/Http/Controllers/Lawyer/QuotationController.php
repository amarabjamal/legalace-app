<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuotationRequest;
use App\Models\BankAccount;
use App\Models\CaseFile;
use App\Models\Quotation;
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
            dd('Case file already has a quoation');
        }

        return Inertia::render('Lawyer/Quotation/CreateQuotation', [
            //'case_file' => $casefile,
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

    public function store(StoreQuotationRequest $request) {
        $validated = $request->validated();

        $quotation = Quotation::create($validated);

        return redirect()->route('lawyer.edit.quotation', $quotation);
    }

    public function edit(Quotation $quotation)
    {
        $quotation->caseFile;

        return Inertia::render('Lawyer/Quotation/Edit', [
            'quotation' => $quotation,
            'client_bank_accounts' => $this->bankaccount->clientAccountOptions(),
        ]);
    }

    public function update(Request $request, Quotation $quotation) 
    {
        $validated = $request->validate([
            'deposit_amount' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'bank_account_id' => ['required', 'exists:bank_accounts,id']
        ]);

        $quotation->update($validated);

        return back()->with('successMessage', 'Successfully update!');
    }
}
