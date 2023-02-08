<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClientAccount;
use Illuminate\Support\Facades\Auth;
use Throwable;
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

    public function edit(ClientAccount $client)
    {
        
    }

    public function update(Request $request, ClientAccount $client)
    {
        
    }

    public function destroy(ClientAccount $clientAccount)
    {
        $clientAccount->delete();   

        return redirect()->route('client-account.index')->with('message', 'Successfully deleted the client.');
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
