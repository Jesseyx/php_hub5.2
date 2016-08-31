<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class RecordLastActivedTime
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
        if (Auth::check() && request()->is('notifications/count') == false) {
            Auth::user()->recordLastActivedAt();
        }

        return $next($request);
    }
}
