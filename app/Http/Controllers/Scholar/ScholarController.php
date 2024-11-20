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
use App\Models\specialallowanceforms;
use App\Models\allowancebook;
use App\Models\allowanceevent;
use App\Models\allowancegraduation;
use App\Models\allowanceproject;
use App\Models\allowancethesis;
use App\Models\allowancetranspo;
use App\Models\allowanceuniform;
use App\Models\Announcement;
use App\Models\applicationforms;
use App\Models\Appointments;
use App\Models\communityservice;
use App\Models\criteria;
use App\Models\csregistration;
use App\Models\institutions;
use App\Models\courses;
use App\Models\scholarshipinfo;
use App\Models\staccount;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Facades\App;
use Svg\Tag\Rect;

class ScholarController extends Controller
{

    // public function showHome()
    // {
    //     $user = Auth::user();
    //     $announcements = Announcement::whereJsonContains('recipients', 'all')
    //         ->orWhereJsonContains('recipients', $user->caseCode)
    //         ->get();
    //     return view('scholar.schome', compact('announcements'));
    // }
    public function showHome(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $filter = $request->input('filter', 'all');

        $announcements = Announcement::where(function ($query) use ($user) {
            $query->whereJsonContains('recipients', 'all')
                ->orWhereJsonContains('recipients', $user->caseCode);
        })
            // Add search functionality if a search term is provided
            ->when($search, function ($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%');
            })
            // Apply filter based on selected filter
            ->when($filter === 'latest', function ($query) {
                return $query->orderBy('created_at', 'desc');
            })

            ->when($filter === 'humanities', function ($query) {
                return $query->where(function ($q) {
                    $q->where('title', 'like', '%humanities%')
                        ->orWhere('description', 'like', '%humanities%');
                });
            })
            ->when($filter === 'community_service', function ($query) {
                return $query->where(function ($q) {
                    $q->where('title', 'like', '%community service%')
                        ->orWhere('description', 'like', '%community service%');
                });
            })
            ->get();

        return view('scholar.schome', compact('announcements'));
    }

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
        try {
            $validatedData = $request->validate([
                'scOccupation' => 'required|string|max:100',
                'scIncome' => 'required|numeric|min:0',
                'scFblink' => 'required|string',
                'scEmail' => 'required|email|max:255',
                'scPhoneNum' => 'required|regex:/^[0-9]{12}$/',
                'scResidential' => 'required|string|max:255',
                'scRegion' => 'required|string|max:50',
                'scCity' => 'required|string|max:50',
                'scBarangay' => 'required|string|max:50',
                'scGuardianName' => 'required|string|max:255',
                'scRelationToGuardian' => 'required|string|max:255',
                'scGuardianEmailAddress' => 'required|email|max:255',
                'scGuardianPhoneNumber' => 'required|regex:/^[0-9]{12}$/',
            ]);
            // Get authenticated user
            $user = User::with(['basicInfo', 'education', 'addressInfo'])
                ->where('id', Auth::id())
                ->first();

            // Update user's email and phone number
            $user->scEmail = $validatedData['scEmail'];
            $user->scPhoneNum = $validatedData['scPhoneNum'];
            $user->save();

            $user->basicInfo->scOccupation = $validatedData['scOccupation'];
            $user->basicInfo->scIncome = $validatedData['scIncome'];
            $user->basicInfo->scFblink = $validatedData['scFblink'];
            $user->basicInfo->save(); // Save AddressInfo model

            // Update address information
            $user->addressInfo->scResidential = $validatedData['scResidential'];
            $user->addressInfo->scRegion = $validatedData['scRegion'];
            $user->addressInfo->scCity = $validatedData['scCity'];
            $user->addressInfo->scBarangay = $validatedData['scBarangay'];
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

    public function showScholarshipOverview(Request $request)
    {
        $user = User::with(['basicInfo', 'education'])
            ->where('id', Auth::id())
            ->first();

        // Retrieve the penalty filter status
        $penaltyStatus = $request->input('penalty_status', 'all');

        // Fetch penalties based on the filter status
        $penalty = penalty::where('caseCode', $user->caseCode)
            ->when($penaltyStatus !== 'all', function ($query) use ($penaltyStatus) {
                return $query->where('remark', $penaltyStatus);
            })
            ->get();

        // Retrieve the renewal filter status
        $renewalStatus = $request->input('renewal_status', 'all');

        // Fetch renewal applications based on the filter status
        $renewal = applicationforms::where('formname', 'Renewal')
            ->when($renewalStatus !== 'all', function ($query) use ($renewalStatus) {
                return $query->where('status', $renewalStatus);
            })
            ->first();

        $academicData = grades::selectRaw("CONCAT(grades.schoolyear, ' - ', grades.SemesterQuarter) AS period, grades.GWA")
            ->where('grades.caseCode', $user->caseCode)
            ->orderBy('grades.schoolyear', 'asc')
            ->orderBy('grades.SemesterQuarter', 'asc')
            ->get();

        $chartData = [
            'labels' => $academicData->pluck('period')->toArray(),
            'grades' => $academicData->pluck('GWA')->toArray(),
        ];

        $communityServiceData = csattendance::where('caseCode', $user->caseCode)
            ->join('communityservice', 'csattendance.csid', '=', 'communityservice.csid')
            ->select(DB::raw('SUM(csattendance.hoursspent) as total_hours'))
            ->first();

        $totalRequiredHours = criteria::selectRaw('criteria.cshours')->first()->cshours ?? 0;

        $completedHours = $communityServiceData->total_hours ?? 0;
        $remainingHours = max($totalRequiredHours - $completedHours, 0);

        $communityServiceChart = [
            'completed' => $completedHours,
            'remaining' => $remainingHours,
        ];

        return view('scholar.scholarship.overview', compact('user', 'penalty', 'chartData', 'communityServiceChart', 'renewal'));
    }

    public function showrenewalform()
    {
        $user = User::with(['basicInfo', 'education', 'addressInfo', 'clothingSize', 'scholarshipinfo'])
            ->where('id', Auth::id())
            ->first();

        $academiccycle = institutions::where('schoolname', $user->education->scSchoolName)
            ->where('schoollevel', $user->education->scSchoolLevel)
            ->pluck('academiccycle')->first();

        $grade = null;

        if (in_array($user->education->scSchoolLevel, ['College', 'Senior High'])) {
            $academicCycles = [
                'Semester' => ['2nd Semester', '1st Semester'],
                'Trimester' => ['3rd Semester', '2nd Semester', '1st Semester'],
            ];

            $semesters = $academicCycles[$academiccycle];

            foreach ($semesters as $semester) {
                $grade = grades::where('caseCode', $user->caseCode)->where('SemesterQuarter', $semester)->first();
                if ($grade) break; // Stop once a grade is found
            }
        } else {
            $quarters = ['4th Quarter', '3rd Quarter', '2nd Quarter', '1st Quarter'];
            foreach ($quarters as $quarter) {
                $grade = grades::where('caseCode', $user->caseCode)->where('SemesterQuarter', $quarter)->first();
                if ($grade) break; // Stop once a grade is found
            }
        }

        // dd($grade);

        $courses = courses::where('level', 'College')->get();
        $strands = courses::where('level', 'Senior High')->get();
        $institutions = [
            'Elementary' => DB::table('institutions')->where('schoollevel', 'Elementary')->get(),
            'Junior High' => DB::table('institutions')->where('schoollevel', 'Junior High')->get(),
            'Senior High' => DB::table('institutions')->where('schoollevel', 'Senior High')->get(),
            'College' => DB::table('institutions')->where('schoollevel', 'College')->get()
        ];

        return view('scholar.scholarship.screnewal', compact('courses', 'institutions', 'user', 'grade', 'strands'));
    }

    public function showGradeSubmission(Request $request)
    {
        // Retrieve the currently authenticated user's caseCode
        $user = Auth::user();
        $educ = ScEducation::where('caseCode', $user->caseCode)->first();
        $institution = institutions::where('schoolname', $educ->scSchoolName)
            ->where('schoollevel', $educ->scSchoolLevel)
            ->first();

        // dd($institution);

        $status = $request->input('status', 'all');

        // Fetch grades based on the filter status
        $grades = grades::where('caseCode', $user->caseCode)
            ->when($status !== 'all', function ($query) use ($status) {
                return $query->where('GradeStatus', $status);
            })
            ->get();
        return view('scholar/scholarship.gradesub', compact('grades', 'educ', 'status', 'institution'));
    }

    public function storeGradeSubmission(Request $request)
    {
        $user = Auth::user();
        $educ = ScEducation::where('caseCode', $user->caseCode)->first();
        $gwaRules = ['numeric'];

        if ($educ->scSchoolLevel == 'College') {
            $gwaRules[] = 'min:1';
            $gwaRules[] = 'max:5';
        } else {
            $gwaRules[] = 'min:1';
            $gwaRules[] = 'max:100';
        }

        try {
            if ($educ->scSchoolLevel == 'College') {
                $request->validate([
                    'semester' => 'required',
                    'gwa' => $gwaRules,
                    'gradeImage' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:2048']
                ]);
            } else {
                $request->validate([
                    'semester' => 'required',
                    'genave' => [$gwaRules, 'required'],
                    'gwaconduct' => 'required|string|min:1',
                    'chinesegenave' => [$gwaRules, 'nullable'],
                    'chineseconduct' => 'nullable|string|min:1',
                    'gradeImage' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:2048']
                ]);
            }

            $existingGrade = grades::where('caseCode', $user->caseCode)
                ->where('SemesterQuarter', $request->semester)
                ->where('schoolyear', $educ->scAcademicYear)
                ->first();

            if ($existingGrade) {
                return redirect()->back()->with('failure', 'A grade for this semester in the academic year ' . $educ->scAcademicYear . ' has already been submitted.')->withInput();
            }

            // Handle file upload
            if ($request->hasFile('gradeImage')) {
                $file = $request->file('gradeImage');

                // Create a custom file name using caseCode and last name
                $fileName = $user->caseCode . '_' . $user->basicInfo->scLastname . '_' . time() . '.' . $file->getClientOriginalExtension();

                // Store the file in the specified directory
                $filePath = $file->storeAs('uploads/grade_reports', $fileName, 'public');

                // Perform OCR on the uploaded image
                $ocr = new TesseractOCR(storage_path('app/public/' . $filePath));
                $tesseractPath = env('TESSERACT_PATH', '/usr/bin/tesseract'); // Default to /usr/bin/tesseract if not set
                $ocr->executable($tesseractPath); // Use the path from the .env file
                $extractedText = $ocr->run();

                // Debugging: Log or dump the extracted text to verify the result
                Log::info('Full OCR Extracted Text: ' . $extractedText);

                // Patterns to extract GPA in multiple formats
                $patterns = [
                    '/General Average[^0-9]*([\d.]+)/i',  // Matches "General Average: 70%" or similar
                    '/Average[^0-9]*([\d.]+)/i',          // Matches "Average: 70%"
                    '/GPA[^0-9]*([\d.]+)/i',              // Retain existing GPA patterns
                    '/GWA[^0-9]*([\d.]+)/i',              // Retain GWA patterns
                    '/Grade Point Average[^0-9]*([\d.]+)/i'
                ];

                $ocrGpa = null;

                // Attempt to match each pattern
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $extractedText, $matches)) {
                        $ocrGpa = floatval($matches[1]);
                        break; // Stop once a match is found
                    }
                }

                // If no GPA found, handle the failure
                if ($ocrGpa === null) {
                    return redirect()->back()->with('failure', 'Could not extract GPA from the uploaded image. Please ensure it is legible and try again.')->withInput();
                }

                // Validate OCR GPA with user input
                $inputGpa = $request->gwa ?? $request->genave; // Adjust as per your input field names
                if (abs($ocrGpa - $inputGpa) > 0.01) { // Allow for minor floating-point differences
                    return redirect()->back()->with('failure', 'The GPA in the image (' . $ocrGpa . ') does not match the input GPA (' . $inputGpa . '). Please verify your entry.')->withInput();
                }
            } else {
                return redirect()->back()->with('failure', 'File upload failed. Please try again.')->withInput();
            }


