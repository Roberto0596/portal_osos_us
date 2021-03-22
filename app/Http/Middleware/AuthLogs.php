<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthLogs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'log_auth')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect()->route('logs.auth');
        }
        return $next($request);
    }
}
