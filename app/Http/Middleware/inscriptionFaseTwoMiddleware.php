<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class inscriptionFaseTwoMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard("alumn")->user()->inscripcion != 1) {
            $path = $request->path();
            session()->flash("messages","info|Espera a que verifiquen tu pago");
            return redirect("alumn/home");
        }
        return $next($request);
    }
}
