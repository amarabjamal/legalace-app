<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBankAccountRequest;
use App\Http\Requests\UpdateBankAccountRequest;
use App\Models\BankAccount;
use Inertia\Inertia;

class ManageBankAccount extends Controller
{
    protected $bankaccount;

    public function __construct(BankAccount $bankaccount)
    {
        $this->bankaccount = $bankaccount;
    }

    public function index()
    {

        return Inertia::render('Admin/BankAccount/Index', [
            'bankAccounts' => $this->bankaccount->allBankAccounts(),
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

        return redirect()->route('admin.bankaccounts.index')->with('message', 'Successfully added new bank account [' . $validated['label'] . '].');
    }

    public function edit(BankAccount $bankaccount)
    {
        $bankaccount->bankAccountType;

        return Inertia::render('Admin/BankAccount/Edit', [
            'bankAccount' => $bankaccount,
        ]);
    }

    public function update(UpdateBankAccountRequest $request, BankAccount $bankaccount) 
    {
        $validated = $request->validated();

        $bankaccount->update($validated);
 
         return redirect()->route('admin.bankaccounts.index')->with('message', 'Successfully updated the bank account [' . $request->label . '].');
    }

    public function destroy(BankAccount $bankaccount)
    {
        //Add conditional checking before delete
        $bankaccount->delete();   

        return redirect()->route('admin.bankaccounts.index')->with('message', 'Successfully deleted the bank account.');
    }
}
