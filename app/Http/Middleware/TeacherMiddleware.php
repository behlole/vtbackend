<?php

namespace App\Http\Middleware;

use Closure;

class TeacherMiddleware
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
        // Pre-Middleware Action

        if (\auth()->user()->role_type!=1)
        {
            return response('UnAuthorized',401);
        }

        // Post-Middleware Action

        return $next($request);
    }
}
