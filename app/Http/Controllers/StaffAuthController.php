<?php

namespace App\Http\Controllers;

use App\Models\staccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StaffAuthController extends Controller
{
    public function showLogin()
    {
        return view('staff.login');
    }

    public function login(Request $request)
    {
        if (Auth::guard('staff')->attempt($request->only('email', 'password'))) {
            $user = Auth::guard('staff')->user();

            if ($user->status === 'Active') {
                $request->session()->regenerate();

                switch ($user->role) {
                    case 'Social Worker':
                        return redirect()->route('home-sw');
                    case 'System Admin':
                        return redirect()->route('dashboard');
                    default:
                        return redirect()->back()->with('error', 'Unknown role. Access denied.');
                }
            } else {
                return redirect()->back()->with('error', 'Your account has been deactivated. If this was an error, please contact us at inquiriescholartrack@gmail.com for assistance.');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid email or password.');
        }
    }

    public function createAccount(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:staccounts,email',
                'role' => 'required|string|max:25',
                'area' => 'required|string|max:25',
                'mobileno' => 'nullable|string|max:11|unique:staccounts,mobileno',
            ]);

            $mobileno = null;

            $status = "Active";

            $nameParts = explode(' ', strtolower($request->name));
            $password = end($nameParts) . '.scholartrack';

            Staccount::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobileno' => $mobileno,
                'area' => $request->area,
                'role' => $request->role,
                'status' => $status,
                'password' => Hash::make($password),
            ]);

            return redirect()->route('users-staff')->with('success', 'Account created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('users-staff')->with('error', 'Account creation was unsuccessful. ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login-sw');
    }
}
