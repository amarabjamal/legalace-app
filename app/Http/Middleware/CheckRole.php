<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, String $role)
    {
        $roles = [
            'admin' => Role::IS_ADMIN,
            'lawyer' => Role::IS_LAWYER,
        ];

        $roleId = $roles[$role] ?? 0;

        if(!in_array($roleId, auth()->user()->userRoles->pluck('role_id')->toArray())) {
            abort(403);
        }
        
        return $next($request);
    }
}
