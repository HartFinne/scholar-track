<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\staccount;
use App\Models\User;
use App\Models\communityservice;
use App\Models\csregistration;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function showAccountSW()
    {
        if (Auth::guard('staff')->check()) { // Check if the user is authenticated using the staff guard
            return view('staff.accountsw');
        }

        // Redirect the user if not authenticated
        return redirect()->route('login');
    }

    public function showAccountSA()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.accountsa');
        }

        return redirect()->route('login');
    }

    public function showApplicants()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.applicants');
        }

        return redirect()->route('login');
    }

    public function showApplicationForms()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.applicationforms');
        }

        return redirect()->route('login');
    }

    public function showAttendanceSystem()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.hcattendancesystem');
        }

        return redirect()->route('login');
    }

    public function showScholarsCollege()
    {
        if (Auth::guard('staff')->check()) {
            // Retrieve all scholars
            $scholar = User::with(['basicInfo', 'education', 'addressInfo'])->get();

            return view('staff.listcollege', compact('scholar'));
        }

        return redirect()->route('login');
    }

    public function showScholarsElem()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.listelementary');
        }

        return redirect()->route('login');
    }

    public function showScholarsHS()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.listhighschool');
        }

        return redirect()->route('login');
    }

    public function showScholarProfile($id)
    {
        if (Auth::guard('staff')->check()) {
            // Retrieve scholar info
            $data = User::with(['basicInfo', 'education', 'addressInfo'])->findOrFail($id);

            return view('staff.scholarsinfo', compact('data'));
        }

        return redirect()->route('login');
    }

    public function showLogin()
    {
        return view('staff.login');
    }

    public function showLTE()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.lte');
        }

        return redirect()->route('login');
    }

    public function showHumanitiesClass()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.managehc');
        }

        return redirect()->route('login');
    }

    public function showPenalty()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.penalty');
        }

        return redirect()->route('login');
    }

    public function showQualiCollege()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.qualificationcollege');
        }

        return redirect()->route('login');
    }

    public function showQualiElem()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.qualificationelem');
        }

        return redirect()->route('login');
    }

    public function showQualiJHS()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.qualificationjhs');
        }

        return redirect()->route('login');
    }

    public function showQualiSHS()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.qualificationshs');
        }

        return redirect()->route('login');
    }

    public function showAllowanceRegular()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.regularallowance');
        }

        return redirect()->route('login');
    }

    public function showRenewal()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.renewal');
        }

        return redirect()->route('login');
    }

    public function showRenewalCollege()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.renewcollege');
        }

        return redirect()->route('login');
    }

    public function showRenewalElem()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.renewelementary');
        }

        return redirect()->route('login');
    }

    public function showRenewalHS()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.renewhighschool');
        }

        return redirect()->route('login');
    }

    public function showAllowanceSpecial()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.specialallowance');
        }

        return redirect()->route('login');
    }

    public function showScholars()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.scholars');
        }

        return redirect()->route('login');
    }

    public function showUsersScholar()
    {
        if (Auth::guard('staff')->check()) {
            // Retrieve all scholars accounts
            $scholarAccounts = User::all();

            return view('staff.admscholars', compact('scholarAccounts'));
        }

        return redirect()->route('login');
    }

    public function showUserApplicants()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.admapplicants');
        }

        return redirect()->route('login');
    }

    public function showUserStaff()
    {
        if (Auth::guard('staff')->check()) {
            // Retrieve all staff accounts
            $staffAccounts = Staccount::all();

            // Pass the staff accounts to the view
            return view('staff.admstaff', compact('staffAccounts'));
        }

        return redirect()->route('login');
    }

    public function showHome()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.home');
        }

        return redirect()->route('login');
    }

    public function showDashboard()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.admdashboard');
        }

        return redirect()->route('login');
    }

    // Methods to activate and deactivate a user
    public function activateStaff($id)
    {
        $user = Staccount::findOrFail($id);
        $user->status = 'Active';
        $user->save();

        return redirect()->back()->with('success', 'User activated successfully.');
    }

    public function deactivateStaff($id)
    {
        $user = Staccount::findOrFail($id);
        $user->status = 'Inactive';
        $user->save();

        return redirect()->back()->with('success', 'User deactivated successfully.');
    }

    public function activateScholar($id)
    {
        $user = User::findOrFail($id);
        $user->scStatus = 'Active';
        $user->save();

        return redirect()->back()->with('success', 'User activated successfully.');
    }

    public function deactivateScholar($id)
    {
        $user = User::findOrFail($id);
        $user->scStatus = 'Inactive';
        $user->save();

        return redirect()->back()->with('success', 'User deactivated successfully.');
    }

    public function showStaffInfo($id)
    {
        if (Auth::guard('staff')->check()) {
            // Retrieve the user account by ID
            $user = Staccount::findOrFail($id);

            // Pass the user info to the view
            return view('staff.admstaffinfo', compact('user'));
        }

        // Redirect the user if not authenticated
        return redirect()->route('login');
    }

    public function showScholarInfo($id)
    {
        if (Auth::guard('staff')->check()) {
            // Retrieve the user account by ID
            $user = User::findOrFail($id);

            // Pass the user info to the view
            return view('staff.admscholarinfo', compact('user'));
        }

        // Redirect the user if not authenticated
        return redirect()->route('login');
    }

    public function showCommunityService()
    {
        if (Auth::guard('staff')->check()) {
            // Retrieve all cs events
            $events = communityservice::all();
            // Fetch the total number of events
            $totalevents = communityservice::count();

            // Fetch the number of open events
            $openevents = communityservice::where('eventstatus', 'Open')->count();

            // Fetch the number of closed events
            $closedevents = communityservice::where('eventstatus', 'Closed')->count();

            return view('staff.managecs', compact('events', 'totalevents', 'openevents', 'closedevents'));
        }

        return redirect()->route('login');
    }

    public function showCSOpenEvents()
    {
        if (Auth::guard('staff')->check()) {
            $events = communityservice::where('eventstatus', 'Open')->get();
            // Fetch the total number of events
            $totalevents = communityservice::count();

            // Fetch the number of open events
            $openevents = communityservice::where('eventstatus', 'Open')->count();

            // Fetch the number of closed events
            $closedevents = communityservice::where('eventstatus', 'Closed')->count();

            return view('staff.openevents', compact('events', 'totalevents', 'openevents', 'closedevents'));
        }

        return redirect()->route('login');
    }

    public function showCSClosedEvents()
    {
        if (Auth::guard('staff')->check()) {
            // Retrieve only community service events where the eventstatus is 'Open'
            $events = communityservice::where('eventstatus', 'Closed')->get();
            // Fetch the total number of events
            $totalevents = communityservice::count();

            // Fetch the number of open events
            $openevents = communityservice::where('eventstatus', 'Open')->count();

            // Fetch the number of closed events
            $closedevents = communityservice::where('eventstatus', 'Closed')->count();

            return view('staff.closedevents', compact('events', 'totalevents', 'openevents', 'closedevents'));
        }

        return redirect()->route('login');
    }

    public function showcseventinfo($csid)
    {
        if (Auth::guard('staff')->check()) {
            // Retrieve all staff accounts
            $event = communityservice::findOrFail($csid);
            $volunteers = csregistration::where('csid', $csid)->get();
            return view('staff.cseventinfo', compact('event', 'volunteers'));
        }

        return redirect()->route('login');
    }

    public function createcsevent(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'title' => 'required|string|max:255',
                'eventloc' => 'required|string|max:255',
                'eventdate' => 'required|date',
                'meetingplace' => 'required|string|max:255',
                'calltime' => 'required',
                'starttime' => 'required',
                'facilitator' => 'required|string|max:255',
                'slotnum' => 'required|integer|min:1',
            ]);

            // Set default values for volunteers number and event status
            $volunteersnum = 0;
            $eventstatus = 'Open';

            // Create a new event record in the database
            $event = communityservice::create([
                'title' => $request->title,
                'eventloc' => $request->eventloc,
                'eventdate' => $request->eventdate,
                'meetingplace' => $request->meetingplace,
                'calltime' => $request->calltime,
                'starttime' => $request->starttime,
                'facilitator' => $request->facilitator,
                'slotnum' => $request->slotnum,
                'volunteersnum' => $volunteersnum,
                'eventstatus' => $eventstatus
            ]);

            return redirect()->route('communityservice')->with('success', 'Activity created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('communityservice')->with('error', 'Activity creation was unsuccessful. ' . $e->getMessage());
        }
    }

    public function updatecsevent($csid, Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'title' => 'required|string|max:255',
                'eventloc' => 'required|string|max:255',
                'eventdate' => 'required|date',
                'meetingplace' => 'required|string|max:255',
                'calltime' => 'required',
                'starttime' => 'required',
                'facilitator' => 'required|string|max:255',
                'slotnum' => 'required|integer|min:1',
                'eventstatus' => 'required',
            ]);

            // Fetch the event by csid (passed as a parameter)
            $event = communityservice::where('csid', $csid)->first();

            // Check if the event exists
            if (!$event) {
                return redirect()->back()->with('error', 'Event not found.');
            }

            // Update event details in the database
            $event->update([
                'title' => $request->title,
                'eventloc' => $request->eventloc,
                'eventdate' => $request->eventdate,
                'meetingplace' => $request->meetingplace,
                'calltime' => $request->calltime,
                'starttime' => $request->starttime,
                'facilitator' => $request->facilitator,
                'slotnum' => $request->slotnum,
                'volunteersnum' => $event->volunteersnum, // Keep the original value
                'eventstatus' => $request->eventstatus
            ]);

            return redirect()->back()->with('success', 'Successfully updated activity details.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Updating activity details was unsuccessful. ' . $e->getMessage());
        }
    }
}
