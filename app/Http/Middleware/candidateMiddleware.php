<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class candidateMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard("alumn")->user()->id_alumno == null) {
            $path = $request->path();
            return redirect("/alumn");
        }
        return $next($request);
    }
}
