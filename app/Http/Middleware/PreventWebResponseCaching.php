<?php

namespace App\Http\Middleware;

use Closure;

class PreventWebResponseCaching
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Web responses embed user-specific CSRF tokens and session state.
        $response->headers->set('Cache-Control', 'no-cache, private, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('X-LiteSpeed-Cache-Control', 'no-cache');

        return $response;
    }
}
