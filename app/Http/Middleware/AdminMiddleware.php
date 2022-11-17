<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->role->slug !=='admin'){
            return json_response('Failed', ResponseAlias::HTTP_UNAUTHORIZED, '', 'You do not have permission', false);
        }
        return $next($request);
    }
}
