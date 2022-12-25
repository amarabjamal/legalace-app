<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

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

        $companyProfile = Company::where('id', '=', $user->company_id)->get();
        
        return Inertia::render('Admin/Dashboard', [
            'total_users' => User::all()->count(),
            'isCompanyProfileConfigured' => sizeof($companyProfile) >= 1 ? true : false,
            'role' => $roles
        ]);
    }
}
