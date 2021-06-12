<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use function auth;

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (auth()->user()->role_type != 2) {
            return response('UnAuthorized', 401);
        }

        // Post-Middleware Action

        return $next($request);
    }
}
