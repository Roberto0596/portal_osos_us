<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class inscriptionFaseTwoMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard("alumn")->user()->inscripcion != 1) 
        {
            $path = $request->path();
            if(Auth::guard("alumn")->user()->inscripcion == 2)
            {
                return redirect()->route("alumn.payment.note");
            }
            return redirect()->route("alumn.charge");
            
        }
        return $next($request);
    }
}
