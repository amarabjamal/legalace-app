<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\FirmAccount;
use App\Models\FirmAccountList;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

class FirmAccountController extends Controller
{
    public function index(Request $request)
    {
        $accList = DB::table('firm_account');

        $firmAccountList = FirmAccountList::query()
            ->where('bank_account_type_id', 'like', '2')
            ->paginate(10)
            ->withQueryString()
            ->through(fn($accList) => [
                'id' => $accList->id,
                'label' => $accList->label,
                'account_name' => $accList->account_name,
                'bank_name' => $accList->bank_name,
                'account_number' => $accList->account_number,
                'opening_balance' => $accList->opening_balance,
                'swift_code' => $accList->swift_code,
            ]);


        return Inertia::render('Lawyer/FirmAccount/Index', [
            'firmAccountList' => $firmAccountList,
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

    public function detail(Request $request, $acc_number)
    {
        $filters = FacadesRequest::all(['search']);
        $accList = DB::table('firm_account');

        $bankAccount = FirmAccountList::query()
            ->where('id', 'like', "%{$acc_number}%")
            ->paginate(10)
            ->withQueryString()
            ->through(fn($accList) => [
                'id' => $accList->id,
                'label' => $accList->label,
                'account_name' => $accList->account_name,
                'bank_name' => $accList->bank_name,
                'account_number' => $accList->account_number,
                'opening_balance' => $accList->opening_balance,
                'swift_code' => $accList->swift_code,
            ]);

        $firmAccounts = FirmAccount::query()
            ->where('bank_account_id', 'like', "%{$acc_number}%")
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


        $acc = DB::table('firm_account')->sum('balance');

        return Inertia::render('Lawyer/FirmAccount/Details', [
            'firmAccounts' => $firmAccounts,
            'acc' => $acc,
            'filters' => FacadesRequest::all('search'),
            'bank_accounts' => $bankAccount,
        ]);
    }

    public function detailFilter(Request $request, $acc_number, $t_type)
    {
        $filters = FacadesRequest::all(['search']);
        $accList = DB::table('firm_account');
        $filter_type = 0;
        if ($t_type == 0) {
            $filter_type = "funds out";
        } else {
            $filter_type = "funds in";
        }

        $bankAccount = FirmAccountList::query()
            ->where('id', 'like', "%{$acc_number}%")
            ->paginate(10)
            ->withQueryString()
            ->through(fn($accList) => [
                'id' => $accList->id,
                'label' => $accList->label,
                'account_name' => $accList->account_name,
                'bank_name' => $accList->bank_name,
                'account_number' => $accList->account_number,
                'opening_balance' => $accList->opening_balance,
                'swift_code' => $accList->swift_code,
            ]);

        $firmAccounts = FirmAccount::query()
            ->where('bank_account_id', 'like', "%{$acc_number}%")
            ->where('transaction_type', 'like', "funds out")
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


        $acc = DB::table('firm_account')->sum('balance');

        return Inertia::render('Lawyer/FirmAccount/Details', [
            'firmAccounts' => $firmAccounts,
            'acc' => $acc,
            'filters' => FacadesRequest::all('search'),
            'bank_accounts' => $bankAccount,
        ]);
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
