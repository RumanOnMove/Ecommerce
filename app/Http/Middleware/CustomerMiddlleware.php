<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CustomerMiddlleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->role->slug !== 'customer'){
            return json_response('Failed', ResponseAlias::HTTP_UNAUTHORIZED, '', 'You do not have permission', false);
        }
        return $next($request);
    }
}
