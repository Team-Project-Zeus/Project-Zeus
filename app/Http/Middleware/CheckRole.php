<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

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
        //dd(Auth::user());
        if (Auth::check()) {
            if (Auth::user()->user_role === 'Admin') {

                return redirect('error')->with('error', 'Je hebt niet de juiste rol.');
            } else {
                return redirect('home');
            }
        }

        return $next($request);
    }


//        if (Auth::user()->user_role == 'Default') {
//            //user heeft verkeerde rol
//            return redirect('error')->with('error', 'Wrong role...');
//        }
//
//        if (Auth::user()->user_role != 'Default' || '') {
//            //user heeft verkeerde rol
//            return redirect('home')->with('error', 'je bent ingelogd...');
//        }
//
//        if (Auth::check()) {
//            //user is niet ingelogd
//            return redirect('error')->with('error', 'je bent wel logged in...');
//        }

//        return $next($request);
//    }
}
