<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\staccount;

class StaffController extends Controller
{
    public function showAccountSW()
    {
        return view('staff.accountsw');
    }

    public function showAccountSA()
    {
        return view('staff.accountsa');
    }

    public function showApplicants()
    {
        return view('staff.applicants');
    }

    public function showApplicationForms()
    {
        return view('staff.applicationforms');
    }

    public function showCSClosedEvents()
    {
        return view('staff.closedevents');
    }

    public function showAttendanceSystem()
    {
        return view('staff.hcattendancesystem');
    }

    public function showHome()
    {
        return view('staff.home');
    }

    public function showScholarsCollege()
    {
        return view('staff.listcollege');
    }

    public function showScholarsElem()
    {
        return view('staff.listelementary');
    }

    public function showScholarsHS()
    {
        return view('staff.listhighschool');
    }

    public function showLogin()
    {
        return view('staff.login');
    }

    public function showLTE()
    {
        return view('staff.lte');
    }

    public function showCommunityService()
    {
        return view('staff.managecs');
    }

    public function showHumanitiesClass()
    {
        return view('staff.managehc');
    }

    public function showCSOpenEvents()
    {
        return view('staff.openevents');
    }

    public function showPenalty()
    {
        return view('staff.penalty');
    }

    public function showQualiCollege()
    {
        return view('staff.qualificationcollege');
    }

    public function showQualiElem()
    {
        return view('staff.qualificationelem');
    }

    public function showQualiJHS()
    {
        return view('staff.qualificationjhs');
    }

    public function showQualiSHS()
    {
        return view('staff.qualificationshs');
    }

    public function showAllowanceRegular()
    {
        return view('staff.regularallowance');
    }

    public function showRenewal()
    {
        return view('staff.renewal');
    }

    public function showRenewalCollege()
    {
        return view('staff.renewcollege');
    }

    public function showRenewalElem()
    {
        return view('staff.renewelementary');
    }

    public function showRenewalHS()
    {
        return view('staff.renewhighschool');
    }

    public function showScholars()
    {
        return view('staff.scholars');
    }

    public function showAllowanceSpecial()
    {
        return view('staff.specialallowance');
    }

    public function showDashboard()
    {
        return view('staff.admdashboard');
    }

    public function showUsersScholar()
    {
        return view('staff.admscholars');
    }

    public function showUserApplicants()
    {
        return view('staff.admapplicants');
    }

    public function showUserStaff()
    {
        // Retrieve all staff accounts
        $staffAccounts = Staccount::all();

        // Pass the staff accounts to the view
        return view('staff.admstaff', compact('staffAccounts'));
    }

    // Method to activate a user
    public function activateUser($id)
    {
        $user = Staccount::findOrFail($id);
        $user->status = 'Active';
        $user->save();

        return redirect()->back()->with('success', 'User activated successfully.');
    }

    // Method to deactivate a user
    public function deactivateUser($id)
    {
        $user = Staccount::findOrFail($id);
        $user->status = 'Inactive';
        $user->save();

        return redirect()->back()->with('success', 'User deactivated successfully.');
    }

    public function showUserInfo($id)
    {
        // Retrieve the user account by ID
        $user = Staccount::findOrFail($id);

        // Pass the user info to the view
        return view('staff.admuserinfo', compact('user'));
    }
}
