<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\TokenMismatchException;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        try {
            if (Auth::guard($guard)->check()) {
                return redirect('/schome');
            }

            return $next($request);
        } catch (TokenMismatchException) {
            return redirect()->back();
        }
    }
}
