<?php

namespace App\Http\Controllers\Scholar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\communityservice;
use App\Models\csattendance;
use App\Models\csregistration;
use App\Models\lte;
use App\Models\staccount;
use App\Models\User;
use App\Notifications\LteAnnouncementCreated;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class CommunityController extends Controller
{
    // cs activies page to show
    // public function showCSActivities()
    // {
    //     $user = Auth::user(); // Get the authenticated user
    //     $caseCode = $user->caseCode; // Access the user's caseCode

    //     // Step 1: Retrieve all activities that are in the future or today
    //     $activities = communityservice::where('eventdate', '>=', now()->toDateString())->get();
    //     // dd($activities);

    //     // Step 2: Get the registration status for each activity
    //     $registrations = csregistration::where('caseCode', $caseCode)
    //         ->get(['csid', 'registatus'])
    //         ->keyBy('csid')
    //         ->toArray(); // Get all registered activities with status

    //     // dd($registrations);

    //     // Step 3: Filter out activities that the user has already submitted attendance for
    //     $attendedActivities = csattendance::where('caseCode', $caseCode)
    //         ->pluck('csid')
    //         ->toArray(); // Get the CSIDs of activities the user has attended

    //     // dd($attendedActivities);

    //     // Step 4: Filter out activities from the collection if the user has already attended them
    //     $filteredActivities = $activities->filter(function ($activity) use ($attendedActivities) {
    //         return !in_array($activity->csid, $attendedActivities);
    //     });

    //     // dd($filteredActivities);

    //     // Step 5: Pass the filtered activities and registrations to the view
    //     return view('scholar.communityservice.csactivities', compact('filteredActivities', 'registrations'));
    // }

    public function showCSActivities(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user
        $caseCode = $user->caseCode; // Access the user's caseCode

        // Step 1: Get the search query and area from the request
        $search = $request->input('search');
        $area = $request->input('area');

        // Step 2: Retrieve all activities that are in the future or today
        $activities = communityservice::where('eventdate', '>=', now()->toDateString());

        // Step 3: Filter activities based on search query if provided
        if ($search) {
            $activities->where('title', 'like', '%' . $search . '%')
                ->orWhere('eventloc', 'like', '%' . $search . '%')
                ->orWhere('facilitator', 'like', '%' . $search . '%')
                ->orWhere('meetingplace', 'like', '%' . $search . '%');
        }

        $activities = $activities->get();

        // Step 4: Get the registration status for each activity
        $registrations = csregistration::where('caseCode', $caseCode)
            ->get(['csid', 'registatus'])
            ->keyBy('csid')
            ->toArray(); // Get all registered activities with status

        // Step 5: Filter out activities that the user has already submitted attendance for
        $attendedActivities = csattendance::where('caseCode', $caseCode)
            ->pluck('csid')
            ->toArray(); // Get the CSIDs of activities the user has attended

        // Step 6: Filter out activities from the collection if the user has already attended them
        $filteredActivities = $activities->filter(function ($activity) use ($attendedActivities) {
            return !in_array($activity->csid, $attendedActivities);
        });

        // Step 7: Pass the filtered activities and registrations to the view
        return view('scholar.communityservice.csactivities', compact('filteredActivities', 'registrations', 'search', 'area'));
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
        return view('scholar.communityservice.csdetails', compact('activity', 'isRegistered', 'remainingHours'));
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
        $caseCode = $user->caseCode; // Get user's caseCode

        // Get today's date in the Philippines time zone
        $today = Carbon::now('Asia/Manila')->toDateString();

        // Fetch the activities the user is registered for, join with communityservice table to get activity details
        $registrations = csregistration::where('caseCode', $caseCode)
            ->join('communityservice', 'csregistration.csid', '=', 'communityservice.csid')
            ->select('communityservice.*', 'csregistration.created_at as registration_date', 'csregistration.registatus', 'csregistration.csid')
            ->get();

        // Loop through the registrations to check if any "GOING" status needs to be updated
        // foreach ($registrations as $registration) {
        //     // Check if the event date is in the past and the status is "GOING"
        //     if ($registration->registatus === 'GOING' && Carbon::parse($registration->eventdate)->toDateString() < $today) {
        //         // Check if there is no attendance record for this activity
        //         $attendanceExists = csattendance::where('caseCode', $caseCode)
        //             ->where('csid', $registration->csid)
        //             ->exists();

        //         if (!$attendanceExists) {
        //             // Update the status to "ABSENT"
        //             csregistration::where('caseCode', $caseCode)
        //                 ->where('csid', $registration->csid)
        //                 ->update(['registatus' => 'ABSENT']);
        //         }
        //     }
        // }

        // Refresh the registrations after updating statuses
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
        return view(
            'scholar.communityservice.csdashboard',
            compact(
                'registrations',
                'totalHoursSpent',
                'remainingHours',
                'hoursPerActivity',
                'hoursPerMonth'
            )
        );
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

            // Retrieve the staff member using the staffID from the community service event
            $staff = staccount::find($activity->staffID);

            if ($staff) {
                // Create an LTE record using the staff's details
                $lte = lte::create([
                    'caseCode' => $caseCode,
                    'violation' => 'Cancelled',
                    'conditionid' => $csid, // Use the CS activity ID as the conditionid
                    'eventtype' => 'Community Service', // Specify the event type
                    'dateissued' => now(),
                    'deadline' => now()->addDays(3), // Set a deadline for response (3 days in this example)
                    'datesubmitted' => null,
                    'reason' => null,
                    'explanation' => null,
                    'proof' => null,
                    'ltestatus' => 'No Response', // Default status
                    'workername' => strtoupper($staff->name) . ", RSW", // Using the staff's name
                ]);

                $api_key = env('MOVIDER_API_KEY');
                $api_secret = env('MOVIDER_API_SECRET');

                $user = User::where('caseCode', $caseCode)->first();

                // Initialize the Guzzle client
                $client = new \GuzzleHttp\Client();

                // Track failed SMS and failed email notifications
                $failedSMS = [];
                $failedEmail = [];
                $message = 'You cancelled a cs activity';

                if ($user->notification_preference === 'sms') {
                    // Send the SMS using the Movider API
                    try {
                        $response = $client->post('https://api.movider.co/v1/sms', [
                            'form_params' => [
                                'api_key' => $api_key,
                                'api_secret' => $api_secret,
                                'to' => $user->scPhoneNum,
                                'text' => $message,
                            ],
                        ]);

                        $responseBody = $response->getBody()->getContents();
                        $decodedResponse = json_decode($responseBody, true);

                        Log::info('Movider SMS Response', ['response' => $decodedResponse]);
                        // Check if phone_number_list is an array and not empty
                        if (!isset($decodedResponse['phone_number_list']) || !is_array($decodedResponse['phone_number_list']) || count($decodedResponse['phone_number_list']) == 0) {
                            $failedSMS[] = $user->scPhoneNum; // Track failed SMS
                        }
                    } catch (\Exception $e) {
                        // Catch and handle any exception
                        $failedSMS[] = $user->scPhoneNum;
                        Log::info('Movider SMS Response', ['response' => $failedSMS]);
                    }
                } else {
                    // Send an email notification
                    try {
                        $user->notify(new LteAnnouncementCreated($lte));
                    } catch (\Exception $e) {
                        // If email notification failed, add to failed list
                        $failedEmail[] = $user->email;
                    }
                }

                // Update the registration status to 'Cancelled' instead of deleting
                $registration->registatus = 'Cancelled';
                $registration->save(); // Save the updated registration

                return redirect()->route('csdashboard')->with('success', 'Your registration has been cancelled. Thank you.');
            }

            return redirect()->route('csdashboard')->with('error', 'Unable to cancel registration.');
        }
    }


    // show cs attendance
    // public function showCSAttendance()
    // {
    //     $user = Auth::user(); // Get the authenticated user
    //     $caseCode = $user->caseCode; // Get user's caseCode

    //     // Fetch the total attendance (number of entries in the attendance table)
    //     $totalAttendance = csattendance::where('caseCode', $caseCode)->count();

    //     // Fetch the total tardiness (assuming there's a field indicating tardiness duration)
    //     $totalTardiness = csattendance::where('caseCode', $caseCode)
    //         ->where('csastatus', 'Late')
    //         ->count();

    //     // Fetch the total absences (assuming absence means no attendance record for an event the user was registered for)
    //     $totalAbsences = csregistration::where('caseCode', $caseCode)
    //         ->where('registatus', 'ABSENT')
    //         ->count();

    //     // Fetch the attendance details joined with the community service information
    //     $attendances = csattendance::where('caseCode', $caseCode)
    //         ->join('communityservice', 'csattendance.csid', '=', 'communityservice.csid')
    //         ->select(
    //             'csattendance.*',
    //             'communityservice.title as activity_title',
    //             'communityservice.eventloc as location',
    //             'communityservice.eventdate as date',
    //             'communityservice.calltime as time', // Use calltime as the event time
    //             'communityservice.facilitator'
    //         )
    //         ->get();

    //     // Pass the data to the view
    //     return view('scholar.communityservice.csattendance', compact('totalAttendance', 'totalTardiness', 'totalAbsences', 'attendances'));
    // }

    public function showCSAttendance(Request $request)
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
            ->count();

        // Get the selected attendance status filter from the request
        $attendanceStatus = $request->input('attendance_status', 'all');

        // Fetch the attendance details joined with the community service information
        $attendanceQuery = csattendance::where('caseCode', $caseCode)
            ->join('communityservice', 'csattendance.csid', '=', 'communityservice.csid')
            ->select(
                'csattendance.*',
                'communityservice.title as activity_title',
                'communityservice.eventloc as location',
                'communityservice.eventdate as date',
                'communityservice.calltime as time', // Use calltime as the event time
                'communityservice.facilitator'
            );

        // Apply filter based on the attendance status if it is not "all"
        if ($attendanceStatus !== 'all') {
            $attendanceQuery->where('csattendance.csastatus', $attendanceStatus);
        }

        // Get the filtered results
        $attendances = $attendanceQuery->get();

        // Pass the data to the view
        return view('scholar.communityservice.csattendance', compact('totalAttendance', 'totalTardiness', 'totalAbsences', 'attendances', 'attendanceStatus'));
    }


    // show the cs form
    public function showCSForm()
    {
        $data = User::with(['basicInfo', 'education', 'scholarshipinfo'])
            ->where('id', Auth::id())
            ->first();

        // Assuming 'caseCode' is an attribute of the authenticated user
        $caseCode = $data->caseCode; // Retrieve caseCode from the user's data

        // Get only registered activities for the authenticated user
        $csRecord = communityservice::join('csregistration', 'communityservice.csid', '=', 'csregistration.csid')
            ->where('csregistration.registatus', 'Going')
            ->where('csregistration.caseCode', $caseCode) // Use the retrieved caseCode
            ->select('communityservice.*')
            ->get();

        return view('scholar.communityservice.csform', compact('data', 'csRecord'));
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

            // Retrieve the currently authenticated user's details
            $user = Auth::user();

            // Construct the directory path based on the user's full name and case code
            $directoryPath = 'scholars/'
                . $user->basicInfo->scLastname . ', '
                . $user->basicInfo->scFirstname . ' '
                . $user->basicInfo->scMiddlename . '_'
                . $user->caseCode . '/cs_attendance/' . $communityService->title;

            // Ensure the directory exists
            if (!Storage::exists('public/' . $directoryPath)) {
                Storage::makeDirectory('public/' . $directoryPath);
            }

            // Store the explanation file in the specified directory
            $attendancePath = $request->file('proofImg')->storeAs(
                'public/' . $directoryPath,
                'proof_image' . $request->file('proofImg')->getClientOriginalExtension()
            );

            $attendance->attendanceproof = $attendancePath;

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
