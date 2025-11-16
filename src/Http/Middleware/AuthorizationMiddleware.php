<?php

namespace Encodex\Metheme\Http\Middleware;

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
            if (view()->exists('metheme::auth.unauthorize1')) {
                return response()->view('metheme::auth.unauthorize1', ['permission' => $permission], 403);
            }

            return response("Unauthorized Action: {$permission}", 403);
        }

        return $next($request);
    }
}
