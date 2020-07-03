<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class inscriptionMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard("alumn")->user()->inscripcion != 0) {
            $path = $request->path();
            return redirect("alumn/payment");
        }
        return $next($request);
    }
}