            $criteria = criteria::first();
            $requiredgwa = [
                'College' => $criteria->cgwa,
                'Senior High' => $criteria->shsgwa,
                'Junior High' => $criteria->jhsgwa,
                'Elementary' => $criteria->elemgwa,
            ];

            $gradingsystem = institutions::where('schoolname', $educ->scSchoolName)
                ->where('schoollevel', $educ->scSchoolLevel)->first();

            // dd($gradingsystem);
            if ($gradingsystem->highestgwa == 1) {
                if ($educ->scSchoolLevel == 'College') {
                    $gradeStatus = ($request->gwa > $requiredgwa['College']) ? 'Failed GWA' : 'Passed';
                } else {
                    if ($request->genave > $requiredgwa[$educ->scSchoolLevel]) {
                        $gradeStatus = 'Failed GWA';
                    } else if ($request->genave > $requiredgwa[$educ->scSchoolLevel]) {
                        $gradeStatus = 'Failed GWA (Chinese Subject)';
                    } else {
                        $gradeStatus = 'Passed';
                    }
                }
            } else if ($gradingsystem->highestgwa == 5) {
                if ($educ->scSchoolLevel == 'College') {
                    $gradeStatus = ($request->gwa < $requiredgwa['College']) ? 'Failed GWA' : 'Passed';
                } else {
                    if ($request->genave < $requiredgwa[$educ->scSchoolLevel]) {
                        $gradeStatus = 'Failed GWA';
                    } else if ($request->genave < $requiredgwa[$educ->scSchoolLevel]) {
                        $gradeStatus = 'Failed GWA (Chinese Subject)';
                    } else {
                        $gradeStatus = 'Passed';
                    }
                }
            } else if ($gradingsystem->highestgwa == 100) {
                if ($educ->scSchoolLevel == 'College') {
                    $gradeStatus = ($request->gwa < $requiredgwa['College']) ? 'Failed GWA' : 'Passed';
                } else {
                    if ($request->genave < $requiredgwa[$educ->scSchoolLevel]) {
                        $gradeStatus = 'Failed GWA';
                    } else if ($request->genave < $requiredgwa[$educ->scSchoolLevel]) {
                        $gradeStatus = 'Failed GWA (Chinese Subject)';
                    } else {
                        $gradeStatus = 'Passed';
                    }
                }
            }

            DB::beginTransaction();
            // Save the grade entry and link it to the educationID
            grades::create([
                'caseCode' => $user->caseCode, // Link the grade to the scholar
                'schoolyear' => $educ->scAcademicYear,
                'SemesterQuarter' => $request->semester,
                'GWA' => $request->gwa ?? $request->genave,
                'GWAConduct' => $request->gwaconduct ?? NULL,
                'ChineseGWA' => $request->chinesegenave ?? NULL,
                'ChineseGWAConduct' => $request->chineseconduct ?? NULL,
                'ReportCard' => $filePath,
                'GradeStatus' => $gradeStatus
            ]);

