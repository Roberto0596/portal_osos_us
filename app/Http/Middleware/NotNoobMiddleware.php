<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class NotNoobMiddleware
{
    public function handle($request, Closure $next, $guard = 'alumn')
    {
        if (Auth::guard($guard)->user()->id_alumno == null) {
            $path = $request->path();
            return redirect()->route('alumn.form.reinscription');
        }
        return $next($request);
    }
}
