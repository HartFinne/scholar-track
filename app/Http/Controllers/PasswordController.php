<?php

namespace App\Http\Controllers;

use App\Http\Middleware\applicant;
use Illuminate\Http\Request;
use App\Models\user;
use App\Models\staccount;
use App\Models\applicants;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $isApplicant = applicants::where('email', $request->email)->exists();
        $isScholar = user::where('scEmail', $request->email)->exists();
        $isStaff = staccount::where('email', $request->email)->exists();

        if ($isApplicant || $isScholar || $isStaff) {
            return redirect()->back()->with('success', 'Reset password link has been sent. Please check your email inbox or spam folder.');
        } else {
            return redirect()->back()->with('error', 'Reset password request failed. The provided email has no match in our database.');
        }
    }
}
