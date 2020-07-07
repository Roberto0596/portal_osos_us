<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthComputer
{
    public function handle($request, Closure $next, $guard = 'computercenter')
    {
        if (!Auth::guard($guard)->check()) {
            $path = $request->path();
            return redirect()->route('computo.login');
        }
        return $next($request);
    }
}
