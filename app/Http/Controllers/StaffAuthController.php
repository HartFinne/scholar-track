<?php

namespace App\Http\Controllers;

use App\Models\staccount;
use App\Notifications\AccountCreationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
                        return redirect()->back()->with('failure', 'Unknown role. Access denied.');
                }
            } else {
                return redirect()->back()->with('failure', 'Your account has been deactivated. If this was an error, please contact us at inquiriescholartrack@gmail.com for assistance.');
            }
        } else {
            return redirect()->back()->with('failure', 'Invalid email or password.');
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'newpassword' => [
                    'required',
                    'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/',
                ],
                'confirmpassword' => 'required|same:newpassword',
            ], [
                'confirmpassword.same' => 'The new password and confirm password must match.',
                'newpassword.regex' => 'The new password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long.',
            ]);

            // Get the currently authenticated user
            $user = Auth::guard('staff')->user();
            $user = staccount::where('id', $user->id)->first();

            // Check if the current password matches the user's actual password
            if (!Hash::check($request->currentpassword, $user->password)) {
                return redirect()->back()->with('failure', 'The current password is incorrect.');
            }
            DB::beginTransaction();
            $user->password = Hash::make($request->newpassword);
            $user->save();
            DB::commit();
            // Redirect with success message
            return redirect()->back()->with('success', 'Password changed successfully.');
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
            DB::rollBack();
            return redirect()->back()->with('failure', 'Failed to change password. If this issue persists, please contact us at inquiriescholartrack@gmail.com');
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

            // Generate password using the last part of the name
            $nameParts = explode(' ', strtolower($request->name));
            $password = end($nameParts) . '.st'; // password: surname.st

            // Create the account in the database
            $staccount = staccount::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobileno' => $mobileno,
                'area' => $request->area,
                'role' => $request->role,
                'status' => $status,
                'password' => Hash::make($password),
            ]);

            // Fetch the created account
            $staff = Staccount::where('email', $request->email)->first();

            // Send notification to email only
            try {
                $staff->notify(new AccountCreationNotification($staccount, $password));
                return redirect()->route('users-staff')->with('success', 'Account created successfully and email sent.');
            } catch (\Exception $e) {
                Log::error('Email Notification Error', ['error' => $e->getMessage()]);
                return redirect()->route('users-staff')->with('failure', 'Account created, but email notification failed.');
            }
        } catch (\Exception $e) {
            return redirect()->route('users-staff')->with('failure', 'Account creation was unsuccessful. ' . $e->getMessage());
        }
    }


    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login-sw');
    }
}
