<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Clerk;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $companyProfile = Company::where('user_id', '=', $userId)->get();
        
        return Inertia::render('Admin/Dashboard', [
            'total_clerks' => Clerk::all()->count(),
            'total_users' => User::all()->count(),
            'isCompanyProfileConfigured' => sizeof($companyProfile) >= 1 ? true : false,
        ]);
    }
}
