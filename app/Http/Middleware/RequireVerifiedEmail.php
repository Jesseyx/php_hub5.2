<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class RequireVerifiedEmail
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
        if (Auth::check() && !Auth::user()->verified) {
            return redirect(route('email-verification-required'));
        }
        
        return $next($request);
    }
}
