<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ConfigModel;

class inscriptionOpenMiddleware
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
        $config = getConfig();
        if ($config->open_inscription == 0) {
            $path = $request->path();
            return redirect("alumn/charge");
        }
        return $next($request);
    }
}
