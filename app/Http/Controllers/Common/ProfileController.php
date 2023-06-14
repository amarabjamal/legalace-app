<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{

    public function indexAdmin() {
        $user = Auth::user();
        $user->company;

        return Inertia::render('Admin/Profile', [
            'user' => $user,
        ]);
    } 

    public function indexLawyer() {
        $user = Auth::user();
        $user->company;
        
        return Inertia::render('Lawyer/Profile', [
            'user' => $user,
        ]);
    }
}
