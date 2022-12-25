<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        return Inertia::render('Lawyer/Client/Index', [
            
        ]);
    }

    public function create()
    {
        $userId = Auth::id();

        $client = Client::where('user_id', '=', $userId)->get();

        if (sizeof($client) < 1)
        {
            return Inertia::render('Lawyer/Client/Create');
        }
        
        return redirect()->route('clients.index');
    }

}
