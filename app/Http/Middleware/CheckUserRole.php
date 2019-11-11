<?php

namespace App\Http\Middleware;

use App\appointments;
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
        $student = User::where('id', '=', $request->student)->first();
//        $product = User::find($student);


        if ($user_role === 'driving_instructor') {
            if ($student->user_role === 'student') {
                return $next($request);
            }else{
                return response()->json('You dont have the right role', 403);
            }
        }else{
            return response()->json('You dont have the right role', 403);
        }
    }
}
