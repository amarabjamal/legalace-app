<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check() == true) {
            $userRoles = User::findOrFail(Auth::id())->userRoles;
            $roles = array();
    
            foreach($userRoles as $userRole) {
                array_push($roles, $userRole->role->name);
            }
    
            
            if($roles !== null) {
                if(auth::check() && in_array("admin", $roles)){
                    return $next($request);
                }
                else {
                    return redirect()->route('login')->withErrors([
                        'invalid_role' => 'The user is not an admin',
                    ]);
                }
            } else {
                return redirect()->route('login')->withErrors([
                    'invalid_role' => 'The user is not assigned a role',
                ]);
            }
        } else {
            return redirect()->route('login');
        }
    }
}
