<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserRoleDrivingInstructor
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

        if ($user_role === 'driving_instructor' ) {
            return $next($request);
        }else{
            return response()->json('You dont have the right role', 403);
        }    }
}
