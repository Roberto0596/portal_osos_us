<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthDepartament
{
    public function handle($request, Closure $next, $guard = 'departament')
    {
        if (!Auth::guard($guard)->check()) {
            $path = $request->path();
            return redirect()->route('departament.login');
        }
        return $next($request);
    }
}
