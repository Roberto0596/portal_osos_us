<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthFinance
{
    public function handle($request, Closure $next, $guard = 'finance')
    {
        if (!Auth::guard($guard)->check()) {
            $path = $request->path();
            return redirect()->route('finance.login');
        }
        return $next($request);
    }
}
