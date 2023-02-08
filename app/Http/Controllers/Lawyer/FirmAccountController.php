<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\FirmAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class FirmAccountController extends Controller
{
    public function index(Request $request)
    {
        $firmAccounts = FirmAccount::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('description', 'like', "%{$search}%");
            })
            ->orderBy('date','desc')
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
            ])
            ;
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
            'date'=> $request->date,
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
        return Inertia::render('Lawyer/FirmAccount/Edit', [
            'firmAccounts' => $firmAccount
        ]);
    }

    public function update(Request $request, FirmAccount $firmAccount)
    {
        $debit = $request->debit;
        $credit = $request->credit;
        $firmAccount->update([
            'date'=> $request->date,
            'description' => $request->description,
            'transaction_type' => $request->transaction_type,
            'debit' => $request->debit,
            'credit' => $request->credit,
            'balance' => $debit - $credit,
            'bank_account_id'=>$request->bank_account_id,
        ]);

        return redirect()->route('firm-account.index')->with('message', 'Successfully updated the transaction.');
    }

    public function destroy(FirmAccount $firmAccount)
    {
        $firmAccount->delete();   

        return redirect()->route('firm-account.index')->with('message', 'Successfully deleted the transaction.');
    }

    public function totalBalance(){
        $acc = DB::table('firm_account')->sum('balance')->select('balance')->get();
        dd($acc);
    }

    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        if (! app()->environment(['local', 'testing']) && in_array($response->status(), [500, 503, 404, 403])) {
            return Inertia::render('Error', ['status' => $response->status()])
                ->toResponse($request)
                ->setStatusCode($response->status());
        } elseif ($response->status() === 419) {
            return back()->with([
                'message' => 'The page expired, please try again.',
            ]);
        }

        return $response;
    }
}
