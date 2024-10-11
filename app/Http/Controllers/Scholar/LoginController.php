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

        if (Auth::attempt(['caseCode' => $request->caseCode, 'password' => $request->password])) {
            $request->session()->regenerate();

            return redirect()->intended('scholar/schome');
        }

        return back()->withErrors([
            'caseCode' => 'The provided do not match',
        ]);
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
