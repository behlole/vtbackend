<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class StudentMiddleware
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

        if (\auth()->user()->role_type!=2)
        {
            return response('UnAuthorized',401);
        }

        // Post-Middleware Action

        return $next($request);
    }
}
