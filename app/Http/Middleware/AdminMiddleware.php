<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AdminMiddleware
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
        // return $next($request);
        if (Auth::user()->usertype == "admin") {
            return $next($request);
        } else {
            return \redirect('/login')->with('status', 'Anda tidak diperbolehkan untuk akses dashboard admin.');
        };
    }
}
