<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBankAccountRequest;
use App\Http\Requests\UpdateBankAccountRequest;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ManageBankAccount extends Controller
{
    protected $bank_account;

    public function __construct(BankAccount $bank_account)
    {
        $this->bank_account = $bank_account;
    }

    public function index()
    {

        return Inertia::render('Admin/BankAccount/Index', [
            'bankAccounts' => $this->bank_account->allBankAccounts(),
        ]);
    }

    public function create()
    {

        return Inertia::render('Admin/BankAccount/Create');
    }

    public function store(StoreBankAccountRequest $request) 
    {
        $validated = $request->validated();

        BankAccount::create($validated);

        return redirect()->route('admin.bank-accounts.index')->with('successMessage', 'Successfully added the new bank account.');
    }

    public function edit(BankAccount $bank_account)
    {
        $bank_account->bankAccountType;

        return Inertia::render('Admin/BankAccount/Edit', [
            'bankAccount' => $bank_account,
        ]);
    }

    public function update(UpdateBankAccountRequest $request, BankAccount $bank_account) 
    {
        $validated = $request->validated();

        $bank_account->update($validated);
 
         return redirect()->route('admin.bank-accounts.index')->with('successMessage', 'Successfully updated the bank account.');
    }

    public function destroy(BankAccount $bank_account)
    {
        //Add conditional checking before delete
        $bank_account->delete();   

        return redirect()->route('admin.bank-accounts.index')->with('successMessage', 'Successfully deleted the bank account.');
    }

    public function getBankAccountDetails(Request $request,BankAccount $bank_account)
    {
        if($request->ajax()) {
            return response()->json([ 'hello' => 'hihiho']);
        }

        return response()->json([ 'hello' => 'hihihu']);
    }

    public function fetchBankAccounts()
    {
        return [
            'client_accounts' => $this->bank_account->clientAccountOptions(),
            'firm_accounts' => $this->bank_account->firmAccountOptions(),
        ];
    }

    public function fetchBankAccountDetails(BankAccount $bank_account)
    {
        $bank_account->bankAccountType;

        return array_merge(
            $bank_account->only(['label', 'account_name', 'bank_name', 'account_number', 'swift_code']),
            [ 'account_type' => $bank_account->bankAccountType->name ]
        );
    }
}
