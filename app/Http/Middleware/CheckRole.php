<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Response;


class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->user_role == 'Admin' || Auth::user()->user_role == 'Customer' || Auth::user()->user_role == 'Driving_instructor') {
                return $next($request);
            }

            return redirect('/error')->with('error', 'You do not have access');
       }
    }
}