<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\FirmAccount;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

class FirmAccountController extends Controller
{
    // protected $bank_account;
    // public function __construct(BankAccount $bank_account)
    // {
    //     $this->bank_account = $bank_account;
    // }

    public function index(Request $request)
    {
        $firmAccounts = FirmAccount::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString()
            ->through(fn($acc) => [
                'id' => $acc->id,
                'date' => $acc->date,
                'description' => $acc->description,
                'transaction_type' => $acc->transaction_type,
                'debit' => $acc->debit,
                'credit' => $acc->credit,
                'balance' => $acc->balance,
            ]);
        $filters = $request->only(['search']);

        $acc = DB::table('firm_account')->sum('balance');

        // $bankAccount = $this->bankAccount->all();
        // $bankAccounts = BankAccount::all()->map(function ($bank_account) {
        //     return [
        //         'id' => $bank_account->id,
        //         'label' => $bank_account->label,
        //         'bank_name' => $bank_account->bank_name,
        //         'account_name' => $bank_account->account_name,
        //         'account_number' => $bank_account->account_number,
        //         'swift_code' => $bank_account->swift_code,
        //         'account_type' => $bank_account->bankAccountType->name,
        //     ];
        // });

        $bankAccounts = BankAccount::filter(FacadesRequest::only('search'))
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
            ]);

        return Inertia::render('Lawyer/FirmAccount/Index', [
            'firmAccounts' => $firmAccounts,
            'filters' => $filters,
            'acc' => $acc,
            // 'bankAccounts' => $bank_accounts,
            'filters' => FacadesRequest::all('search'),
            'bank_accounts' => $bankAccounts,
        ]);
    }

    public function create()
    {
        return Inertia::render('Lawyer/FirmAccount/Create');
    }

    public function store(Request $request)
    {
        $debit = $request->debit;
        $credit = $request->credit;
        FirmAccount::create([
            'description' => $request->description,
            'transaction_type' => $request->transaction_type,
            'debit' => $request->debit,
            'credit' => $request->credit,
            'balance' => $debit - $credit,
            'bank_account_id' => $request->bank_account_id,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('firm-account.index')->with('message', 'Successfully added new transaction.');
    }

    public function edit(FirmAccount $firmAccount) {}

    public function update(Request $request, FirmAccount $firmAccount) {}

    public function destroy(FirmAccount $firmAccount)
    {
        $firmAccount->delete();

        return redirect()->route('firm-account.index')->with('message', 'Successfully deleted the account.');
    }

    public function totalBalance()
    {
        $acc = DB::table('firm_account')->sum('balance')->select('balance')->get();
        dd($acc);
    }
}