            if ($gradeStatus == 'Failed GWA') {
                $scinfo = scholarshipinfo::where('caseCode', $user->caseCode)->first();
                $worker = staccount::where('area', $scinfo->area)->first();

                $gradeinfo = grades::where('caseCode', $user->caseCode)
                    ->where('schoolyear', $educ->scAcademicYear)
                    ->where('SemesterQuarter', $request->semester)->first();
                lte::create([
                    'caseCode' => $user->caseCode,
                    'violation' => $gradeStatus,
                    'conditionid' => $gradeinfo->gid,
                    'eventtype' => null,
                    'dateissued' => now(),
                    'deadline' => Carbon::now()->addDays(3),
                    'datesubmitted' => null,
                    'reason' => null,
                    'explanation' => null,
                    'proof' => null,
                    'ltestatus' => 'No Response',
                    'workername' => strtoupper($worker->name) . ', RSW',
                ]);
                $scinfo->scholarshipstatus = 'On-Hold';
                $scinfo->save();
            }

            DB::commit();
            // Redirect on success and pass the grades data
            return redirect()->route('gradesub')->with('success', 'Grade submission uploaded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error for debugging purposes
            Log::error('Grade submission failed: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Something went wrong. Please try again later.' . $e->getMessage())->withInput();
        }
    }

    public function showGradeInfo($id)
    {
        // Find the grade using the correct primary key
        $grade = grades::findOrFail($id);
        $educ = ScEducation::where('caseCode', $grade->caseCode)->first();

        // Pass the grade data and academic year to the view
        return view('scholar.scholarship.gradesinfo', compact('grade', 'educ'));
    }

    public function showHumanitiesClass(Request $request)
    {
        $scholar = Auth::user();

        // Get the search and attendance status filters
        $search = $request->input('search');
        $attendanceStatus = $request->input('attendance_status', 'all');

        // Query the humanities class with attendance data
        $query = humanitiesclass::with(['hcattendance' => function ($query) use ($scholar, $attendanceStatus) {
            $query->where('caseCode', $scholar->caseCode);

            if ($attendanceStatus !== 'all') {
                $query->where('hcastatus', $attendanceStatus);
            }
        }]);

        // Apply search filter if a search term is provided
        if ($search) {
            $query->where('topic', 'like', "%{$search}%");
        }

        $classes = $query->get();

        $totalattendance = hcattendance::where('caseCode', $scholar->caseCode)->count();
        $totaltardiness = hcattendance::where('caseCode', $scholar->caseCode)->sum('tardinessduration');
        $totalabsences = hcattendance::where('caseCode', $scholar->caseCode)
            ->where('hcastatus', 'Absent')
            ->count();

        return view('scholar.scholarship.schumanities', compact('classes', 'totalattendance', 'totaltardiness', 'totalabsences'));
    }

    public function showLTE(Request $request)
    {
        $scholar = Auth::user();
        $noresponseletters = lte::with(['hcattendance', 'csattendance', 'csregistration'])
            ->where('caseCode', $scholar->caseCode)->where('ltestatus', "No Response")->get();

        // Retrieve the status from the request
        $status = $request->input('lte_status', 'all');

        $letters = lte::where('caseCode', $scholar->caseCode)
            ->when($status === 'all', function ($query) {
                return $query->where('ltestatus', '!=', 'No Response');
            }, function ($query) use ($status) {
                return $query->where('ltestatus', $status)->where('ltestatus', '!=', 'No Response');
            })
            ->get();

        return view('scholar.scholarship.sclte', compact('noresponseletters', 'letters'));
    }

    public function showLTEinfo($lid)
    {
        $letter = lte::where('lid', $lid)->first();

        $scholar = User::with(['basicInfo', 'education'])
            ->where('id', Auth::id())
            ->first();

        if ($letter->violation == 'Failed GWA' || $letter->violation == 'Failed Grade' || $letter->violation == 'Mismatched GWA') {
            $academicData = grades::where('gid', $letter->conditionid)->first();

            return view('scholar.scholarship.lteinfo', compact('letter', 'scholar', 'academicData'));
        } else {
            if ($letter->eventtype == 'Humanities Class') {
                $violation = hcattendance::where('hcaid', $letter->conditionid)->first();
                $eventinfo = humanitiesclass::where('hcid', $violation->hcid)->first();
            } elseif ($letter->eventtype == 'Community Service') {
                $violation = csregistration::where('csrid', $letter->conditionid)->first();
                $eventinfo = communityservice::where('csid', $violation->csid)->first();
            }

            return view('scholar.scholarship.lteinfo', compact('letter', 'scholar', 'eventinfo'));
        }
    }

    public function showLTEForm($lid)
    {
        return view('scholar.scholarship.lteform', compact(var_name: 'lid'));
    }

    public function storeLTEForm(Request $request, $lid)
    {
        // get the data in lte
        $letter = lte::where('lid', $lid)->first();

        // Validate the incoming request
        $request->validate([
            'explanation' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'reason' => 'required|string',
            'medical-file' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'academic-file' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'death-file' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'disaster-file' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        // Retrieve the currently authenticated user's details
        $user = Auth::user();

        // Construct the directory path based on the user's full name and case code
        $directoryPath = 'scholars/'
            . $user->basicInfo->scLastname . ', '
            . $user->basicInfo->scFirstname . ' '
            . $user->basicInfo->scMiddlename . '_'
            . $user->caseCode . '/lte_submitted/' . $letter->eventtype . '/' .  $letter->conditionid;

        // Ensure the directory exists
        if (!Storage::exists('public/' . $directoryPath)) {
            Storage::makeDirectory('public/' . $directoryPath);
        }

        // Store the explanation file in the specified directory
        $explanationPath = $request->file('explanation')->storeAs(
            'public/' . $directoryPath,
            'explanation_' . time() . '.' . $request->file('explanation')->getClientOriginalExtension()
        );

        // Initialize the reason file path variable
        $reasonFilePath = null;

        // Depending on the selected reason, store the related file in the specified directory
        switch ($request->input('reason')) {
            case 'Medical':
                if ($request->hasFile('medical-file')) {
                    $reasonFilePath = $request->file('medical-file')->storeAs(
                        'public/' . $directoryPath,
                        'medical_' . time() . '.' . $request->file('medical-file')->getClientOriginalExtension()
                    );
                }
                break;
            case 'Academic Activity':
                if ($request->hasFile('academic-file')) {
                    $reasonFilePath = $request->file('academic-file')->storeAs(
                        'public/' . $directoryPath,
                        'academic_' . time() . '.' . $request->file('academic-file')->getClientOriginalExtension()
                    );
                }
                break;
            case 'Death of an Immediate Family Member':
                if ($request->hasFile('death-file')) {
                    $reasonFilePath = $request->file('death-file')->storeAs(
                        'public/' . $directoryPath,
                        'death_' . time() . '.' . $request->file('death-file')->getClientOriginalExtension()
                    );
                }
                break;
            case 'Natural and Human induced disasters':
                if ($request->hasFile('disaster-file')) {
                    $reasonFilePath = $request->file('disaster-file')->storeAs(
                        'public/' . $directoryPath,
                        'disaster_' . time() . '.' . $request->file('disaster-file')->getClientOriginalExtension()
                    );
                }
                break;
        }

        // Convert paths to remove the 'public/' prefix so they can be accessed correctly via the browser
        $explanationPathForDB = str_replace('public/', '', $explanationPath);
        $reasonFilePathForDB = $reasonFilePath ? str_replace('public/', '', $reasonFilePath) : null;

        // Retrieve the LTE record by its ID
        $lte = LTE::findOrFail($lid);

        // Update the record with new data
        $lte->update([
            'datesubmitted' => now(), // Update the date submitted to the current time
            'explanation' => $explanationPathForDB,
            'reason' => $request->reason,
            'proof' => $reasonFilePathForDB,
            'ltestatus' => 'To Review'
            // other fields as needed...
        ]);

        return redirect()->route('sclte')->with('success', 'LTE submission successful.');
    }

    public function showSubLTEInfo($lid)
    {

        $letter = lte::where('lid', $lid)->first();

        $concerncsregistration = csregistration::where('csrid', $letter->conditionid)->first();
        $concernhcattendance = hcattendance::where('hcaid', $letter->conditionid)->first();
        // for the if statement pdf, image
        $fileExtensionExplanation = pathinfo($letter->explanation, PATHINFO_EXTENSION);
        $fileExtensionProof = pathinfo($letter->proof, PATHINFO_EXTENSION);

        return view('scholar.scholarship.sublteinfo', compact('letter', 'fileExtensionExplanation', 'fileExtensionProof', 'concerncsregistration', 'concernhcattendance'));
    }


    // public function showspecialallowance()
    // {
    //     $scholar = User::with('education')
    //         ->where('id', Auth::id())
    //         ->first();

    //     $reqbook = allowancebook::where('caseCode', $scholar->caseCode)->orderBy('created_at', 'asc')->get();
    //     $reqevent = allowanceevent::where('caseCode', $scholar->caseCode)->orderBy('created_at', 'asc')->get();
    //     $reqthesis = allowancethesis::where('caseCode', $scholar->caseCode)->orderBy('created_at', 'asc')->get();
    //     $reqproj = allowanceproject::where('caseCode', $scholar->caseCode)->orderBy('created_at', 'asc')->get();
    //     $reqtranspo = allowancetranspo::where('caseCode', $scholar->caseCode)->orderBy('created_at', 'asc')->get();
    //     $requnif = allowanceuniform::where('caseCode', $scholar->caseCode)->orderBy('created_at', 'asc')->get();
    //     $reqgrad = allowancegraduation::where('caseCode', $scholar->caseCode)->orderBy('created_at', 'asc')->get();

    //     $mergedrequests = $reqbook
    //         ->concat($reqevent)
    //         ->concat($reqthesis)
    //         ->concat($reqproj)
    //         ->concat($reqtranspo)
    //         ->concat($requnif)
    //         ->concat($reqgrad);

    //     $requests = $mergedrequests->sortBy('created_at')->values();

    //     return view('scholar.allowancerequest.scspecial', compact('requests', 'scholar'));
    // }

    public function showspecialallowance(Request $request)
    {
        $scholar = User::with('education')
            ->where('id', Auth::id())
            ->first();

        $reqbook = allowancebook::where('caseCode', $scholar->caseCode)->orderBy('created_at', 'asc')->get();
        $reqevent = allowanceevent::where('caseCode', $scholar->caseCode)->orderBy('created_at', 'asc')->get();
        $reqthesis = allowancethesis::where('caseCode', $scholar->caseCode)->orderBy('created_at', 'asc')->get();
        $reqproj = allowanceproject::where('caseCode', $scholar->caseCode)->orderBy('created_at', 'asc')->get();
        $reqtranspo = allowancetranspo::where('caseCode', $scholar->caseCode)->orderBy('created_at', 'asc')->get();
        $requnif = allowanceuniform::where('caseCode', $scholar->caseCode)->orderBy('created_at', 'asc')->get();
        $reqgrad = allowancegraduation::where('caseCode', $scholar->caseCode)->orderBy('created_at', 'asc')->get();

        $mergedrequests = $reqbook
            ->concat($reqevent)
            ->concat($reqthesis)
            ->concat($reqproj)
            ->concat($reqtranspo)
            ->concat($requnif)
            ->concat($reqgrad);

        $status = $request->input('status', 'all');

        if ($status !== 'all') {
            $requests = $mergedrequests->where('status', $status)->sortBy('created_at')->values();
        } else {
            $requests = $mergedrequests->sortBy('created_at')->values();
        }

        return view('scholar.allowancerequest.scspecial', compact('requests', 'scholar', 'status'));
    }

    public function showrequestinstruction($requesttype)
    {
        $transpo = specialallowanceforms::where('filetype', 'TRF')->first();
        $cert = specialallowanceforms::where('filetype', 'PBCF')->first();
        $acknowledgement = specialallowanceforms::where('filetype', 'AR')->first();
        $liquidation = specialallowanceforms::where('filetype', 'LF')->first();

        if ($requesttype == 'TRF') {
            if ($transpo == NULL) {
                return redirect()->route('scspecial')->with('failure', 'We apologize, but this special request is currently unavailable. For urgent assistance, please contact one of our social workers for support.');
            }
            return view('scholar.allowancerequest.transporeq', compact('transpo'));
        } elseif ($requesttype == 'BAR') {
            if ($cert == NULL || $acknowledgement == NULL || $liquidation == NULL) {
                return redirect()->route('scspecial')->with('failure', 'We apologize, but this special request is currently unavailable. For urgent assistance, please contact one of our social workers for support.');
            }
            return view('scholar.allowancerequest.bookreq', compact('cert', 'acknowledgement', 'liquidation'));
        } elseif ($requesttype == 'TAR') {
            if ($acknowledgement == NULL || $liquidation == NULL) {
                return redirect()->back()->with('failure', 'We apologize, but this special request is currently unavailable. For urgent assistance, please contact one of our social workers for support.');
            }
            return view('scholar.allowancerequest.thesisreq', compact('acknowledgement', 'liquidation'));
        } elseif ($requesttype == 'PAR') {
            if ($cert == NULL || $acknowledgement == NULL || $liquidation == NULL) {
                return redirect()->back()->with('failure', 'We apologize, but this special request is currently unavailable. For urgent assistance, please contact one of our social workers for support.');
            }
            return view('scholar.allowancerequest.projectreq', compact('cert', 'acknowledgement', 'liquidation'));
        } elseif ($requesttype == 'UAR') {
            if ($acknowledgement == NULL || $liquidation == NULL) {
                return redirect()->back()->with('failure', 'We apologize, but this special request is currently unavailable. For urgent assistance, please contact one of our social workers for support.');
            }
            return view('scholar.allowancerequest.uniformreq', compact('acknowledgement', 'liquidation'));
        } elseif ($requesttype == 'GAR') {
            if ($acknowledgement == NULL || $liquidation == NULL) {
                return redirect()->back()->with('failure', 'We apologize, but this special request is currently unavailable. For urgent assistance, please contact one of our social workers for support.');
            }
            return view('scholar.allowancerequest.gradreq', compact('acknowledgement', 'liquidation'));
        } elseif ($requesttype == 'FTTSAR') {
            if ($acknowledgement == NULL || $liquidation == NULL) {
                return redirect()->back()->with('failure', 'We apologize, but this special request is currently unavailable. For urgent assistance, please contact one of our social workers for support.');
            }
            return view('scholar.allowancerequest.fieldtripreq', compact('acknowledgement', 'liquidation'));
        }
    }

    public function showrequestform($formtype)
    {
        $scholar = User::with(['basicInfo', 'education', 'scholarshipinfo'])
            ->find(Auth::id());
        if ($formtype == 'TRF') {
            return view('scholar.allowancerequest.transpoform', compact('scholar'));
        } elseif ($formtype == 'BAR') {
            return view('scholar.allowancerequest.bookform', compact('scholar'));
        } elseif ($formtype == 'TAR') {
            return view('scholar.allowancerequest.thesisform', compact('scholar'));
        } elseif ($formtype == 'PAR') {
            return view('scholar.allowancerequest.projectform', compact('scholar'));
        } elseif ($formtype == 'UAR') {
            return view('scholar.allowancerequest.uniformform', compact('scholar'));
        } elseif ($formtype == 'GAR') {
            return view('scholar.allowancerequest.gradform', compact('scholar'));
        } elseif ($formtype == 'FTTSAR') {
            return view('scholar.allowancerequest.fieldtripform', compact('scholar'));
        }
    }

    public function reqallowancebook($caseCode, Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'booktitle' => 'string|max:255',
                    'author' => 'string|max:255',
                    'price' => 'numeric|min:1',
                    'certification' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'acknowledgement' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'purchaseproof' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'liquidation' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                ],
                [
                    'booktitle.string' => 'The book title must be a valid string.',
                    'booktitle.max' => 'The book title must not be greater than 255 characters.',
                    'author.string' => 'The author name must be a valid string.',
                    'author.max' => 'The author name must not be greater than 255 characters.',
                    'price.numeric' => 'The price must be a valid number.',
                    'price.min' => 'The price must be at least 1.',
                    'certification.mimes' => 'The certification must be a file of type: pdf, jpg, jpeg, png.',
                    'certification.max' => 'The certification file must not exceed 2 MB.',
                    'acknowledgement.mimes' => 'The acknowledgement must be a file of type: pdf, jpg, jpeg, png.',
                    'acknowledgement.max' => 'The acknowledgement file must not exceed 2 MB.',
                    'purchaseproof.mimes' => 'The purchase proof must be a file of type: pdf, jpg, jpeg, png.',
                    'purchaseproof.max' => 'The purchase proof file must not exceed 2 MB.',
                    'liquidation.mimes' => 'The liquidation must be a file of type: pdf, jpg, jpeg, png.',
                    'liquidation.max' => 'The liquidation file must not exceed 2 MB.',
                ]
            );

            $certification = $request->file('certification');
            $acknowledgement = $request->file('acknowledgement');
            $purchaseproof = $request->file('purchaseproof');
            $liquidation = $request->file('liquidation');

            $datetime = now()->format('Ymd_His');

            $filename_certification = $datetime . '_' . $caseCode . '_Book_Certification.' . $certification->getClientOriginalExtension();
            $filename_acknowledgement = $datetime . '_' . $caseCode . '_Acknowledgement_Receipt.' . $acknowledgement->getClientOriginalExtension();
            $filename_purchaseproof = $datetime . '_' . $caseCode . '_Proof_of_Purchase.' . $purchaseproof->getClientOriginalExtension();
            $filename_liquidation = $datetime . '_' . $caseCode . '_Liquidation_Form.' . $liquidation->getClientOriginalExtension();

            $path_certification = $certification->storeAs('uploads/allowance_requests/special/book_requests', $filename_certification, 'public');
            $path_acknowledgement = $acknowledgement->storeAs('uploads/allowance_requests/special/book_requests', $filename_acknowledgement, 'public');
            $path_purchaseproof = $purchaseproof->storeAs('uploads/allowance_requests/special/book_requests', $filename_purchaseproof, 'public');
            $path_liquidation = $liquidation->storeAs('uploads/allowance_requests/special/book_requests', $filename_liquidation, 'public');

            allowancebook::create([
                'caseCode' => $caseCode,
                'booktitle' => $request->booktitle,
                'author' => $request->author,
                'price' => $request->price,
                'certification' => $path_certification,
                'acknowledgement' => $path_acknowledgement,
                'purchaseproof' => $path_purchaseproof,
                'liquidation' => $path_liquidation
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Your book allowance request has been successfully submitted. You can view the status and additional details of your request by navigating to Allowance Requests > Special Allowance.');
        } catch (ValidationException $e) {
            DB::rollBack();
            $errors = $e->errors();
            $errorMessages = '<ul>';
            foreach ($errors as $fieldErrors) {
                foreach ($fieldErrors as $errorMessage) {
                    $errorMessages .= '<li>' . $errorMessage . '</li>';
                }
            }
            $errorMessages .= '</ul>';
            return redirect()->back()->with('failure', 'Your request could not be processed. Please review the following errors: ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('failure', 'Your request could not be processed. Please review the following errors: ' . $e->getMessage());
        };
    }

    public function reqallowanceevent($caseCode, Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'eventtype' => 'string|max:50',
                    'eventloc' => 'string|max:255',
                    'totalprice' => 'numeric|min:1',
                    'memo' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'waiver' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'acknowledgement' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'liquidation' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                ],
                [
                    'eventtype.string' => 'The event type must be a valid string.',
                    'eventtype.max' => 'The event type must not be greater than 50 characters.',
                    'eventloc.string' => 'The author name must be a valid string.',
                    'eventloc.max' => 'The author name must not be greater than 255 characters.',
                    'totalprice.numeric' => 'The price must be a valid number.',
                    'totalprice.min' => 'The price must be at least 1.',
                    'memo.mimes' => 'The memo must be a file of type: pdf, jpg, jpeg, png.',
                    'memo.max' => 'The memo file must not exceed 2 MB.',
                    'waiver.mimes' => 'The waiver must be a file of type: pdf, jpg, jpeg, png.',
                    'waiver.max' => 'The waiver file must not exceed 2 MB.',
                    'acknowledgement.mimes' => 'The acknowledgement must be a file of type: pdf, jpg, jpeg, png.',
                    'acknowledgement.max' => 'The acknowledgement file must not exceed 2 MB.',
                    'liquidation.mimes' => 'The liquidation must be a file of type: pdf, jpg, jpeg, png.',
                    'liquidation.max' => 'The liquidation file must not exceed 2 MB.',
                ]
            );

            $memo = $request->file('memo');
            $waiver = $request->file('waiver');
            $acknowledgement = $request->file('acknowledgement');
            $liquidation = $request->file('liquidation');

            $datetime = now()->format('Ymd_His');

            $filename_memo = $datetime . '_' . $caseCode . '_Memo.' . $memo->getClientOriginalExtension();
            $filename_acknowledgement = $datetime . '_' . $caseCode . '_Acknowledgement_Receipt.' . $acknowledgement->getClientOriginalExtension();
            $filename_waiver = $datetime . '_' . $caseCode . '_Waiver.' . $waiver->getClientOriginalExtension();
            $filename_liquidation = $datetime . '_' . $caseCode . '_Liquidation_Form.' . $liquidation->getClientOriginalExtension();

            $path_memo = $memo->storeAs('uploads/allowance_requests/special/event_requests', $filename_memo, 'public');
            $path_acknowledgement = $acknowledgement->storeAs('uploads/allowance_requests/special/event_requests', $filename_acknowledgement, 'public');
            $path_waiver = $waiver->storeAs('uploads/allowance_requests/special/event_requests', $filename_waiver, 'public');
            $path_liquidation = $liquidation->storeAs('uploads/allowance_requests/special/event_requests', $filename_liquidation, 'public');

            allowanceevent::create([
                'caseCode' => $caseCode,
                'eventtype' => $request->eventtype,
                'eventloc' => $request->eventloc,
                'totalprice' => $request->totalprice,
                'memo' => $path_memo,
                'waiver' => $path_waiver,
                'acknowledgement' => $path_acknowledgement,
                'liquidation' => $path_liquidation
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Your event allowance request has been successfully submitted. You can view the status and additional details of your request by navigating to Allowance Requests > Special Allowance.');
        } catch (ValidationException $e) {
            DB::rollBack();
            $errors = $e->errors();
            $errorMessages = '<ul>';
            foreach ($errors as $fieldErrors) {
                foreach ($fieldErrors as $errorMessage) {
                    $errorMessages .= '<li>' . $errorMessage . '</li>';
                }
            }
            $errorMessages .= '</ul>';
            return redirect()->back()->with('failure', 'Your request could not be processed. Please review the following errors: ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('failure', 'Your request could not be processed. Please review the following errors: ' . $e->getMessage());
        };
    }

    public function reqallowancegraduation($caseCode, Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'totalprice' => 'numeric|min:1',
                    'gradlist' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'acknowledgement' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'liquidation' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                ],
                [
                    'totalprice.numeric' => 'The price must be a valid number.',
                    'totalprice.min' => 'The price must be at least 1.',
                    'gradlist.mimes' => 'The official list of graduates must be a file of type: pdf, jpg, jpeg, png.',
                    'gradlist.max' => 'The official list of graduates file must not exceed 2 MB.',
                    'acknowledgement.mimes' => 'The acknowledgement must be a file of type: pdf, jpg, jpeg, png.',
                    'acknowledgement.max' => 'The acknowledgement file must not exceed 2 MB.',
                    'liquidation.mimes' => 'The liquidation must be a file of type: pdf, jpg, jpeg, png.',
                    'liquidation.max' => 'The liquidation file must not exceed 2 MB.',
                ]
            );

            $gradlist = $request->file('gradlist');
            $acknowledgement = $request->file('acknowledgement');
            $liquidation = $request->file('liquidation');

            $datetime = now()->format('Ymd_His');

            $filename_gradlist = $datetime . '_' . $caseCode . '_List_of_Graduates.' . $gradlist->getClientOriginalExtension();
            $filename_acknowledgement = $datetime . '_' . $caseCode . '_Acknowledgement_Receipt.' . $acknowledgement->getClientOriginalExtension();
            $filename_liquidation = $datetime . '_' . $caseCode . '_Liquidation_Form.' . $liquidation->getClientOriginalExtension();

            $path_gradlist = $gradlist->storeAs('uploads/allowance_requests/special/graduation_requests', $filename_gradlist, 'public');
            $path_acknowledgement = $acknowledgement->storeAs('uploads/allowance_requests/special/graduation_requests', $filename_acknowledgement, 'public');
            $path_liquidation = $liquidation->storeAs('uploads/allowance_requests/special/graduation_requests', $filename_liquidation, 'public');

            allowancegraduation::create([
                'caseCode' => $caseCode,
                'totalprice' => $request->totalprice,
                'listofgraduates' => $path_gradlist,
                'acknowledgement' => $path_acknowledgement,
                'liquidation' => $path_liquidation
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Your graduation allowance request has been successfully submitted. You can view the status and additional details of your request by navigating to Allowance Requests > Special Allowance.');
        } catch (ValidationException $e) {
            DB::rollBack();
            $errors = $e->errors();
            $errorMessages = '<ul>';
            foreach ($errors as $fieldErrors) {
                foreach ($fieldErrors as $errorMessage) {
                    $errorMessages .= '<li>' . $errorMessage . '</li>';
                }
            }
            $errorMessages .= '</ul>';
            return redirect()->back()->with('failure', 'Your request could not be processed. Please review the following errors: ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('failure', 'Your request could not be processed. Please review the following errors: ' . $e->getMessage());
        };
    }

    public function reqallowanceproject($caseCode, Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'subject' => 'string|max:255',
                    'totalprice' => 'numeric|min:1',
                    'certification' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'acknowledgement' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'purchaseproof' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'liquidation' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                ],
                [
                    'subject.string' => 'The subject must be a valid string.',
                    'subject.max' => 'The subject must not be greater than 255 characters.',
                    'totalprice.numeric' => 'The price must be a valid number.',
                    'totalprice.min' => 'The price must be at least 1.',
                    'certification.mimes' => 'The certification must be a file of type: pdf, jpg, jpeg, png.',
                    'certification.max' => 'The certification file must not exceed 2 MB.',
                    'acknowledgement.mimes' => 'The acknowledgement must be a file of type: pdf, jpg, jpeg, png.',
                    'acknowledgement.max' => 'The acknowledgement file must not exceed 2 MB.',
                    'purchaseproof.mimes' => 'The purchase proof must be a file of type: pdf, jpg, jpeg, png.',
                    'purchaseproof.max' => 'The purchase proof file must not exceed 2 MB.',
                    'liquidation.mimes' => 'The liquidation must be a file of type: pdf, jpg, jpeg, png.',
                    'liquidation.max' => 'The liquidation file must not exceed 2 MB.',
                ]
            );

            $certification = $request->file('certification');
            $acknowledgement = $request->file('acknowledgement');
            $purchaseproof = $request->file('purchaseproof');
            $liquidation = $request->file('liquidation');

            $datetime = now()->format('Ymd_His');

            $filename_certification = $datetime . '_' . $caseCode . '_Project_Certification.' . $certification->getClientOriginalExtension();
            $filename_acknowledgement = $datetime . '_' . $caseCode . '_Acknowledgement_Receipt.' . $acknowledgement->getClientOriginalExtension();
            $filename_purchaseproof = $datetime . '_' . $caseCode . '_Proof_of_Purchase.' . $purchaseproof->getClientOriginalExtension();
            $filename_liquidation = $datetime . '_' . $caseCode . '_Liquidation_Form.' . $liquidation->getClientOriginalExtension();

            $path_certification = $certification->storeAs('uploads/allowance_requests/special/project_requests', $filename_certification, 'public');
            $path_acknowledgement = $acknowledgement->storeAs('uploads/allowance_requests/special/project_requests', $filename_acknowledgement, 'public');
            $path_purchaseproof = $purchaseproof->storeAs('uploads/allowance_requests/special/project_requests', $filename_purchaseproof, 'public');
            $path_liquidation = $liquidation->storeAs('uploads/allowance_requests/special/project_requests', $filename_liquidation, 'public');

            allowanceproject::create([
                'caseCode' => $caseCode,
                'subject' => $request->subject,
                'totalprice' => $request->totalprice,
                'certification' => $path_certification,
                'acknowledgement' => $path_acknowledgement,
                'purchaseproof' => $path_purchaseproof,
                'liquidation' => $path_liquidation
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Your project allowance request has been successfully submitted. You can view the status and additional details of your request by navigating to Allowance Requests > Special Allowance.');
        } catch (ValidationException $e) {
            DB::rollBack();
            $errors = $e->errors();
            $errorMessages = '<ul>';
            foreach ($errors as $fieldErrors) {
                foreach ($fieldErrors as $errorMessage) {
                    $errorMessages .= '<li>' . $errorMessage . '</li>';
                }
            }
            $errorMessages .= '</ul>';
            return redirect()->back()->with('failure', 'Your request could not be processed. Please review the following errors: ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('failure', 'Your request could not be processed. Please review the following errors: ' . $e->getMessage());
        };
    }

    public function reqallowancethesis($caseCode, Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'thesistitle' => 'string|max:255',
                    'totalprice' => 'numeric|min:1',
                    'titlepage' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'acknowledgement' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'purchaseproof' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'liquidation' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                ],
                [
                    'thesistitle.string' => 'The thesis title must be a valid string.',
                    'thesistitle.max' => 'The thesis title must not be greater than 255 characters.',
                    'totalprice.numeric' => 'The price must be a valid number.',
                    'totalprice.min' => 'The price must be at least 1.',
                    'titlepage.mimes' => 'The title page must be a file of type: pdf, jpg, jpeg, png.',
                    'titlepage.max' => 'The title page file must not exceed 2 MB.',
                    'acknowledgement.mimes' => 'The acknowledgement must be a file of type: pdf, jpg, jpeg, png.',
                    'acknowledgement.max' => 'The acknowledgement file must not exceed 2 MB.',
                    'purchaseproof.mimes' => 'The purchase proof must be a file of type: pdf, jpg, jpeg, png.',
                    'purchaseproof.max' => 'The purchase proof file must not exceed 2 MB.',
                    'liquidation.mimes' => 'The liquidation must be a file of type: pdf, jpg, jpeg, png.',
                    'liquidation.max' => 'The liquidation file must not exceed 2 MB.',
                ]
            );

            $titlepage = $request->file('titlepage');
            $acknowledgement = $request->file('acknowledgement');
            $purchaseproof = $request->file('purchaseproof');
            $liquidation = $request->file('liquidation');

            $datetime = now()->format('Ymd_His');

            $filename_titlepage = $datetime . '_' . $caseCode . '_Title_Page.' . $titlepage->getClientOriginalExtension();
            $filename_acknowledgement = $datetime . '_' . $caseCode . '_Acknowledgement_Receipt.' . $acknowledgement->getClientOriginalExtension();
            $filename_purchaseproof = $datetime . '_' . $caseCode . '_Proof_of_Purchase.' . $purchaseproof->getClientOriginalExtension();
            $filename_liquidation = $datetime . '_' . $caseCode . '_Liquidation_Form.' . $liquidation->getClientOriginalExtension();

            $path_titlepage = $titlepage->storeAs('uploads/allowance_requests/special/thesis_requests', $filename_titlepage, 'public');
            $path_acknowledgement = $acknowledgement->storeAs('uploads/allowance_requests/special/thesis_requests', $filename_acknowledgement, 'public');
            $path_purchaseproof = $purchaseproof->storeAs('uploads/allowance_requests/special/thesis_requests', $filename_purchaseproof, 'public');
            $path_liquidation = $liquidation->storeAs('uploads/allowance_requests/special/thesis_requests', $filename_liquidation, 'public');

            allowancethesis::create([
                'caseCode' => $caseCode,
                'thesistitle' => $request->thesistitle,
                'totalprice' => $request->totalprice,
                'titlepage' => $path_titlepage,
                'acknowledgement' => $path_acknowledgement,
                'purchaseproof' => $path_purchaseproof,
                'liquidation' => $path_liquidation
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Your thesis allowance request has been successfully submitted. You can view the status and additional details of your request by navigating to Allowance Requests > Special Allowance.');
        } catch (ValidationException $e) {
            DB::rollBack();
            $errors = $e->errors();
            $errorMessages = '<ul>';
            foreach ($errors as $fieldErrors) {
                foreach ($fieldErrors as $errorMessage) {
                    $errorMessages .= '<li>' . $errorMessage . '</li>';
                }
            }
            $errorMessages .= '</ul>';
            return redirect()->back()->with('failure', 'Your request could not be processed. Please review the following errors: ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('failure', 'Your request could not be processed. Please review the following errors: ' . $e->getMessage());
        };
    }

    public function reqallowancetranspo($caseCode, Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'totalprice' => 'numeric|min:1',
                    'purpose' => 'string|max:255',
                    'staffname' => 'string|max:255',
                    'transpoform' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                ],
                [
                    'totalprice.numeric' => 'The price must be a valid number.',
                    'totalprice.min' => 'The price must be at least 1.',
                    'purpose.string' => 'The purpose/activity must be a valid string.',
                    'purpose.max' => 'The purpose/activity must not be greater than 255 characters.',
                    'staffname.string' => 'The name of staff/volunteer must be a valid string.',
                    'staffname.max' => 'The name of staff/volunteer must not be greater than 255 characters.',
                    'transpoform.mimes' => 'The transportation reimbursement form must be a file of type: pdf, jpg, jpeg, png.',
                    'transpoform.max' => 'The transportation reimbursement form must not exceed 2 MB.',
                ]
            );

            $transpoform = $request->file('transpoform');

            $datetime = now()->format('Ymd_His');

            $filename_transpoform = $datetime . '_' . $caseCode . '_Transportation_Reimbursement_Form.' . $transpoform->getClientOriginalExtension();

            $path_transpoform = $transpoform->storeAs('uploads/allowance_requests/special/transportation_reimbursements', $filename_transpoform, 'public');

            allowancetranspo::create([
                'caseCode' => $caseCode,
                'totalprice' => $request->totalprice,
                'purpose' => $request->purpose,
                'staffname' => $request->staffname,
                'transpoform' => $path_transpoform,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Your transportation reimbursements request has been successfully submitted. You can view the status and additional details of your request by navigating to Allowance Requests > Special Allowance.');
        } catch (ValidationException $e) {
            DB::rollBack();
            $errors = $e->errors();
            $errorMessages = '<ul>';
            foreach ($errors as $fieldErrors) {
                foreach ($fieldErrors as $errorMessage) {
                    $errorMessages .= '<li>' . $errorMessage . '</li>';
                }
            }
            $errorMessages .= '</ul>';
            return redirect()->back()->with('failure', 'Your request could not be processed. Please review the following errors: ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('failure', 'Your request could not be processed. Please review the following errors: ' . $e->getMessage());
        };
    }

    public function reqallowanceuniform($caseCode, Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'totalprice' => 'numeric|min:1',
                    'certificate' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'acknowledgement' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'uniformpic' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                    'liquidation' => ['mimes:pdf,jpg,jpeg,png', 'max:2048'],
                ],
                [
                    'totalprice.numeric' => 'The price must be a valid number.',
                    'totalprice.min' => 'The price must be at least 1.',
                    'certificate.mimes' => 'The Enrollment/OJT certificate must be a file of type: pdf, jpg, jpeg, png.',
                    'certificate.max' => 'The Enrollment/OJT certificate file must not exceed 2 MB.',
                    'acknowledgement.mimes' => 'The acknowledgement must be a file of type: pdf, jpg, jpeg, png.',
                    'acknowledgement.max' => 'The acknowledgement file must not exceed 2 MB.',
                    'uniformpic.mimes' => 'The picture of uniform must be a file of type: pdf, jpg, jpeg, png.',
                    'uniformpic.max' => 'The picture of uniform must not exceed 2 MB.',
                    'liquidation.mimes' => 'The liquidation must be a file of type: pdf, jpg, jpeg, png.',
                    'liquidation.max' => 'The liquidation file must not exceed 2 MB.',
                ]
            );

            $certificate = $request->file('certificate');
            $acknowledgement = $request->file('acknowledgement');
            $uniformpic = $request->file('uniformpic');
            $liquidation = $request->file('liquidation');

            $datetime = now()->format('Ymd_His');

            $filename_certificate = $datetime . '_' . $caseCode . '_Title_Page.' . $certificate->getClientOriginalExtension();
            $filename_acknowledgement = $datetime . '_' . $caseCode . '_Acknowledgement_Receipt.' . $acknowledgement->getClientOriginalExtension();
            $filename_uniformpic = $datetime . '_' . $caseCode . '_Proof_of_Purchase.' . $uniformpic->getClientOriginalExtension();
            $filename_liquidation = $datetime . '_' . $caseCode . '_Liquidation_Form.' . $liquidation->getClientOriginalExtension();

            $path_certificate = $certificate->storeAs('uploads/allowance_requests/special/uniform_requests', $filename_certificate, 'public');
            $path_acknowledgement = $acknowledgement->storeAs('uploads/allowance_requests/special/uniform_requests', $filename_acknowledgement, 'public');
            $path_uniformpic = $uniformpic->storeAs('uploads/allowance_requests/special/uniform_requests', $filename_uniformpic, 'public');
            $path_liquidation = $liquidation->storeAs('uploads/allowance_requests/special/uniform_requests', $filename_liquidation, 'public');

            allowanceuniform::create([
                'caseCode' => $caseCode,
                'uniformtype' => $request->uniformtype,
                'totalprice' => $request->totalprice,
                'certificate' => $path_certificate,
                'acknowledgement' => $path_acknowledgement,
                'uniformpic' => $path_uniformpic,
                'liquidation' => $path_liquidation
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Your uniform allowance request has been successfully submitted. You can view the status and additional details of your request by navigating to Allowance Requests > Special Allowance.');
        } catch (ValidationException $e) {
            DB::rollBack();
            $errors = $e->errors();
            $errorMessages = '<ul>';
            foreach ($errors as $fieldErrors) {
                foreach ($fieldErrors as $errorMessage) {
                    $errorMessages .= '<li>' . $errorMessage . '</li>';
                }
            }
            $errorMessages .= '</ul>';
            return redirect()->back()->with('failure', 'Your request could not be processed. Please review the following errors: ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('failure', 'Your request could not be processed. Please review the following errors: ' . $e->getMessage());
        };
    }

    public function showrequestinfo($requesttype, $id)
    {
        $scholar = User::with(['basicInfo', 'education'])
            ->where('id', Auth::id())
            ->first();
        if ($requesttype == 'TRF') {
            $request = allowancetranspo::where('id', $id)->first();
            return view('scholar.allowancerequest.transpoinfo', compact('request', 'scholar'));
        } elseif ($requesttype == 'BAR') {
            $request = allowancebook::where('id', $id)->first();
            return view('scholar.allowancerequest.bookinfo', compact('request', 'scholar'));
        } elseif ($requesttype == 'TAR') {
            $request = allowancethesis::where('id', $id)->first();
            return view('scholar.allowancerequest.thesisinfo', compact('request', 'scholar'));
        } elseif ($requesttype == 'PAR') {
            $request = allowanceproject::where('id', $id)->first();
            return view('scholar.allowancerequest.projectinfo', compact('request', 'scholar'));
        } elseif ($requesttype == 'UAR') {
            $request = allowanceuniform::where('id', $id)->first();
            return view('scholar.allowancerequest.uniforminfo', compact('request', 'scholar'));
        } elseif ($requesttype == 'GAR') {
            $request = allowancegraduation::where('id', $id)->first();
            return view('scholar.allowancerequest.gradinfo', compact('request', 'scholar'));
        } elseif ($requesttype == 'FTTSAR') {
            $request = allowanceevent::where('id', $id)->first();
            return view('scholar.allowancerequest.fieldtripinfo', compact('request', 'scholar'));
        } else {
            return redirect()->back()->with('failure', 'The request could not be found. Please try again, and if the issue persists, contact us at inquiriescholartrack@gmail.com for assistance.');
        }
    }

    public function showappointmentsystem(Request $request)
    {
        $user = Auth::user();
        $status = $request->input('status', 'all');

        if ($status === 'all') {
            $userappointments = Appointments::where('caseCode', $user->caseCode)->get();
        } else {
            $userappointments = Appointments::where('caseCode', $user->caseCode)
                ->where('status', $status)
                ->get();
        }

        $timeoptions = [
            '07:00 AM - 08:00 AM',
            '08:00 AM - 09:00 AM',
            '09:00 AM - 10:00 AM',
            '10:00 AM - 11:00 AM',
            '01:00 PM - 02:00 PM',
            '02:00 PM - 03:00 PM',
            '03:00 PM - 04:00 PM',
            '04:00 PM - 05:00 PM',
        ];

        return view('scholar.appointmentsystem', compact('userappointments', 'user', 'status', 'timeoptions'));
    }

    public function appointmentsfilter(Request $request)
    {
        $date = $request->input('date');
        $timeoptions = [
            '07:00 AM - 08:00 AM',
            '08:00 AM - 09:00 AM',
            '09:00 AM - 10:00 AM',
            '10:00 AM - 11:00 AM',
            '01:00 PM - 02:00 PM',
            '02:00 PM - 03:00 PM',
            '03:00 PM - 04:00 PM',
            '04:00 PM - 05:00 PM',
        ];

        $timeslots = [];

        foreach ($timeoptions as $time) {
            $timeslots[$time] = Appointments::where('status', '!=', 'Cancelled')
                ->where('date', $date)
                ->where('time', $time)
                ->count();
        }

        return response()->json($timeslots);
    }

    public function makeappointment($caseCode, Request $request)
    {
        $recordexists = Appointments::where('caseCode', $caseCode)
            // ->where('reason', $request->reason)
            ->where('date', $request->date)
            ->where('status', '!=', 'Cancelled')
            ->first();

        $formattedDate = \Carbon\Carbon::parse($request->date)->format('F d, Y');
        if ($recordexists) {
            $error_message = "You have already made an appointment for " . strtolower($recordexists->reason) . " on " . $formattedDate;
            return redirect()->route('appointment')->with('failure', $error_message);
        }

        $fullybooked = Appointments::where('status', '!=', 'Cancelled')->where('date', $request->date)->count() === 16;
        if ($fullybooked) {
            $error_message = "We're sorry, but all appointments are booked for " . $formattedDate . ". Please consider selecting an alternative date. Thank you for your patience and understanding.";
            return redirect()->route('appointment')->with('failure', $error_message);
        }

        try {
            DB::beginTransaction();
            // Create a new appointment record
            Appointments::create([
                'caseCode' => $caseCode,
                'reason' => $request->reason,
                'date' => $request->date,
                'time' => $request->time,
                'status' => 'Pending',
                'updatedby' => null,  // Use null, not 'NULL'
            ]);

            DB::commit();

            return redirect()->route('appointment')->with('success', 'Successfully made an appointment. Please wait for approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('appointment')->with('failure', 'Failed to make appointment: ' . $e->getMessage());
        }
    }

    public function cancelappointment($id)
    {
        try {
            DB::beginTransaction();

            // Fetch the appointment model from the database
            $appointment = Appointments::where('id', $id)->first();

            // Check if the appointment actually exists
            if (!$appointment) {
                throw new \Exception('Appointment not found.');
            }

            // Update the status of the appointment
            $appointment->status = 'Cancelled';
            $appointment->save();

            // Commit the transaction
            DB::commit();

            // Redirect with a success message
            return redirect()->back()->with('success', 'Successfully cancelled your appointment.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Redirect with an error message
            return redirect()->back()->with('failure', 'Failed to cancel appointment: ' . $e->getMessage());
        }
    }

    public function showappointmentinfo($id)
    {
        $appointment = Appointments::find($id);
        $user = user::with('basicInfo', 'education')->where('caseCode', $appointment->caseCode)->first();

        return view('scholar.appointmentinfo', compact('appointment', 'user'));
    }
}
