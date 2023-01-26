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
    public function index()
    {
        $user = Auth::user();
        $userRoles = User::findOrFail($user->id)->userRoles;
        $roles = array();

        foreach($userRoles as $userRole) {
            array_push($roles, $userRole->role->slug);
        }

        $company = $user->company;
        $companyName = $user->company->name;

        if($roles !== null) {
            if(in_array("admin", $roles)){
                return Inertia::render('Admin/Dashboard', [
                    'total_users' => User::all()->count(),
                    'isCompanyProfileConfigured' => $company != null ? true : false,
                    'role' => $roles
                ]);
            }
            else if(in_array("lawyer", $roles)){
                return Inertia::render('Lawyer/Dashboard', [
                    'user' => $user,
                    'company' => $companyName,
                    'role' => $roles
                ]);
            }
        } else {
            return redirect()->route('login')->withErrors([
                'invalid_role' => 'The user is not assigned a role',
            ]);
        }
    }
}
