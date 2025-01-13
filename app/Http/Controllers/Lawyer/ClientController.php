<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Client;
use App\Models\BankAccounts;
use App\Models\CaseFiles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clientAccounts = BankAccounts::where('bank_account_type_id', 1)->get();

        $filters = FacadesRequest::all(['search']);
        $clients = Client::filter(FacadesRequest::only('search'))
            ->paginate(25)
            ->withQueryString()
            ->through(fn($client) => [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone_number' => $client->phone_number,
                'id_num' => $client->id_number,
                'linked_client_account' => $client->linked_client_account,
                'company_name' => $client->company_name,
            ]);

        return Inertia::render('Lawyer/Client/Index', [
            'filters' => $filters,
            'clients' => $clients,
            'clientAccounts' => $clientAccounts,
        ]);
    }

    public function view($client_id)
    {

        $clientProfile = Client::query()
            ->where('id', 'like', "%{$client_id}%")
            ->first();;

        return Inertia::render('Lawyer/Client/View', [
            'clientProfile' => $clientProfile,
        ]);
    }

    public function create()
    {
        $clients = BankAccounts::where('bank_account_type_id', 1)->get();


        return Inertia::render('Lawyer/Client/Create', [
            'clients' => $clients
        ]);
    }

    public function store(StoreClientRequest $request)
    {
        $input = $request->all();
        $input['address'] = $input['company_address'];

        try {
            DB::transaction(function () use ($input) {
                Client::create($input);
            });
        } catch (\Exception $e) {
            dd($e);

            return back()->with('errorMessage', 'Failed to create new client ');
        }
        // If validation fails, this will be executed
        if ($request->fails()) {
            return Inertia::render('Clients/Create', ['errors' => $request->errors()]);
        }

        return redirect()->route('lawyer.client.index')->with('successMessage', 'Successfully added the new client.');
    }

    public function edit($client_id)
    {
        $clients = BankAccounts::where('bank_account_type_id', 1)->get();
        $clientProfile = Client::query()
            ->where('id', 'like', "%{$client_id}%")
            ->first();
        return Inertia::render('Lawyer/Client/Edit', [
            'clientProfile' => $clientProfile,
            'clients' => $clients
        ]);
    }

    public function update(UpdateClientRequest $request)
    {
        $client = Client::findOrFail($request->id);

        $input = $request->all();
        $input['address'] = $input['company_address'];

        try {
            DB::transaction(function () use ($input, $client) {
                $client->update($input);
            });
        } catch (\Exception $e) {
            dd($e);

            return back()->with('errorMessage', 'Failed to create new client ');
        }
        // If validation fails, this will be executed
        if ($request->fails()) {
            return Inertia::render('Clients/Edit', ['errors' => $request->errors()]);
        }

        return redirect()->route('lawyer.client.index')->with('successMessage', 'Successfully updated the client.');
    }

    public function destroy($client_id)
    {
        $client = Client::findOrFail($client_id);

        $case = CaseFiles::find($client->id);

        if ($case == null) {
            $client->delete();
            // return redirect()->route('lawyer.client.index')->with('successMessage', "Successfully deleted the client. {$case->id}");
            return redirect()->route('lawyer.client.index')->with('successMessage', "Successfully deleted the client");
        } else {
            return redirect()->route('lawyer.client.index')->with('errorMessage', "Client still have case files");
        }
    }
}
