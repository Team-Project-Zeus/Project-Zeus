<?php

namespace App\Http\Middleware;

use App\Appointment;
use App\User;

use Closure;

class CheckUserRole
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
        $payload = auth()->payload();
        //$user_role = the role from the user, retrieved from the token.
        $user_role = $payload->get('user_role');

        if ($user_role != 'default') {
            return $next($request);
        } else {
            return response()->json('You dont have the right role', 403);
        }
    }
}