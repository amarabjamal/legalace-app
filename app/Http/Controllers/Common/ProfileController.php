<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userRoles = $user->userRoles;
        $roles = array();

        foreach($userRoles as $userRole) {
            array_push($roles, $userRole->role->slug);
        }

        $company = $user->company;
        $companyName = $user->company->name;

        if($roles !== null) {
            if(in_array("admin", $roles)){
                return Inertia::render('Admin/Profile', [
                    'user' => $user,
                ]);
            }
            else if(in_array("lawyer", $roles)){
                return Inertia::render('Lawyer/Profile', [
                    'user' => $user,
                ]);
            }
        } else {
            return redirect()->route('login')->withErrors([
                'invalid_role' => 'The user is not assigned a role',
            ]);
        }
    }
}
