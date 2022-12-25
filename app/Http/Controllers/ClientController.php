<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index()
    {
        return Inertia::render('Lawyer/Client/Index', [
            
        ]);
    }

    public function create()
    {
        // $userId = Auth::id();

        // $companyProfile = Company::where('user_id', '=', $userId)->get();

        // if (sizeof($companyProfile) < 1)
        // {
            return Inertia::render('Lawyer/Client/Create');
        // }
        
        // return redirect()->route('company-profile.index');
    }

}
