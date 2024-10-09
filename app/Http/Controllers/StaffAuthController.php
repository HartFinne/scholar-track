<?php

namespace App\Http\Controllers;

use App\Models\staccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StaffAuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the input fields
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (Auth::guard('staff')->attempt($request->only('email', 'password'))) {
            // Authentication successful, get the authenticated user
            $user = Auth::guard('staff')->user();

            // Check user account status
            if ($user->status === 'Active') {
                // Regenerate the session to avoid session fixation
                $request->session()->regenerate();

                // Check user role and redirect accordingly
                switch ($user->role) {
                    case 'Social Worker':
                        return redirect()->route('home-sw');
                    case 'System Admin':
                        return redirect()->route('dashboard');
                    default:
                        return redirect()->back()->with('error', 'Unknown role. Access denied.');
                }
            } else {
                return redirect()->back()->with('error', 'Your account has been deactivated. If this was an error, please contact us at support@example.com or call us at +1 234 567 8901 for assistance.');
            }
        } else {
            // Authentication failed
            return redirect()->back()->with('error', 'Invalid email or password.');
        }
    }

    public function createAccount(Request $request)
    {
        try {
            // Validate the input data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:staccounts,email',
                'role' => 'required|string|max:25',
                'area' => 'required|string|max:25',
                'mobileno' => 'nullable|string|max:11|unique:staccounts,mobileno',
            ]);

            // Mobileno default value
            $mobileno = null;

            // Status default value
            $status = "Active";

            // Generate the password
            $nameParts = explode(' ', strtolower($request->name));
            $password = end($nameParts) . '.scholartrack';

            // Create the account
            Staccount::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobileno' => $mobileno,
                'area' => $request->area,
                'role' => $request->role,
                'status' => $status,
                'password' => Hash::make($password), // hash the password
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
