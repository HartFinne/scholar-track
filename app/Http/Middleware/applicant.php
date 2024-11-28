<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Session\TokenMismatchException;

class applicant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Check if the user is authenticated with the 'applicant' guard
            if (!Auth::guard('applicant')->check()) {
                return redirect()->route('login-applicant')->with('failure', 'Access denied. Please log in to your account first.');
            }

            $session = DB::table('sessions')
                ->where('id', session()->getId())  // Get session ID from the current request
                ->first();

            // If a session exists, check inactivity
            if ($session) {
                // Convert the last_activity timestamp to a Carbon instance
                $lastActivity = Carbon::parse($session->last_activity);
                $now = Carbon::now();

                // If the user has been inactive for more than 15 minutes
                if ($lastActivity->diffInMinutes($now) > 5) {
                    // Log out the user due to inactivity
                    Auth::guard('applicant')->logout();
                    session()->invalidate();
                    session()->regenerateToken();

                    // Redirect the user to the login page with a message
                    return redirect()->route('login-applicant')->with('failure', 'Your session has expired due to inactivity. Please log in again.');
                }
            }

            $user = Auth::guard('applicant')->user();

            if ($user->accountstatus == 'Inactive') {
                Auth::guard('applicant')->logout();
                session()->invalidate();
                session()->regenerateToken();

                // Redirect the user to the login page with a message
                return redirect()->route('login-applicant')->with('failure', 'Your account has been deactivated. If you believe this is a mistake, please contact us at inquiriescholartrack@gmail.com for assistance.');
            }


            // Continue processing the request
            return $next($request);
        } catch (TokenMismatchException) {
            return redirect()->back();
        }
    }
}
