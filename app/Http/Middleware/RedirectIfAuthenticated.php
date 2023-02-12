<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        $userRoles = User::findOrFail(Auth::id())->userRoles;
        $roles = array();

        foreach($userRoles as $userRole) {
            array_push($roles, $userRole->role->slug);
        }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check() && in_array("admin", $roles)) {
                return redirect()->route('dashboard');
            } elseif(Auth::guard($guard)->check() && in_array("lawyer", $roles)){
                return redirect()->route('lawyer.dashboard');
            }
        }

        return $next($request);
    }
}
