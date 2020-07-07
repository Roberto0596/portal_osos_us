<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class inscriptionFaseFour
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard("alumn")->user()->inscripcion != 3)
        {
            $path = $request->path();
            return redirect()->route("alumn.home");
        }
        return $next($request);
    }
}
