<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();
        $allowedRoles = collect($roles)->map(function($role) {
            return explode(',', $role);
        })->flatten()->unique()->all();

        if (!in_array($user->role, $allowedRoles)) {
            return abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}