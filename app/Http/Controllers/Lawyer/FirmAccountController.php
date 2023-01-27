<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\FirmAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FirmAccountController extends Controller
{
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

        return Inertia::render('Lawyer/FirmAccount/Index',[
            'firmAccounts'=> $firmAccounts,
            'filters' => $filters,
            'acc' => $acc,
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
            'bank_account_id'=>$request->bank_account_id,
            'created_by'=>Auth::id(),
        ]);

        return redirect()->route('firm-account.index')->with('message', 'Successfully added new transaction.');
    }

    public function edit(FirmAccount $firmAccount)
    {
        
    }

    public function update(Request $request, FirmAccount $firmAccount)
    {
        
    }

    public function destroy(FirmAccount $firmAccount)
    {
        $firmAccount->delete();   

        return redirect()->route('firm-account.index')->with('message', 'Successfully deleted the account.');
    }

    public function totalBalance(){
        $acc = DB::table('firm_account')->sum('balance')->select('balance')->get();
        dd($acc);
    }
}
