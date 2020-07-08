<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class inscriptionFaseThreeMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard("alumn")->user()->inscripcion != 2) {
            $path = $request->path();
            return redirect("alumn/charge");
        }
        return $next($request);
    }
}
