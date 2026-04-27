<?php

namespace ME\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage:
     * ->middleware(AuthorizationMiddleware::class . ':permission-name')
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        // 🔒 যদি user লগইন না করে
        if (Auth::guest()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = Auth::user();
        // dd($user->hasPermission($permission));
        // 🔑 Permission check
        if (! $user->hasPermission($permission)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
