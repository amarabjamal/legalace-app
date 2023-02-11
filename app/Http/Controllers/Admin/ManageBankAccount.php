<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
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
        dd($request);
        $validated = $request->validate([
            'account_name' => [],
            'bank_name' => [],
            'account_number' => [],
            'bank_address' => [],
            'swift_code' => [],
            'bank_account_type_id' => [],
            'label' => [],
        ]);

        BankAccount::create([
            'account_name' => $validated['account_name'],
            'bank_name' => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'bank_address' => $validated['bank_address'],
            'swift_code' => $validated['swift_code'],
            'bank_account_type_id' => $validated['bank_account_type_id'],
            'label' => $validated['label'],
            'created_by' => Auth::id(),
            'company_id' => Auth::user()->company_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.bankaccounts.index')->with('message', 'Successfully added new bank account (' . $request->account_name . ').');
    }

    public function edit(BankAccount $bankaccount)
    {
        $BankAccountType = $bankaccount->bankAccountType;

        return Inertia::render('Admin/BankAccount/Edit', [
            'bankAccount' => $bankaccount,
        ]);
    }

    public function update(Request $request, BankAccount $bankaccount) {

         $bankaccount->update([
             'account_name' => $request->account_name,
             'bank_name' => $request->bank_name,
             'account_number' => $request->account_number,
             'bank_address' => $request->bank_address,
             'swift_code' => $request->swift_code,
             'bank_account_type_id' => $request->bank_account_type_id,
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
