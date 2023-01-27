<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // $companyProfile = Company::where('id', '=', $user->company_id)->get();
        $company = $user->company;
        $companyName = $user->company->name;

        $totalClient = DB::table('client_accounts')->count();
        $firmAccBalance = DB::table('firm_account')->sum('balance');

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
                    'role' => $roles,
                    'totalClient' => $totalClient,
                    'firmAccBalance' => $firmAccBalance,
                ]);
            }
        } else {
            return redirect()->route('login')->withErrors([
                'invalid_role' => 'The user is not assigned a role',
            ]);
        }
    }
}
