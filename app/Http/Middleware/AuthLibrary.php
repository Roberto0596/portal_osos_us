<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthLibrary
{
    public function handle($request, Closure $next, $guard = 'library')
    {
        if (!Auth::guard($guard)->check()) {
            $path = $request->path();
            return redirect()->route('library.login');
        }
        return $next($request);
    }
}
