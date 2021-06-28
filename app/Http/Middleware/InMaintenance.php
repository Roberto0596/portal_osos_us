<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ConfigModel;

class InMaintenance
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
        $config = ConfigModel::first();
        if ($config->in_maintenance == 1) {
            return redirect('maintenance');
        }

        return $next($request);
    }
}
