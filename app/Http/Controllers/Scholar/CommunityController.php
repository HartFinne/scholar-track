<?php

namespace App\Http\Controllers\Scholar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\communityservice;
use App\Models\csregistration;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    // cs activies page to show
    public function showCSActivities()
    {
        $user = Auth::user(); // Get the authenticated user
        $caseCode = $user->caseCode; // Access the user's caseCode

        // Step 1: Retrieve all activities
        $activities = communityservice::all();

        // Step 2: Get the registration status for each activity
        $registrations = csregistration::where('caseCode', $caseCode)
            ->get(['csid', 'registatus'])
            ->keyBy('csid')
            ->toArray(); // Get all registered activities with status

        // Step 3: Pass the activities and registrations to the view
        return view('scholar.csactivities', compact('activities', 'registrations'));
    }


    // cs details
    public function showCSDetails($csid)
    {
        $user = Auth::user();
        $caseCode = $user->caseCode;

        // Retrieve the activity
        $activity = communityservice::findOrFail($csid);

        // Check if the user is already registered for this activity
        $isRegistered = csregistration::where('csid', $csid)
            ->where('caseCode', $caseCode)
            ->exists();

        // Pass the activity and registration status to the view
        return view('scholar.csdetails', compact('activity', 'isRegistered'));
    }

    // cs details register
    public function storeCSRegistration(Request $request, $csid)
    {
        $user = Auth::user(); // Get the authenticated user
        $caseCode = $user->caseCode;

        // Handle the registration logic here
        $registration = new csregistration();
        $registration->csid = $csid;
        $registration->caseCode = $caseCode;
        $registration->registatus = 'GOING';
        $registration->save();

        // Update the slot number and volunteers number
        $activity = communityservice::findOrFail($csid);
        $activity->slotnum -= 1; // Reduce slot number
        $activity->volunteersnum += 1; // Increase volunteers number
        $activity->save();

        // Flash a success message to the session to trigger the dialog
        return redirect()->route('csdetails', ['csid' => $csid])->with('success', 'Registration successful');
    }


    // show cs dashboardpage
    public function showCSDashboard()
    {
        $user = Auth::user(); // Get the authenticated user
        $caseCode = $user->caseCode; // Get user's caseCode (or other identifier)

        // Fetch the activities the user is registered for, join with communityservice table to get activity details
        $registrations = csregistration::where('caseCode', $caseCode)
            ->join('communityservice', 'csregistration.csid', '=', 'communityservice.csid')
            ->select('communityservice.*', 'csregistration.created_at as registration_date', 'csregistration.registatus')
            ->get();

        // Pass the registrations to the view
        return view('scholar.csdashboard', compact('registrations'));
    }

    // cancel registration
    public function cancelRegistration($csid)
    {
        $user = Auth::user(); // Get the authenticated user
        $caseCode = $user->caseCode;

        // Find the registration record for the given user and activity (csid)
        $registration = csregistration::where('caseCode', $caseCode)
            ->where('csid', $csid)
            ->first();

        if ($registration) {
            // Find the related activity (community service) using the csid
            $activity = communityservice::where('csid', $csid)->first();

            if ($activity) {
                // Increase the slotnum by 1
                $activity->slotnum += 1;
                $activity->volunteersnum -= 1;

                // Save the updated activity
                $activity->save();
            }

            // Update the registration status to 'Cancelled' instead of deleting
            $registration->registatus = 'Cancelled';
            $registration->save(); // Save the updated registration

            return redirect()->route('csdashboard')->with('success', 'Your registration has been cancelled. Thank you.');
        }

        return redirect()->route('csdashboard')->with('error', 'Unable to cancel registration.');
    }
}
