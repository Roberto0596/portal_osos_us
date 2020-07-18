<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthAdmin
{
    public function handle($request, Closure $next, $guard = 'admin')
    {
        if (!Auth::guard($guard)->check()) {
            $path = $request->path();
            return redirect()->route('admin.login');
        }
        return $next($request);
    }
}
