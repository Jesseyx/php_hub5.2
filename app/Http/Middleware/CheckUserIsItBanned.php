<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckUserIsItBanned
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
        if (Auth::check() && Auth::user()->is_banned == 'yes' && request()->is('user-banned') == false) {
            return redirect('/user-banned');
        }

        return $next($request);
    }
}
