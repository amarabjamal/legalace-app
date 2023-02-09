<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\BankAccountType;
use Illuminate\Http\Request;
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

    public function store(Request $request) {
        //Data validation
        
        $accountType = BankAccountType::where('slug', $request->account_type)->firstOrFail();

        BankAccount::create([
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'bank_address' => $request->bank_address,
            'swift_code' => $request->swift_code,
            'account_type' => $accountType->id,
            'label' => $request->label,
            'created_by' => Auth::id(),
            'company_id' => Auth::user()->company_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('bankaccounts.index')->with('message', 'Successfully added new bank account (' . $request->account_name . ').');
    }

    public function edit(BankAccount $bankaccount)
    {
        $BankAccountType = $bankaccount->bankAccountType;

        return Inertia::render('Admin/BankAccount/Edit', [
            'bankAccount' => $bankaccount,
        ]);
    }

    public function update(Request $request, BankAccount $bankaccount) {

         //Data validation
        
         $accountType = BankAccountType::where('slug', $request->bank_account_type)->firstOrFail();

         $bankaccount->update([
             'account_name' => $request->account_name,
             'bank_name' => $request->bank_name,
             'account_number' => $request->account_number,
             'bank_address' => $request->bank_address,
             'swift_code' => $request->swift_code,
             'account_type' => $accountType->id,
             'label' => $request->label,
             'updated_at' => now(),
         ]);
 
         return redirect()->route('admin.bankaccounts.index')->with('message', 'Successfully updated the bank account [' . $request->label . '].');
    }

    public function destroy(BankAccount $bankaccount)
    {
        //Add conditional checking before delete
        $bankaccount->delete();   

        return redirect()->route('admin.bankaccounts.index')->with('message', 'Successfully deleted the bank account.');
    }
}
