<?php

namespace App\Http\Middleware;

use Closure;

class semesterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $period = selectCurrentPeriod();

        if ($period->semestre == 1) {
            return redirect()->route("alumn.home");
        }
        return $next($request);
    }
}
