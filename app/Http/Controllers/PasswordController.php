<?php

namespace App\Http\Controllers;

use App\Http\Middleware\applicant;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\staccount;
use App\Models\applicants;
use App\Notifications\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PasswordController extends Controller
{
    public function showforgotpass()
    {
        return view('forgotpass');
    }

    public function showchangepass($casecode, $usertype)
    {
        if ($usertype == 'applicant') {
            return view('changepassword', compact('casecode', 'usertype'));
        } else {
            return view('changepassword', compact('casecode', 'usertype'));
        }
    }

    public function exitchangepass($casecode, $usertype)
    {
        if ($usertype == 'applicant') {
            return redirect()->route('applicantportal', $casecode);
        } else {
            return redirect()->route('schome');
        }
    }

    public function submitchangepass($casecode, $usertype, Request $request)
    {
        if ($usertype == 'applicant') {
            $applicant = applicants::where('casecode', $casecode)->first();

            if ($applicant && Hash::check($request->currentpass, $applicant->password)) {
                $applicant->password = Hash::make($request->newpass);
                $applicant->save();
                return redirect()->back()->with('success', 'Password updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Current password is incorrect.');
            }
        } else {
            $scholar = User::where('casecode', $casecode)->first();

            if ($scholar && Hash::check($request->currentpass, $scholar->password)) {
                $scholar->password = Hash::make($request->newpass);
                $scholar->save();
                return redirect()->back()->with('success', 'Password updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Current password is incorrect.');
            }
        }
    }


    public function verifyfprequest(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Attempt to find the user in each of the relevant tables
        $isApplicant = applicants::where('email', $request->email)->first();
        $isScholar = user::where('scEmail', $request->email)->first();
        $isStaff = staccount::where('email', $request->email)->first();

        // Identify the user and the appropriate password broker based on the user type
        if ($isApplicant) {
            $broker = Password::broker('applicant');
            $credentials = ['email' => $request->email];
        } elseif ($isScholar) {
            $broker = Password::broker('users'); // Use the 'users' broker with 'scEmail' field
            $credentials = ['scEmail' => $request->email];
        } elseif ($isStaff) {
            $broker = Password::broker('staff');
            $credentials = ['email' => $request->email];
        } else {
            return redirect()->back()->with('error', 'Reset password request failed. The provided email has no match in our database.');
        }

        // Use `sendResetLink` with modified credentials
        $response = $broker->sendResetLink($credentials);

        if ($response === Password::RESET_LINK_SENT) {
            return redirect()->back()->with('success', 'Reset password link has been sent. Please check your email inbox or spam folder.');
        } else {
            return redirect()->back()->withErrors(['email' => trans($response)]);
        }
    }

    public function passwordReset($token)
    {
        return view('reset-password', ['token' => $token]);
    }


    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email', // Use a generic 'email' field
            'password' => 'required|min:8|confirmed',
        ]);

        // Identify which user type we are resetting the password for
        $isApplicant = applicants::where('email', $request->email)->first();
        $isScholar = user::where('scEmail', $request->email)->first();
        $isStaff = staccount::where('email', $request->email)->first();

        if ($isApplicant) {
            $broker = Password::broker('applicant');
            $credentials = ['email' => $request->email, 'password' => $request->password, 'password_confirmation' => $request->password_confirmation, 'token' => $request->token];
        } elseif ($isScholar) {
            $broker = Password::broker('users'); // Broker for scholars
            $credentials = ['scEmail' => $request->email, 'password' => $request->password, 'password_confirmation' => $request->password_confirmation, 'token' => $request->token];
        } elseif ($isStaff) {
            $broker = Password::broker('staff');
            $credentials = ['email' => $request->email, 'password' => $request->password, 'password_confirmation' => $request->password_confirmation, 'token' => $request->token];
        } else {
            return back()->withErrors(['email' => ['User not found in the database']]);
        }

        // Perform the password reset with the identified broker
        $status = $broker->reset(
            $credentials,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('roleselection')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
