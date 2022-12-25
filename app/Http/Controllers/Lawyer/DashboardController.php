<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userRoles = User::findOrFail($user->id)->userRoles;
        $roles = array();

        foreach($userRoles as $userRole) {
            array_push($roles, $userRole->role->name);
        }

        return Inertia::render('Lawyer/Dashboard', [
            'total_users' => User::all()->count(),
            'role' => $roles
        ]);
    }
}
