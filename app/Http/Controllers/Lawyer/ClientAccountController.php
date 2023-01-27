<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClientAccount;
use Illuminate\Support\Facades\Auth;

class ClientAccountController extends Controller
{
    public function index(Request $request)
    {
        $clientAccounts = ClientAccount::query()
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

        return Inertia::render('Lawyer/ClientAccount/Index',[
            'clientAccounts'=> $clientAccounts,
            'filters' => $filters
        ]);
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        
    }

    public function edit(Client $client)
    {
        
    }

    public function update(Request $request, Client $client)
    {
        
    }

    public function destroy(ClientAccount $clientAccount)
    {
        $clientAccount->delete();   

        return redirect()->route('client-account.index')->with('message', 'Successfully deleted the client.');
    }
}
