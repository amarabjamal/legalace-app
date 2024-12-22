<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Client;
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
        return Inertia::render('Lawyer/Client/Create');
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
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('lawyer.client.index')->with('message', 'Successfully added new client.');
    }

    public function edit(Client $client)
    {
        return Inertia::render('Lawyer/Client/Edit', [
            'client' => $client
        ]);
    }

    public function update(Request $request, Client $client)
    {
        $email = Client::where([
            ['email', '=', $request->email],
            ['id', '!=', $client->id],
        ])->first();
        $errors = array();

        $client->update([
            'name' => $request->name,
            'id_types_id' => $request->id_types_id,
            'id_num' => $request->id_num,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        return redirect()->route('clients.index')->with('message', 'Successfully updated the client.');
    }

    public function destroy(Client $client)
    {
        //Add conditional checking before delete
        $client->delete();

        return redirect()->route('clients.index')->with('message', 'Successfully deleted the client.');
    }
}
