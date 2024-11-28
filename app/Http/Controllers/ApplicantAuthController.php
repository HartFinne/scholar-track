<?php

namespace App\Http\Controllers;

use App\Models\applicants;
use App\Models\apfamilyinfo;
use App\Models\apceducation;
use App\Models\applicationforms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantAuthController extends Controller
{
    public function showportal($casecode)
    {
        $applicant = applicants::with('educcollege', 'educelemhs', 'otherinfo', 'requirements', 'casedetails')
            ->where('casecode', $casecode)
            ->first();
        $father = apfamilyinfo::where('casecode', $casecode)
            ->where('relationship', 'Father')->first();
        $mother = apfamilyinfo::where('casecode', $casecode)
            ->where('relationship', 'Mother')->first();
        $siblings = apfamilyinfo::where('casecode', $casecode)
            ->where('relationship', 'Sibling')->get();
        $iscollege = apceducation::where('casecode', $casecode)->exists();

        if ($iscollege) {
            $form = applicationforms::where('formname', 'College');
        } else {
            if ($applicant->educelemhs->schoollevel == 'Elementary') {
                $form = applicationforms::where('formname', 'College')->first();
            } else {
                $form = applicationforms::where('formname', 'High School')->first();
            }
        }

        return view('applicant.applicant-portal', compact('applicant', 'father', 'mother', 'siblings', 'iscollege', 'form'));
    }

    public function showlogin()
    {
        return view('applicant.login');
    }

    public function login(Request $request)
    {
        if (Auth::guard('applicant')->attempt($request->only('casecode', 'password'))) {
            $user = Auth::guard('applicant')->user();

            if ($user->accountstatus === 'Active') {
                $request->session()->regenerate();
                return redirect()->route('applicantportal', $user->casecode);
            } else {
                return redirect()->back()->with('error', 'Your account is suspended. If this was an error, please contact us at inquiriescholartrack@gmail.com for assistance.');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid case code or password.');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('applicant')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login-applicant');
    }
}
