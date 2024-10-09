<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ScAccount;
use Illuminate\Support\Facades\Auth;

class ScholarAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!Auth::check()) {
            // Redirect to the scholar login page if not authenticated
            return redirect()->route('scholar-login');
        }

        // Proceed with the request
        return $next($request);
    }
}
