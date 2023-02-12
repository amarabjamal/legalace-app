<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBankAccountRequest;
use App\Http\Requests\UpdateBankAccountRequest;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ManageBankAccount extends Controller
{
    public function index()
    {
        $bankAccountConditions = [ 'company_id' => Auth::user()->company_id ];
        $bankAccounts = BankAccount::where($bankAccountConditions)->with('createdBy:id,name', 'bankAccountType:id,name')->get();

        return Inertia::render('Admin/BankAccount/Index', [
            'bankAccounts' => $bankAccounts,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/BankAccount/Create');
    }

    public function store(StoreBankAccountRequest $request) {
        $validated = $request->validated();

        BankAccount::create($validated);

        return redirect()->route('admin.bankaccounts.index')->with('message', 'Successfully added new bank account (' . $validated['label'] . ').');
    }

    public function edit(BankAccount $bankaccount)
    {
        $BankAccountType = $bankaccount->bankAccountType;

        return Inertia::render('Admin/BankAccount/Edit', [
            'bankAccount' => $bankaccount,
        ]);
    }

    public function update(UpdateBankAccountRequest $request, BankAccount $bankaccount) {
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
