<?php

namespace App\Http\Middleware;

use Closure;

class inscriptionFaseFour
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard("alumn")->user()->inscripcion != 3) {
            $path = $request->path();
            return redirect("alumn/payment");
        }
        return $next($request);
    }
}
