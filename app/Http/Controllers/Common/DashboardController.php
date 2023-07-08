<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function redirectToDashboard() 
    {
        $user = auth()->user();

        if($user->hasRole('admin')) {
            return $this->indexAdmin();
        } else if($user->hasRole('lawyer')) {
            return $this->indexLawyer();
        }
    }

    public function indexAdmin() 
    {
        return Inertia::render('Admin/Dashboard', [
            'total_users' => User::all()->count(),
        ]);
    }

    public function indexLawyer() 
    {

        return Inertia::render('Lawyer/Dashboard');
    }
}
