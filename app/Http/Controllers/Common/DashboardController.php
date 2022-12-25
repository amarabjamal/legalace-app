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
            array_push($roles, $userRole->role->name);
        }

        $companyProfile = Company::where('id', '=', $user->company_profile_id)->get();

        if($roles !== null) {
            if(in_array("admin", $roles)){
                return Inertia::render('Admin/Dashboard', [
                    'total_users' => User::all()->count(),
                    'isCompanyProfileConfigured' => sizeof($companyProfile) >= 1 ? true : false,
                    'role' => $roles
                ]);
            }
            else if(in_array("lawyer", $roles)){
                return Inertia::render('Lawyer/Dashboard', [
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
