<?php

namespace App\Http\Middleware;

use Closure;

class BitacoraMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public $tokenApp = "93UrDxxqisLGljpo73c29UCgIVKNMT4Q";

    public function handle($request, Closure $next)
    {
        $token = $request->header('x-app');
        if($token && $token == $this->tokenApp) {
            return $next($request);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
