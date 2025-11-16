<?php

namespace Encodex\Metheme\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale(session('locale', app()->getLocale()));
        return $next($request);
    }
}
