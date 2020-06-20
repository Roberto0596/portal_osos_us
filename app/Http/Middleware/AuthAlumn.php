<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthAlumn
{
    public function handle($request, Closure $next, $guard = 'alumn')
    {
        if (!Auth::guard($guard)->check()) {
            $path = $request->path();
            return redirect()->route('alumn.login');
        }
        return $next($request);
    }
}
