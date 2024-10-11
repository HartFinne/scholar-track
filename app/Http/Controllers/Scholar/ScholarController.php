<?php

namespace App\Http\Controllers\Scholar;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\ScPenalty;

class ScholarController extends Controller
{

    // for manage profile
    public function showProfile()
    {
        $data = User::with(['basicInfo', 'education', 'addressInfo', 'clothingSize'])
            ->where('id', Auth::id())
            ->first();

        return view('scholar.manageprofile', compact('data'));
    }

    // for updating the profile
    public function updateProfile(Request $request)
    {
        $validatedData = $request->validate([
            'scEmail' => 'required|email|max:255',
            'scPhoneNum' => 'required|regex:/^[0-9]{10,11}$/',
            'scResidential' => 'required|string|max:255',
            'scGuardianName' => 'required|string|max:255',
            'scRelationToGuardian' => 'required|string|max:255',
            'scGuardianEmailAddress' => 'required|email|max:255',
            'scGuardianPhoneNumber' => 'required|regex:/^[0-9]{10,11}$/',
        ]);

        // Get authenticated user
        $user = User::with(['basicInfo', 'education', 'addressInfo'])
            ->where('id', Auth::id())
            ->first();

        // Update user's email and phone number
        $user->scEmail = $validatedData['scEmail'];
        $user->scPhoneNum = $validatedData['scPhoneNum'];
        $user->save(); // Save the User model

        // Update address information
        $user->addressInfo->scResidential = $validatedData['scResidential'];
        $user->addressInfo->save(); // Save AddressInfo model

        // Update basic info (Guardian details)
        $user->basicInfo->scGuardianName = $validatedData['scGuardianName'];
        $user->basicInfo->scRelationToGuardian = $validatedData['scRelationToGuardian'];
        $user->basicInfo->scGuardianEmailAddress = $validatedData['scGuardianEmailAddress'];
        $user->basicInfo->scGuardianPhoneNumber = $validatedData['scGuardianPhoneNumber'];
        $user->basicInfo->save(); // Save BasicInfo model

        // Redirect with success message
        return redirect()->route('manageprofile')->with('success', 'Profile updated successfully!');
    }

    // for viewing page the change password
    public function changePassword()
    {
        return view('scholar.changepassword');
    }

    // for the show of basic info in scholarship overview
    public function showScholarshipOverview()
    {

        $user = User::with(['basicInfo', 'education'])
            ->where('id', Auth::id())
            ->first();

        // Fetch the penalties associated with the user
        $penalties = ScPenalty::where('caseCode', $user->caseCode)->get();

        // If the user is authenticated, show the overview page
        return view('scholar.overview', compact('user', 'penalties'));
    }


    public function showGradeSubmission()
    {
        return view('scholar.gradesub');
    }

    public function storeGradeSubmission() {}
}
