<?php

namespace App\Http\Controllers\Scholar;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    //
    // for viewing the scholar login page
    public function viewLogin()
    {
        return view('scholar/sc-login');
    }

    // for authenticating scholar in login form
    public function authLogin(Request $request)
    {
        $request->validate([
            'caseCode' => ['required'],
            'password' => ['required']
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt(['caseCode' => $request->caseCode, 'password' => $request->password])) {
            // Check if the user's scStatus is "Active"
            if (Auth::user()->scStatus !== 'Active') {
                Auth::logout(); // Log the user out if not active
                return redirect()->route('scholar-login')->with('failure', 'Your account is suspended. Please contact support for assistance.');
            }

            // Regenerate session if status is Active
            $request->session()->regenerate();

            return redirect()->intended('scholar/schome');
        }

        // If authentication fails
        return back()->with('failure', 'The provided credentials do not match our records.');
    }

    // for logging out in the account
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
