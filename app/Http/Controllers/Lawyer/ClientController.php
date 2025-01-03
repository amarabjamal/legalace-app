<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Client;
use App\Models\BankAccounts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $filters = FacadesRequest::all(['search']);
        $clients = Client::filter(FacadesRequest::only('search'))
            ->paginate(25)
            ->withQueryString()
            ->through(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_num' => $user->phone_num,
                'id_num' => $user->id_num
            ]);

        return Inertia::render('Lawyer/Client/Index', [
            'filters' => $filters,
            'clients' => $clients,
        ]);
    }

    public function create()
    {
        $clients = BankAccounts::where('bank_account_type_id', 1)->get();


        return Inertia::render('Lawyer/Client/Create', [
            'clients' => $clients
        ]);
    }

    public function store(Request $request)
    {
        //Validate the request
        Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'id_type_id' => $request->id_type_id,
            'id_number' => $request->id_number,
            'phone_number' => $request->phone_number,
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
            'address' => $request->company_address,
            'outstanding_balance' => $request->outstanding_balance,
            'linked_client_account' => $request->linked_client_account,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('lawyer.client.index')->with('message', 'Successfully added new client.');
    }

    public function edit($client_id)
    {
        $clientProfile = Client::query()
            ->where('id', 'like', "%{$client_id}%")
            ->first();
        return Inertia::render('Lawyer/Client/Edit', [
            'client' => $clientProfile
        ]);
    }

    public function update(Request $request)
    {
        $client = Client::findOrFail($request->id);

        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'id_type_id' => $request->id_type_id,
            'id_number' => $request->id_number,
            'phone_number' => $request->phone_number,
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
            'address' => $request->company_address,
            'outstanding_balance' => $request->outstanding_balance,
            'linked_client_account' => $request->linked_client_account,
        ]);

        return redirect()->route('lawyer.client.index')->with('message', 'Successfully updated the client.');
    }

    public function destroy($client_id)
    {
        $client = Client::findOrFail($client_id);

        $client->delete();

        return redirect()->route('lawyer.client.index')->with('message', 'Successfully deleted the client.');
    }
}
