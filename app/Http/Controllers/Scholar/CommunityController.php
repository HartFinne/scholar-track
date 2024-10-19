<?php

namespace App\Http\Controllers\Scholar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\communityservice;
use App\Models\csattendance;
use App\Models\csregistration;
use App\Models\User;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommunityController extends Controller
{
    // cs activies page to show
    public function showCSActivities()
    {
        $user = Auth::user(); // Get the authenticated user
        $caseCode = $user->caseCode; // Access the user's caseCode

        // Step 1: Retrieve all activities that are in the future or today
        $activities = communityservice::where('eventdate', '>=', now()->toDateString())->get();
        // dd($activities);

        // Step 2: Get the registration status for each activity
        $registrations = csregistration::where('caseCode', $caseCode)
            ->get(['csid', 'registatus'])
            ->keyBy('csid')
            ->toArray(); // Get all registered activities with status

        // dd($registrations);

        // Step 3: Filter out activities that the user has already submitted attendance for
        $attendedActivities = csattendance::where('caseCode', $caseCode)
            ->pluck('csid')
            ->toArray(); // Get the CSIDs of activities the user has attended

        // dd($attendedActivities);

        // Step 4: Filter out activities from the collection if the user has already attended them
        $filteredActivities = $activities->filter(function ($activity) use ($attendedActivities) {
            return !in_array($activity->csid, $attendedActivities);
        });

        // dd($filteredActivities);

        // Step 5: Pass the filtered activities and registrations to the view
        return view('scholar.csactivities', compact('filteredActivities', 'registrations'));
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

        // Calculate total hours spent by the user
        $totalHoursSpent = csattendance::where('caseCode', $caseCode)
            ->sum('hoursspent');

        // Assuming the total required hours for community service is 8 hours (adjust as needed)
        $totalRequiredHours = 8;
        $remainingHours = max($totalRequiredHours - $totalHoursSpent, 0);

        // Pass the activity, registration status, and remaining hours to the view
        return view('scholar.csdetails', compact('activity', 'isRegistered', 'remainingHours'));
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

        // Fetch the hours spent per activity
        $hoursPerActivity = csattendance::where('caseCode', $caseCode)
            ->join('communityservice', 'csattendance.csid', '=', 'communityservice.csid')
            ->select('communityservice.title', DB::raw('SUM(csattendance.hoursspent) as total_hours'))
            ->groupBy('csattendance.csid', 'communityservice.title')
            ->get();

        // Fetch the hours completed per month with month names and month numbers
        $hoursPerMonth = csattendance::where('caseCode', $caseCode)
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%M") as month'),
                DB::raw('SUM(hoursspent) as total_hours'),
                DB::raw('MONTH(created_at) as month_number')
            )
            ->groupBy('month_number', 'month')
            ->orderBy('month_number', 'asc')
            ->get();

        // Fetch total and remaining hours as before
        $totalHoursSpent = $hoursPerActivity->sum('total_hours');
        $totalRequiredHours = 8; // Example value, adjust as needed
        $remainingHours = max($totalRequiredHours - $totalHoursSpent, 0);

        // Pass the data to the view
        return view('scholar.csdashboard', compact('registrations', 'totalHoursSpent', 'remainingHours', 'hoursPerActivity', 'hoursPerMonth'));
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


    // show cs attendance
    public function showCSAttendance()
    {
        $user = Auth::user(); // Get the authenticated user
        $caseCode = $user->caseCode; // Get user's caseCode

        // Fetch the total attendance (number of entries in the attendance table)
        $totalAttendance = csattendance::where('caseCode', $caseCode)->count();

        // Fetch the total tardiness (assuming there's a field indicating tardiness duration)
        $totalTardiness = csattendance::where('caseCode', $caseCode)
            ->where('csastatus', 'Late')
            ->count();

        // Fetch the total absences (assuming absence means no attendance record for an event the user was registered for)
        $totalAbsences = csregistration::where('caseCode', $caseCode)
            ->where('registatus', 'ABSENT')
            ->whereNotIn('csid', csattendance::where('caseCode', $caseCode)->pluck('csid'))
            ->count();

        // Fetch the attendance details joined with the community service information
        $attendances = csattendance::where('caseCode', $caseCode)
            ->join('communityservice', 'csattendance.csid', '=', 'communityservice.csid')
            ->select(
                'csattendance.*',
                'communityservice.title as activity_title',
                'communityservice.eventloc as location',
                'communityservice.eventdate as date',
                'communityservice.calltime as time', // Use calltime as the event time
                'communityservice.facilitator'
            )
            ->get();

        // Pass the data to the view
        return view('scholar.csattendance', compact('totalAttendance', 'totalTardiness', 'totalAbsences', 'attendances'));
    }


    // show the cs form
    public function showCSForm()
    {
        $data = User::with(['basicInfo', 'education', 'scholarshipinfo'])
            ->where('id', Auth::id())
            ->first();

        // Get only registered activities
        $csRecord = communityservice::join('csregistration', 'communityservice.csid', '=', 'csregistration.csid')
            ->where('csregistration.registatus', 'Going')
            ->select('communityservice.*')
            ->get();

        return view('scholar.csform', compact('data', 'csRecord'));
    }

    // store the cs form 
    public function storeCSForm(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user instance
        $caseCode = $user->caseCode; // Access the caseCode from the user instance

        $validatedData = $request->validate([
            'csid' => 'required|integer', // Integer representing the activity ID
            'timeIn' => 'required|date_format:H:i', // Time input should match the format, e.g., "14:30"
            'timeOut' => 'required|date_format:H:i', // Time input should match the format, e.g., "15:30"
            'attendanceStatus' => 'required|string|in:Present,Late,Left the Activity Early', // Validate against allowed statuses
            'hrSpent' => 'required|integer|min:0', // Integer value for hours spent, minimum 0
            'proofImg' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation for proof of attendance, 2MB max size
        ]);

        // Fetch the community service record for the given csid
        $communityService = Communityservice::where('csid', $validatedData['csid'])->first();

        // Convert the event date and today's date to the Philippines time zone
        $eventDate = Carbon::parse($communityService->eventdate, 'Asia/Manila')->toDateString();
        $todayDate = Carbon::now('Asia/Manila')->toDateString();

        // Log the dates for debugging
        Log::info('Event Date: ' . $eventDate);
        Log::info('Today Date: ' . $todayDate);

        // Check if the event date matches today's date
        if ($eventDate != $todayDate) {
            return back()->withErrors(['failure' => 'You can only fill out the attendance form on the event date.']);
        }

        // Append seconds to the time to match database format
        $validatedData['timeIn'] .= ':00';
        $validatedData['timeOut'] .= ':00';

        // Calculate tardiness
        $eventStartTime = Carbon::createFromFormat('H:i:s', $communityService->starttime, 'Asia/Manila');
        $userTimeIn = Carbon::createFromFormat('H:i:s', $validatedData['timeIn'], 'Asia/Manila');
        $tardinessDuration = 0; // Default to 0 if not late

        // If the user's time in is after the event start time, calculate the difference in minutes
        if ($userTimeIn->gt($eventStartTime)) {
            $tardinessDuration = $eventStartTime->diffInMinutes($userTimeIn);
        }

        try {
            // Save the validated data
            $attendance = new csattendance(); // Assuming you have an Attendance model
            $attendance->csid = $validatedData['csid'];
            $attendance->caseCode = $caseCode;
            $attendance->timein = $validatedData['timeIn'];
            $attendance->timeout = $validatedData['timeOut'];
            $attendance->csastatus = $validatedData['attendanceStatus'];
            $attendance->hoursspent = $validatedData['hrSpent'];
            $attendance->tardinessduration = $tardinessDuration; // Store tardiness duration in minutes

            // Handle the file upload
            if ($request->hasFile('proofImg')) {
                $file = $request->file('proofImg');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('attendance_proof', $filename, 'public');
                $attendance->attendanceproof = $path;
            }

            $attendance->save();

            // Update the registatus using Eloquent
            $csRegistration = Csregistration::where('csid', $validatedData['csid'])
                ->where('caseCode', $caseCode)
                ->first();

            if ($csRegistration) {
                $csRegistration->registatus = 'COMPLETED';
                $csRegistration->save();
            }

            // Return success response
            return back()->with('success', 'Attendance recorded successfully.');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Attendance save failed: ' . $e->getMessage());

            // Return error response
            return back()->withErrors(['failure' => 'An error occurred while saving the attendance. Please try again later.']);
        }
    }
}
