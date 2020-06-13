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

        if ($request->user() && $request->user()->driver && $request->user()->driver->verified_at) {
            return $next($request);
        }
        if (!$request->user()->driver->verified_at) return response()->json(["status"=>"not_available"]);
        if ($request->expectsJson()) {
            return response()->json(["message"=>"Unauthenticated."], 401);
        }
        return new Response('', 401);
    }
}
