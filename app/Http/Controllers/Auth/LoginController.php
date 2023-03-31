<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            $userRoles = User::findOrFail(Auth::id())->userRoles;
            $roles = array();

            foreach($userRoles as $userRole) {
                array_push($roles, $userRole->role->slug);
            }

            $accessExpiryDate = Auth::user()->access_expiry_date;

            if($accessExpiryDate != null && Carbon::now()->isAfter($accessExpiryDate)) {
                return back()->withErrors([
                    'email' => 'Your account access has expired.',
                ]);
            }

            if($roles != null) {   
                if(Auth::check() && in_array("admin", $roles)){
                    return redirect()->intended('/admin/dashboard');
                } elseif(Auth::check() && in_array("lawyer", $roles)){
                    return redirect()->intended('/lawyer/dashboard');
                }
            } else {
                return back()->withErrors([
                    'email' => 'The user is not assigned a role.',
                ]);
            }
            
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
