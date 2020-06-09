<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class ApiAuthDriver
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

        if ($request->user() && $request->user()->driver) {
            return $next($request);
        }
        return new Response('', 403);
    }
}
