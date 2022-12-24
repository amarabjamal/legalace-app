<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $companyProfile = Company::where('id', '=', $user->company_profile_id)->get();
        
        return Inertia::render('Admin/Dashboard', [
            'total_users' => User::all()->count(),
            'isCompanyProfileConfigured' => sizeof($companyProfile) >= 1 ? true : false,
        ]);
    }
}
