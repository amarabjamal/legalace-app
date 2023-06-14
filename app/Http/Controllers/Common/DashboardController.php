<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{

    public function indexAdmin() {
        $user = Auth::user();
        $userRoles = User::findOrFail($user->id)->userRoles;
        $roles = array();

        foreach($userRoles as $userRole) {
            array_push($roles, $userRole->role->slug);
        }

        return Inertia::render('Admin/Dashboard', [
            'total_users' => User::all()->count(),
            'role' => $roles
        ]);
    }

    public function indexLawyer() {
        $user = Auth::user();
        $userRoles = User::findOrFail($user->id)->userRoles;
        $roles = array();

        foreach($userRoles as $userRole) {
            array_push($roles, $userRole->role->slug);
        }

        $companyName = $user->company->name;

        return Inertia::render('Lawyer/Dashboard', [
            'user' => $user,
            'company' => $companyName,
            'role' => $roles
        ]);
    }
}
