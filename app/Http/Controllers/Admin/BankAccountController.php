<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBankAccountRequest;
use App\Http\Requests\UpdateBankAccountRequest;
use App\Models\BankAccount;
use Brick\Money\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class BankAccountController extends Controller
{
    protected $bank_account;

    public function __construct(BankAccount $bank_account)
    {
        $this->bank_account = $bank_account;
    }

    public function index()
    {

        return Inertia::render('Admin/BankAccount/Index', [
            'filters' => Request::all('search'),
            'bank_accounts' => BankAccount::filter(Request::only('search'))
                ->paginate(25)
                ->withQueryString()
                ->through(fn($bank_account) => [
                    'id' => $bank_account->id,
                    'label' => $bank_account->label,
                    'bank_name' => $bank_account->bank_name,
                    'account_name' => $bank_account->account_name,
                    'account_number' => $bank_account->account_number,
                    'swift_code' => $bank_account->swift_code,
                    'account_type' => $bank_account->bankAccountType->name,
                ])
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/BankAccount/Create');
    }

    public function store(StoreBankAccountRequest $request) 
    {
        $input = $request->all();
        $input['opening_balance'] = Money::of($input['opening_balance'], 'MYR');

        try {
            DB::transaction(function () use ($input) {
                BankAccount::create($input);
            });
        } catch (\Exception $e) {
            dd($e);

            return back()->with('errorMessage', 'Failed to create new bank accounts ');
        }

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
        $input = $request->all();
        $input['opening_balance'] = Money::of($input['opening_balance'], 'MYR');

        try {
            DB::transaction(function () use ($input, $bank_account) {
                $bank_account->update($input);
            });
        } catch (\Exception $e) {
            dd($e);

            return back()->with('errorMessage', 'Failed to create new bank accounts ');
        }
 
        return redirect()->route('admin.bank-accounts.show', $bank_account)->with('successMessage', 'Successfully updated the bank account.');
    }

    public function show(BankAccount $bank_account)
    {
        return Inertia::render('Admin/BankAccount/Show', [
            'bank_account' => [
                'id' => $bank_account->id,
                'label' => $bank_account->label,
                'account_type' => $bank_account->bankAccountType->name,
                'account_name' => $bank_account->account_name,
                'bank_name' => $bank_account->bank_name,
                'account_number' => $bank_account->account_number,
                'opening_balance' => $bank_account->opening_balance->formatTo('EN-MY'),
                'swift_code' => $bank_account->swift_code,
                'bank_address' => $bank_account->bank_address,
                'created_by' => $bank_account->createdBy->id === auth()->id() ? $bank_account->createdBy->name . ' (You)' : $bank_account->createdBy->name,
            ],
        ]);
    }

    public function destroy(BankAccount $bank_account)
    {
        //Add conditional checking before delete

        DB::transaction(function () use ($bank_account) {
            $bank_account->delete();   
        });

        return redirect()->route('admin.bank-accounts.index')->with('successMessage', 'Successfully deleted the bank account.');
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
