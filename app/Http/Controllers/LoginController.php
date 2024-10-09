<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //

    public function viewLogin()
    {
        return view('scholar/sc-login');
    }

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

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
