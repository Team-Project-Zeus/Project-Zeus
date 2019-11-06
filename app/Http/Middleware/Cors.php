<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->header('Access-Control-Allow-Origin:', 'http://projectzeusfrontend.herokuapp.com');

        return $response;
    }
}