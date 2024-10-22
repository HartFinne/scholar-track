<?php

namespace App\Http\Controllers\Scholar;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\csattendance;
use App\Models\User;
use App\Models\ScEducation;
use App\Models\penalty;
use App\Models\grades;
use App\Models\hcattendance;
use App\Models\humanitiesclass;

use App\Models\lte;

class ScholarController extends Controller
{

    //for sms or email
    public function updateNotificationPreference(Request $request)
    {
        $request->validate([
            'notification_preference' => ['required', 'in:sms,email'],
        ]);

        // Check if the user is authenticated
        $user = Auth::user(); // Ensure this returns an authenticated user instance
        if ($user) {
            // Update the notification_preference using the Query Builder
            DB::table('users')
                ->where('id', $user->id)
                ->update(['notification_preference' => $request->notification_preference]);

            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }


    // for manage profile
    public function showProfile()
    {
        $data = User::with(['basicInfo', 'education', 'addressInfo', 'clothingSize', 'scholarshipinfo'])
            ->where('id', Auth::id())
            ->first();

        return view('scholar.scholarship.manageprofile', compact('data'));
    }

    // for updating the profile
    public function updateProfile(Request $request)
    {
        $validatedData = $request->validate([
            'scEmail' => 'required|email|max:255',
            'scPhoneNum' => 'required|regex:/^[0-9]{12}$/',
            'scResidential' => 'required|string|max:255',
            'scGuardianName' => 'required|string|max:255',
            'scRelationToGuardian' => 'required|string|max:255',
            'scGuardianEmailAddress' => 'required|email|max:255',
            'scGuardianPhoneNumber' => 'required|regex:/^[0-9]{12}$/',
        ]);

        try {
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
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Profile update failed: ' . $e->getMessage());

            // Redirect with failure message
            return redirect()->route('manageprofile')->with('failure', 'Failed to update profile: ' . $e->getMessage());
        }
    }


    // for viewing page the change password
    public function changePassword()
    {
        return view('scholar.scholarship.changepassword');
    }

    // for the show of basic info in scholarship overview
    public function showScholarshipOverview()
    {

        $user = User::with(['basicInfo', 'education'])
            ->where('id', Auth::id())
            ->first();

        // Fetch the penalties associated with the user
        $penalty = penalty::where('caseCode', $user->caseCode)->get();

        // Fetch grades associated with the user's education
        // Fetch academic performance data using a join
        $academicData = ScEducation::join('grades', 'sc_education.eid', '=', 'grades.eid')
            ->selectRaw("CONCAT(sc_education.scAcademicYear, ' - ', grades.SemesterQuarter) AS period, grades.GWA")
            ->where('sc_education.caseCode', $user->caseCode) // Filter by user's caseCode
            ->orderBy('sc_education.scAcademicYear', 'asc')
            ->orderBy('grades.SemesterQuarter', 'asc')
            ->get();

        // Prepare data for the chart
        $chartData = [
            'labels' => $academicData->pluck('period')->toArray(),
            'grades' => $academicData->pluck('GWA')->toArray(), // Make sure to use the correct column name
        ];

        // Fetch the community service activities and calculate hours
        $communityServiceData = csattendance::where('caseCode', $user->caseCode)
            ->join('communityservice', 'csattendance.csid', '=', 'communityservice.csid')
            ->select(DB::raw('SUM(csattendance.hoursspent) as total_hours'))
            ->first();

        // Set the total required hours (example value)
        $totalRequiredHours = 8;
        $completedHours = $communityServiceData->total_hours ?? 0;
        $remainingHours = max($totalRequiredHours - $completedHours, 0);

        // Pass the community service data to the view
        $communityServiceChart = [
            'completed' => $completedHours,
            'remaining' => $remainingHours,
        ];

        // If the user is authenticated, show the overview page
        return view('scholar.scholarship.overview', compact('user', 'penalty', 'chartData', 'communityServiceChart'));
    }


    public function showGradeSubmission()
    {
        // Retrieve the currently authenticated user's caseCode
        $user = Auth::user(); // Get the authenticated user
        $caseCode = $user->caseCode; // Access the caseCode property

        // Find the corresponding sc_education entry based on caseCode
        $scEducation = ScEducation::where('caseCode', $caseCode)->firstOrFail();

        // Fetch grades associated with the education entry
        $grades = grades::where('eid', $scEducation->eid)->get();

        // Retrieve the academic year from the scEducation record
        $academicYear = $scEducation->scAcademicYear;

        // Pass the grades and academic year to the view
        return view('scholar/scholarship.gradesub', compact('grades', 'academicYear'));
    }

    public function storeGradeSubmission(Request $request)
    {
        // Validate the form data
        $request->validate([
            'semester' => ['required'],
            'gwa' => [
                'required',
                'numeric', // Ensures it is a number (int or float)
                'regex:/^(0|[1-4](\.\d{1,2})?|5(\.0{1,2})?)$/', // Accepts 0, 1, 2, 3, 4, 5 or 1.00, 2.50, etc.
                'min:1',  // Minimum value of 1
                'max:5'   // Maximum value of 5
            ],
            'gradeImage' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:2048'] // Validate file: jpeg/png/jpg/pdf and max size of 2MB
        ]);

        try {
            // Retrieve the currently authenticated user's caseCode
            $user = Auth::user(); // Get the authenticated user
            $caseCode = $user->caseCode; // Access the caseCode property

            // Find the corresponding sc_education entry based on caseCode
            $scEducation = ScEducation::where('caseCode', $caseCode)->firstOrFail();
            Log::info('scEducation ID: ' . $scEducation->eid);

            // Handle file upload
            if ($request->hasFile('gradeImage')) {
                $file = $request->file('gradeImage');

                // Create a custom file name using caseCode and last name
                $fileName = $user->caseCode . '_' . $user->basicInfo->scLastname . '_' . time() . '.' . $file->getClientOriginalExtension();

                // Store the file in the specified directory
                $filePath = $file->storeAs('uploads/grade_reports', $fileName, 'public');
            } else {
                return redirect()->back()->withErrors(['gradeImage' => 'File upload failed. Please try again.'])->withInput();
            }

            // Save the grade entry and link it to the educationID
            grades::create([
                'eid' => $scEducation->eid, // Link the grade to the education entry
                'SemesterQuarter' => $request->semester,
                'GWA' => $request->gwa,
                'ReportCard' => $filePath, // Store the file path
                'GradeStatus' => 'Pending' // Default status or modify based on your logic
            ]);

            // Redirect on success and pass the grades data
            return redirect()->route('gradesub')->with('success', 'Grade submission uploaded successfully!');
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Grade submission failed: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors(['error' => 'Something went wrong. Please try again later.'])->withInput();
        }
    }
    public function showGradeInfo($id)
    {
        // Find the grade using the correct primary key
        $grade = grades::findOrFail($id);
        // Fetch the associated education entry to get the academic year
        $academicYear = ScEducation::findOrFail($grade->eid); // Assuming educationID is stored in ScGrade

        // Pass the grade data and academic year to the view
        return view('scholar.scholarship.gradesinfo', compact('grade', 'academicYear'));
    }

    // HUMANITIES CLASS
    public function showHumanitiesClass()
    {
        $scholar = Auth::user();

        $totalattendance = hcattendance::where('caseCode', $scholar->caseCode)->count();

        $totaltardiness = hcattendance::where('caseCode', $scholar->caseCode)->sum('tardinessduration');

        $totalabsences = hcattendance::where('caseCode', $scholar->caseCode)
            ->where('hcastatus', 'Absent')
            ->count();

        $classes = HumanitiesClass::with(['attendances' => function ($query) use ($scholar) {
            $query->where('caseCode', $scholar->caseCode);
        }])->get();

        return view('scholar.scholarship.schumanities', compact('classes', 'totalattendance', 'totaltardiness', 'totalabsences'));
    }

    public function showLTE()
    {
        $scholar = Auth::user();
        $noresponseletters = lte::with(['hcattendance', 'csattendance'])
            ->where('caseCode', $scholar->caseCode)->where('ltestatus', "No Response")->get();
        $letters = lte::where('caseCode', $scholar->caseCode)
            ->whereIn('ltestatus', ['To Review', 'Excused', 'Unexcused'])
            ->get();

        return view('scholar.scholarship.sclte', compact('noresponseletters', 'letters'));
    }

    public function showLTEinfo($lid)
    {
        $letter = lte::where('lid', $lid)->first();

        $scholar = User::with(['basicInfo', 'education'])
            ->where('id', Auth::id())
            ->first();

        if ($letter->eventtype == 'Humanities Class') {
            $violation = hcattendance::where('hcaid', $letter->conditionid)->first();
            $eventinfo = humanitiesclass::where('hcid', $violation->hcid)->first();

            if ($violation->hcastatus == "Absent") {
                return view('scholar.scholarship.lteinfo-absent', compact('letter', 'scholar', 'eventinfo'));
            } elseif ($violation->hcastatus == "Late") {
                return view('scholar.scholarship.lteinfo-late', compact('letter', 'scholar', 'eventinfo'));
            } elseif ($violation->hcastatus == "Left Early") {
                return view('scholar.scholarship.lteinfo-leftearly', compact('letter', 'scholar', 'eventinfo'));
            }
        }
        // elseif ($letter->eventtype == 'Community Service') {
        //     $violation = csattendance::where('csaid', $letter->conditionid);
        // }
    }
}
