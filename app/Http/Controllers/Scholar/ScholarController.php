<?php

namespace App\Http\Controllers\Scholar;

use App\Exports\SpecialAllowanceFormExport;
use Alimranahmed\LaraOCR\Facades\OCR;
use \Intervention\Image\ImageManager;
use \Intervention\Image\Drivers\GD\Driver;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
use App\Models\CreateSpecialAllowanceForm;
use App\Models\renewal;
use App\Models\RnwEducation;
use App\Models\RnwFamilyInfo;
use App\Models\RnwOtherInfo;
use App\Models\scholarshipinfo;
use App\Models\SpecialAllowanceFormStructure;
use App\Models\SpecialAllowanceSummary;
use App\Models\staccount;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\App;


class ScholarController extends Controller
{
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

        $renewals = renewal::with('grade', 'otherinfo', 'casedetails')->where('caseCode', $user->caseCode)->orderBy('datesubmitted', 'DESC')->get();

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

        $appliedRenewal = Renewal::where('caseCode', $user->caseCode)
            ->whereBetween('datesubmitted', [
                $renewal->updated_at->toDateString(),
                $renewal->enddate
            ])
            ->exists();

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

        return view('scholar.scholarship.overview', compact('user', 'penalty', 'chartData', 'communityServiceChart', 'renewal', 'renewals', 'appliedRenewal'));
    }

    public function showrenewalform()
    {
        $user = User::with(['basicInfo', 'education', 'addressInfo', 'clothingSize', 'scholarshipinfo'])
            ->where('id', Auth::id())
            ->first();

        $form = applicationforms::where('formname', 'Renewal')->first();

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

    public function storerenewal(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::with(['basicInfo', 'addressinfo', 'education', 'scholarshipinfo'])
                ->where('id', Auth::id())
                ->first();

            $casecode = $user->caseCode;

            $today = Carbon::now()->format('Y-m-d');

            $sincome = array_sum($request->sincome);

            $levels = [
                'College' => ['First Year', 'Second Year', 'Third Year', 'Fourth Year', 'Fifth Year'],
                'Senior High' => ['Grade 11', 'Grade 12'],
                'Junior High' => ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'],
                'Elementary' => ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'],
            ];

            $schoollevel = null;

            foreach ($levels as $level => $years) {
                if (in_array($request->incomingyear, $years)) {
                    $schoollevel = $level;
                    break;
                }
            }

            $status = 'Pending';

            if ($user->education->scSchoolName != $request->schoolname) {
                $status = 'Changed School';
                $user->scholarshipinfo->scholarshipstatus = 'On-Hold';
                $user->scholarshipinfo->save();
            }

            if ($user->education->scCourseStrandSec != $request->course) {
                $status = 'Changed Course';
                $user->scholarshipinfo->scholarshipstatus = 'On-Hold';
                $user->scholarshipinfo->save();
            }

            $newacadyear = Carbon::now()->year . '-' . Carbon::now()->addYear()->year;

            $prioritylevel = $this->determineprioritylevel($schoollevel, $request->income, $request->fincome, $request->mincome, $sincome, $request->gwa);
            $phoneNumber = $request->input('phonenum');

            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '63' . substr($phoneNumber, 1);
            }

            $request->validate([
                // Applicant info
                'homeaddress' => 'string|max:255',
                'region' => 'string|max:50',
                'city' => 'string|max:50',
                'barangay' => 'string|max:50',
                'email' => 'email|max:255',
                'phonenum' => 'digits_between:11,12',
                'occupation' => 'string|max:100',
                'income' => 'numeric|min:0',
                'fblink' => 'url',
                // Father info
                'fname' => 'string|max:255',
                'fage' => 'integer',
                'fsex' => 'required',
                'fbirthdate' => 'date',
                'freligion' => 'string|max:100',
                'fattainment' => 'string|max:100',
                'foccupation' => 'string|max:100',
                'fcompany' => 'string|max:100',
                'fincome' => 'numeric|min:0',
                // Mother info
                'mname' => 'string|max:255',
                'mage' => 'integer',
                'msex' => 'required',
                'mbirthdate' => 'date',
                'mreligion' => 'string|max:100',
                'mattainment' => 'string|max:100',
                'moccupation' => 'string|max:100',
                'mcompany' => 'string|max:100',
                'mincome' => 'numeric|min:0',
                // Other info
                'grant' => 'string|max:255',
                'talent' => 'string|max:255',
                'expectation' => 'string|max:255',
                // Required documents
                'idpic'          => ['mimes:jpeg,png', 'max:2048'],
                'reportcard'     => ['mimes:jpeg,png,pdf', 'max:2048'],
                'regiform'       => ['mimes:jpeg,png,pdf', 'max:2048'],
                'autobiography'  => ['mimes:pdf', 'max:2048'],
                'familypic'      => ['mimes:jpeg,png', 'max:2048'],
                'insidehouse'    => ['mimes:jpeg,png', 'max:2048'],
                'outsidehouse'   => ['mimes:jpeg,png', 'max:2048'],
                'utility'        => ['mimes:jpeg,png,pdf', 'max:2048'],
                'sketchmap'      => ['mimes:jpeg,png,pdf', 'max:2048'],
                'payslip'        => ['mimes:jpeg,png,pdf', 'max:2048'],
                'indigencycert'  => ['mimes:jpeg,png,pdf', 'max:2048'],
            ], [
                'scholarname.string' => 'The scholar name must be a valid string.',
                'scholarname.max' => 'The scholar name may not be greater than 255 characters.',
                'chinesename.string' => 'The Chinese name must be a valid string.',
                'chinesename.max' => 'The Chinese name may not be greater than 255 characters.',
                'age.integer' => 'The age must be a number.',
                'birthdate.date' => 'The birthdate must be a valid date.',
                'homeaddress.string' => 'The home address must be a valid string.',
                'homeaddress.max' => 'The home address may not be greater than 255 characters.',
                'barangay.string' => 'The barangay must be a valid string.',
                'barangay.max' => 'The barangay may not be greater than 50 characters.',
                'city.string' => 'The city must be a valid string.',
                'city.max' => 'The city may not be greater than 50 characters.',
                'email.email' => 'The email address must be a valid email address.',
                'email.max' => 'The email may not be greater than 255 characters.',
                'phonenum.string' => 'The phone number must be a valid string.',
                'phonenum.max' => 'The phone number may not be longer than 11 characters.',
                'occupation.string' => 'The occupation must be a valid string.',
                'occupation.max' => 'The occupation may not be greater than 100 characters.',
                'income.numeric' => 'The income must be a number.',
                'income.min' => 'The income must be at least 0.',
                'fblink.url' => 'The Facebook link must be a valid URL.',
                'indigenousgroup.required_if' => 'The indigenous group field is required when you are a member of an indigenous group.',
                'indigenousgroup.max' => 'The indigenous group name may not be greater than 100 characters.',
                // Father info
                'fname.string' => 'The father\'s name must be a valid string.',
                'fname.max' => 'The father\'s name may not be greater than 255 characters.',
                'fage.integer' => 'The father\'s age must be a number.',
                'fbirthdate.date' => 'The father\'s birthdate must be a valid date.',
                'freligion.string' => 'The father\'s religion must be a valid string.',
                'freligion.max' => 'The father\'s religion may not be greater than 100 characters.',
                'fattainment.string' => 'The father\'s educational attainment must be a valid string.',
                'fattainment.max' => 'The father\'s educational attainment may not be greater than 100 characters.',
                'foccupation.string' => 'The father\'s occupation must be a valid string.',
                'foccupation.max' => 'The father\'s occupation may not be greater than 100 characters.',
                'fcompany.string' => 'The father\'s company name must be a valid string.',
                'fcompany.max' => 'The father\'s company name may not be greater than 100 characters.',
                'fincome.numeric' => 'The father\'s income must be a number.',
                'fincome.min' => 'The father\'s income must be at least 0.',
                // Mother info
                'mname.string' => 'The mother\'s name must be a valid string.',
                'mname.max' => 'The mother\'s name may not be greater than 255 characters.',
                'mage.integer' => 'The mother\'s age must be a number.',
                'mbirthdate.date' => 'The mother\'s birthdate must be a valid date.',
                'mreligion.string' => 'The mother\'s religion must be a valid string.',
                'mreligion.max' => 'The mother\'s religion may not be greater than 100 characters.',
                'mattainment.string' => 'The mother\'s educational attainment must be a valid string.',
                'mattainment.max' => 'The mother\'s educational attainment may not be greater than 100 characters.',
                'moccupation.string' => 'The mother\'s occupation must be a valid string.',
                'moccupation.max' => 'The mother\'s occupation may not be greater than 100 characters.',
                'mcompany.string' => 'The mother\'s company name must be a valid string.',
                'mcompany.max' => 'The mother\'s company name may not be greater than 100 characters.',
                'mincome.numeric' => 'The mother\'s income must be a number.',
                'mincome.min' => 'The mother\'s income must be at least 0.',
                // Other info
                'grant.string' => 'The grant details must be a valid string.',
                'grant.max' => 'The grant details may not be greater than 255 characters.',
                'talent.string' => 'The talent details must be a valid string.',
                'talent.max' => 'The talent details may not be greater than 255 characters.',
                'expectation.string' => 'The expectations must be a valid string.',
                'expectation.max' => 'The expectations may not be greater than 255 characters.',
                // Custom error messages for documents with file size limits
                'idpic.mimes' => 'The ID picture must be a valid image file (jpeg, jpg, png).',
                'idpic.max' => 'The ID picture must not exceed 2 MB.',

                'reportcard.mimes' => 'The report card must be a valid file (jpeg, jpg, png, or pdf).',
                'reportcard.max' => 'The report card must not exceed 2 MB.',

                'regiform.mimes' => 'The registration form must be a valid file (jpeg, jpg, png, or pdf).',
                'regiform.max' => 'The registration form must not exceed 2 MB.',

                'autobiography.mimes' => 'The autobiography must be a PDF file.',
                'autobiography.max' => 'The autobiography must not exceed 2 MB.',

                'familypic.mimes' => 'The family picture must be a valid image file (jpeg, jpg, or png).',
                'familypic.max' => 'The family picture must not exceed 2 MB.',

                'insidehouse.mimes' => 'The image of the inside of the house must be a valid image file (jpeg, jpg, or png).',
                'insidehouse.max' => 'The image of the inside of the house must not exceed 2 MB.',

                'outsidehouse.mimes' => 'The image of the outside of the house must be a valid image file (jpeg, jpg, or png).',
                'outsidehouse.max' => 'The image of the outside of the house must not exceed 2 MB.',

                'utility.mimes' => 'The utility bill must be a valid file (jpeg, jpg, png, or pdf).',
                'utility.max' => 'The utility bill must not exceed 2 MB.',

                'sketchmap.mimes' => 'The sketch map must be a valid file (jpeg, jpg, png, or pdf).',
                'sketchmap.max' => 'The sketch map must not exceed 2 MB.',

                'payslip.mimes' => 'The payslip must be a valid file (jpeg, jpg, png, or pdf).',
                'payslip.max' => 'The payslip must not exceed 2 MB.',

                'indigencycert.mimes' => 'The indigency certificate must be a valid file (jpeg, jpg, png, or pdf).',
                'indigencycert.max' => 'The indigency certificate must not exceed 2 MB.',
            ]);

            if ($schoollevel != 'College') {
                $request->validate([
                    'incomingyear' => 'required|string|max:15',
                    'schoolname' => 'required|string|max:255',
                    'gwa' => 'required|numeric|min:1|max:100',
                    'gwaconduct' => 'required|string|max:50',
                    'chinesegwa' => 'nullable|numeric|min:1|max:100',
                    'chinesegwaconduct' => 'nullable|string|max:50',
                ], [
                    // Required field validation messages
                    'incomingyear.required' => 'The incoming grade level is required.',
                    'gwa.required' => 'The General Average is required.',
                    'gwaconduct.required' => 'The conduct is required.',

                    // String validation messages
                    'incomingyear.string' => 'The incoming grade level must be a valid string.',
                    'schoolname.string' => 'The school name must be a valid string.',
                    'gwaconduct.string' => 'The conduct must be a valid string.',
                    'chinesegwaconduct.string' => 'The conduct for Chinese subject must be a valid string.',

                    // Max length validation messages
                    'incomingyear.max' => 'The incoming grade level must not exceed 15 characters.',
                    'schoolname.max' => 'The school name must not exceed 255 characters.',
                    'gwaconduct.max' => 'The conduct must not exceed 50 characters.',
                    'chinesegwaconduct.max' => 'The conduct for Chinese subject must not exceed 50 characters.',

                    // Numeric validation messages
                    'gwa.numeric' => 'The General Average must be a valid number.',
                    'gwa.min' => 'The General Average must be at least 1.',
                    'gwa.max' => 'The General Average may not be greater than 100.',

                    'chinesegwa.numeric' => 'The General Average for Chinese subject must be a valid number.',
                    'chinesegwa.min' => 'The General Average for Chinese subject must be at least 1.',
                    'chinesegwa.max' => 'The General Average for Chinese subject may not be greater than 100.',
                ]);

                if ($schoollevel == 'Senior High') {
                    $request->validate([
                        'strand' => 'required_if:schoollevel,Senior High|string|max:100',
                    ], [
                        'strand.max' => 'The strand must not exceed 100 characters.',
                        'strand.required' => 'The strand is required for Senior High.',
                        'strand.string' => 'The strand must be a valid string.',
                    ]);
                } else {
                    $request->validate([
                        'section' => 'required_if:schoollevel,Junior High,Elementary|string|max:50',
                    ], [
                        'section.max' => 'The section must not exceed 50 characters.',
                        'section.required' => 'The section is required for Junior High and Elementary.',
                        'section.string' => 'The section must be a valid string.',
                    ]);
                }
            } else {
                $request->validate([
                    'schoolname' => 'required|string|max:255',
                    'collegedept' => 'required|string|max:255',
                    'incomingyear' => 'required|string|max:15',
                    'course' => 'required|string|max:255',
                    'gwa' => 'required|numeric|min:1|max:5',
                ], [
                    'schoolname.string' => 'The school name must be a valid string.',
                    'schoolname.max' => 'The school name may not be greater than 255 characters.',
                    'collegedept.string' => 'The college department must be a valid string.',
                    'collegedept.max' => 'The college department may not be greater than 255 characters.',
                    'incomingyear.string' => 'The incoming year level must be a valid string.',
                    'course.string' => 'The course must be a valid string.',
                    'course.max' => 'The course may not be greater than 255 characters.',
                    'gwa.numeric' => 'The GWA must be a number.',
                    'gwa.min' => 'The GWA must be at least 1.',
                    'gwa.max' => 'The GWA may not be greater than 5.',
                ]);
            }
            // dd($request->siblingcount);
            // Sibling info
            if ($request->siblingcount > 0) {
                $rules = [];
                $messages = [];

                for ($i = 1; $i <= $request->siblingcount; $i++) {
                    // Base rules
                    $rules["sname[$i]"] = 'nullable|string|max:255';

                    // Other fields are required only if sname[#] is not null
                    $rules["sage[$i]"] = 'required_with:sname[' . $i . ']|integer';
                    $rules["sbirthdate[$i]"] = 'required_with:sname[' . $i . ']|date';
                    $rules["sreligion[$i]"] = 'required_with:sname[' . $i . ']|string|max:100';
                    $rules["sattainment[$i]"] = 'required_with:sname[' . $i . ']|string|max:100';
                    $rules["soccupation[$i]"] = 'required_with:sname[' . $i . ']|string|max:100';
                    $rules["scompany[$i]"] = 'required_with:sname[' . $i . ']|string|max:100';
                    $rules["sincome[$i]"] = 'required_with:sname[' . $i . ']|numeric|min:0';

                    // Custom messages
                    $messages["sname[$i].string"] = 'The name for sibling ' . $i . ' must be a valid string.';
                    $messages["sname[$i].max"] = 'The name for sibling ' . $i . ' may not be greater than 255 characters.';

                    $messages["sage[$i].required_with"] = 'The age for sibling ' . $i . ' is required when the name is provided.';
                    $messages["sage[$i].integer"] = 'The age for sibling ' . $i . ' must be a number.';

                    $messages["sbirthdate[$i].required_with"] = 'The birthdate for sibling ' . $i . ' is required when the name is provided.';
                    $messages["sbirthdate[$i].date"] = 'The birthdate for sibling ' . $i . ' must be a valid date.';

                    $messages["sreligion[$i].required_with"] = 'The religion for sibling ' . $i . ' is required when the name is provided.';
                    $messages["sreligion[$i].string"] = 'The religion for sibling ' . $i . ' must be a valid string.';
                    $messages["sreligion[$i].max"] = 'The religion for sibling ' . $i . ' may not be greater than 100 characters.';

                    $messages["sattainment[$i].required_with"] = 'The educational attainment for sibling ' . $i . ' is required when the name is provided.';
                    $messages["sattainment[$i].string"] = 'The educational attainment for sibling ' . $i . ' must be a valid string.';
                    $messages["sattainment[$i].max"] = 'The educational attainment for sibling ' . $i . ' may not be greater than 100 characters.';

                    $messages["soccupation[$i].required_with"] = 'The occupation for sibling ' . $i . ' is required when the name is provided.';
                    $messages["soccupation[$i].string"] = 'The occupation for sibling ' . $i . ' must be a valid string.';
                    $messages["soccupation[$i].max"] = 'The occupation for sibling ' . $i . ' may not be greater than 100 characters.';

                    $messages["scompany[$i].required_with"] = 'The company name for sibling ' . $i . ' is required when the name is provided.';
                    $messages["scompany[$i].string"] = 'The company name for sibling ' . $i . ' must be a valid string.';
                    $messages["scompany[$i].max"] = 'The company name for sibling ' . $i . ' may not be greater than 100 characters.';

                    $messages["sincome[$i].required_with"] = 'The income for sibling ' . $i . ' is required when the name is provided.';
                    $messages["sincome[$i].numeric"] = 'The income for sibling ' . $i . ' must be a number.';
                    $messages["sincome[$i].min"] = 'The income for sibling ' . $i . ' must be at least 0.';
                }

                $request->validate($rules, $messages);
            }
            // update current data
            $user->addressinfo->scResidential = $request->homeaddress;
            $user->addressinfo->scRegion = $request->region;
            $user->addressinfo->scCity = $request->city;
            $user->addressinfo->scBarangay = $request->barangay;
            $user->addressinfo->save();

            $user->scEmail = $request->email;
            $user->scPhoneNum = $request->phonenum;
            $user->save();

            $user->basicInfo->scOccupation = $request->occupation;
            $user->basicInfo->scIncome = $request->income;
            $user->basicInfo->scFblink = $request->fblink;
            $user->basicInfo->save();

            $user->education->scSchoolLevel = $schoollevel;
            $user->education->scSchoolName = $request->schoolname;
            $user->education->scCollegedept = $request->collegedept ?? NULL;
            $user->education->scYearGrade = $request->incomingyear;
            if ($schoollevel == 'College') {
                $user->education->scCourseStrandSec = $request->course;
            } else if ($schoollevel == 'Senior High') {
                $user->education->scCourseStrandSec = $request->strand;
            } else {
                $user->education->scCourseStrandSec = $request->section;
            }
            $user->education->scAcademicYear = $newacadyear;
            $user->education->save();

            // get the files from form
            $idpic = $request->file('idpic');
            $reportcard = $request->file('reportcard');
            $regiform = $request->file('regiform');
            $autobio = $request->file('autobiography');
            $familypic = $request->file('familypic');
            $houseinside = $request->file('insidehouse');
            $houseoutside = $request->file('outsidehouse');
            $utilitybill = $request->file('utility');
            $sketchmap = $request->file('sketchmap');
            $payslip = $request->file('payslip') ?? NULL;
            $indigencycert = $request->file('indigencycert');
            // Create a custom file name using casecode
            $filename_idpic = $casecode . '_' . 'idpic' . '.' . $idpic->extension();
            $filename_reportcard = $casecode . '_' . 'reportcard' . '.' . $reportcard->extension();
            $filename_regiform = $casecode . '_' . 'regiform' . '.' . $regiform->extension();
            $filename_autobio = $casecode . '_' . 'autobio' . '.' . $autobio->extension();
            $filename_familypic = $casecode . '_' . 'familypic' . '.' . $familypic->extension();
            $filename_houseinside = $casecode . '_' . 'houseinside' . '.' . $houseinside->extension();
            $filename_houseoutside = $casecode . '_' . 'houseoutside' . '.' . $houseoutside->extension();
            $filename_utilitybill = $casecode . '_' . 'utilitybill' . '.' . $utilitybill->extension();
            $filename_sketchmap = $casecode . '_' . 'sketchmap' . '.' . $sketchmap->extension();
            if ($payslip) {
                $filename_payslip = $casecode . '_' . 'payslip' . '.' . $payslip->extension();
            }
            $filename_indigencycert = $casecode . '_' . 'indigencycert' . '.' . $indigencycert->extension();
            // Store the file in the specified directory
            $path_idpic = $idpic->storeAs('uploads/renewal_requirements/id_pics', $filename_idpic, 'public');
            $path_reportcard = $reportcard->storeAs('uploads/renewal_requirements/report_cards', $filename_reportcard, 'public');
            $path_regiform = $regiform->storeAs('uploads/renewal_requirements/registration_forms', $filename_regiform, 'public');
            $path_autobio = $autobio->storeAs('uploads/renewal_requirements/autobiographies', $filename_autobio, 'public');
            $path_familypic = $familypic->storeAs('uploads/renewal_requirements/family_pics', $filename_familypic, 'public');
            $path_houseinside = $houseinside->storeAs('uploads/renewal_requirements/house_inside', $filename_houseinside, 'public');
            $path_houseoutside = $houseoutside->storeAs('uploads/renewal_requirements/house_outside', $filename_houseoutside, 'public');
            $path_utilitybill = $utilitybill->storeAs('uploads/renewal_requirements/utility_bills', $filename_utilitybill, 'public');
            $path_sketchmap = $sketchmap->storeAs('uploads/renewal_requirements/sketch_maps', $filename_sketchmap, 'public');
            if ($payslip) {
                $path_payslip = $payslip->storeAs('uploads/renewal_requirements/payslips', $filename_payslip, 'public');
            }
            $path_indigencycert = $indigencycert->storeAs('uploads/renewal_requirements/indigency_certs', $filename_indigencycert, 'public');

            $renewal = renewal::create([
                'caseCode' => $casecode,
                'datesubmitted' => $today,
                'idpic' => $path_idpic,
                'reportcard' => $path_reportcard,
                'regicard' => $path_regiform,
                'autobio' => $path_autobio,
                'familypic' => $path_familypic,
                'houseinside' => $path_houseinside,
                'houseoutside' => $path_houseoutside,
                'utilitybill' => $path_utilitybill,
                'sketchmap' => $path_sketchmap,
                'payslip' => $path_payslip ?? NULL,
                'indigencycert' => $path_indigencycert,
                'status' => $status,
                'prioritylevel' => $prioritylevel
            ]);

            RnwEducation::create([
                'rid' => $renewal->rid,
                'schoolyear' => $newacadyear,
                'gwa' =>  $request->gwa ?? $request->genave,
                'gwaconduct' => $request->gwaconduct ?? NULL,
                'chinsegwa' => $request->chinsegenave ?? NULL,
                'chinsegwaconduct' => $request->chinsegwaconduct ?? NULL,
            ]);

            // Father and mother info
            RnwFamilyInfo::create([
                'caseCode' => $casecode,
                'name' => $request->fname,
                'age' => $request->fage,
                'sex' => $request->fsex,
                'birthdate' => $request->fbirthdate,
                'relationship' => 'Father',
                'religion' => $request->freligion,
                'educattainment' => $request->fattainment,
                'occupation' => $request->foccupation,
                'company' => $request->fcompany,
                'income' => $request->fincome,
            ]);

            RnwFamilyInfo::create([
                'caseCode' => $casecode,
                'name' => $request->mname,
                'age' => $request->mage,
                'sex' => $request->msex,
                'birthdate' => $request->mbirthdate,
                'relationship' => 'Mother',
                'religion' => $request->mreligion,
                'educattainment' => $request->mattainment,
                'occupation' => $request->moccupation,
                'company' => $request->mcompany,
                'income' => $request->mincome,
            ]);

            if (!empty($request->siblingcount) && $request->siblingcount > 0) {
                foreach ($request->sname as $index => $name) {
                    // Skip index 0
                    if ($index == 0) {
                        continue;
                    }

                    if (is_null($request->sname[$index])) {
                        continue;
                    }

                    // Create new sibling record starting from index 1
                    RnwFamilyInfo::create([
                        'caseCode' => $casecode,
                        'name' => $name,
                        'age' => $request->sage[$index],
                        'sex' => $request->ssex[$index],
                        'birthdate' => $request->sbirthdate[$index],
                        'relationship' => 'Sibling',
                        'religion' => $request->sreligion[$index],
                        'educattainment' => $request->sattainment[$index],
                        'occupation' => $request->soccupation[$index],
                        'company' => $request->scompany[$index],
                        'income' => $request->sincome[$index],
                    ]);
                }
            }

            RnwOtherInfo::create([
                'rid' => $renewal->rid,
                'grant' => $request->grant,
                'talent' => $request->talent,
                'expectation' => $request->expectation,
            ]);

            DB::commit();

            return redirect()->route('showRenewForm', $renewal->rid);
        } catch (ValidationException $e) {
            DB::rollback();
            $errors = $e->errors();
            $errorMessages = '<ul>';
            foreach ($errors as $fieldErrors) {
                foreach ($fieldErrors as $errorMessage) {
                    $errorMessages .= '<li>' . $errorMessage . '</li>';
                }
            }
            $errorMessages .= '</ul>';
            return redirect()->back()->with('failure', 'Your application has failed due to the following errors: ' . $errorMessages)->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('failure', 'Sorry, your application could not be processed at this time. Please try again later or contact support if the problem persists. ' . $e->getMessage())->withInput();
        }
    }

    public function determineprioritylevel($schoollevel, $income, $fincome, $mincome, $sincome, $gwa)
    {
        $criteria = criteria::first();
        $prioritylevel = 0;
        if ($fincome <= $criteria->fincome) {
            $fincomelvl = 1;
        } else {
            $fincomelvl = 0;
        }

        if ($mincome <= $criteria->mincome) {
            $mincomelvl = 1;
        } else {
            $mincomelvl = 0;
        }

        if ($sincome <= $criteria->sincome) {
            $sincomelvl = 1;
        } else {
            $sincomelvl = 0;
        }

        if ($income <= $criteria->aincome) {
            $incomelvl = 1;
        } else {
            $incomelvl = 0;
        }

        if ($schoollevel == 'College') {
            if ($gwa <= $criteria->cgwa) {
                $cgwalvl = 1;
            } else {
                $cgwalvl = 0;
            }
            $prioritylevel = $fincomelvl + $mincomelvl + $sincomelvl + $incomelvl + $cgwalvl;
        } elseif ($schoollevel == 'Senior High') {
            if ($gwa <= $criteria->cgwa) {
                $shsgwalvl = 1;
            } else {
                $shsgwalvl = 0;
            }
            $prioritylevel = $fincomelvl + $mincomelvl + $sincomelvl + $incomelvl + $shsgwalvl;
        } elseif ($schoollevel == 'Junior High') {
            if ($gwa <= $criteria->cgwa) {
                $jhsgwalvl = 1;
            } else {
                $jhsgwalvl = 0;
            }
            $prioritylevel = $fincomelvl + $mincomelvl + $sincomelvl + $incomelvl + $jhsgwalvl;
        } elseif ($schoollevel == 'Elementary') {
            if ($gwa <= $criteria->cgwa) {
                $elemgwalvl = 1;
            } else {
                $elemgwalvl = 0;
            }
            $prioritylevel = $fincomelvl + $mincomelvl + $sincomelvl + $incomelvl + $elemgwalvl;
        }
        return $prioritylevel;
    }

    public function showRenewForm($id)
    {
        $user = User::with([
            'basicInfo',
            'addressinfo',
            'education',
            'scholarshipinfo'
        ])->where('id', Auth::id())
            ->first();

        $iscollege = ScEducation::where('scSchoolLevel', 'College')->where('caseCode', $user->caseCode)->exists();

        $renewal = renewal::with('grade', 'casedetails', 'otherinfo')->where('rid', $id)->first();

        $father = RnwFamilyInfo::where('caseCode', $user->caseCode)->where('relationship', 'Father')->first();
        $mother = RnwFamilyInfo::where('caseCode', $user->caseCode)->where('relationship', 'Mother')->first();
        $siblings = RnwFamilyInfo::where('caseCode', $user->caseCode)->where('relationship', 'Sibling')->get();

        $form = applicationforms::where('formname', 'Renewal')->first();

        return view('scholar.scholarship.subrenewal', compact('user', 'father', 'mother', 'siblings', 'form', 'renewal', 'iscollege'));
    }

    public function showGradeSubmission(Request $request)
    {
        // Retrieve the currently authenticated user's caseCode
        $user = User::with(['basicInfo', 'education', 'scholarshipinfo'])
            ->where('id', Auth::id())
            ->first();
        $institution = institutions::where('schoolname', $user->education->scSchoolName)
            ->where('schoollevel', $user->education->scSchoolLevel)
            ->first();

        // dd($institution);

        $status = $request->input('status', 'all');

        // Fetch grades based on the filter status
        $grades = grades::where('caseCode', $user->caseCode)
            ->when($status !== 'all', function ($query) use ($status) {
                return $query->where('GradeStatus', $status);
            })
            ->get();

        $term = grades::where('caseCode', $user->caseCode)
            ->when($status !== 'all', function ($query) use ($status) {
                return $query->where('GradeStatus', $status);
            })
            ->where('schoolyear', $user->education->scAcademicYear)
            ->orderBy('SemesterQuarter', 'desc')
            ->pluck('SemesterQuarter')
            ->first();

        if ($institution->academiccycle != "Quarter") {
            if ($term) {
                if ($term == '1st Semester') {
                    $term = '2nd Semester';
                } else if ($term == '2nd Semester' && $institution->academiccycle == 'Trimester') {
                    $term = '3rd Semester';
                } else if ($term == '2nd Semester' && $institution->academiccycle == 'Semester') {
                    $term = '1st Semester';
                }
            } else {
                $term = '1st Semester';
            }
        } else {
            $term = "4th Quarter";
        }

        return view('scholar/scholarship.gradesub', compact('user', 'grades', 'status', 'institution', 'term'));
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
                    'gradeImage' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:10240']
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
                // return redirect()->back()->with('failure', 'Grade submission failed. If the problem continues, please contact one of our social welfare officers for assistance.')->withInput();
                return redirect()->back()->with('failure', 'A grade for this semester in the academic year ' . $educ->scAcademicYear . ' has already been submitted.')->withInput();
            }

            // Handle file upload
            if ($request->hasFile('gradeImage')) {
                $file = $request->file('gradeImage');
                $fileName = $user->caseCode . '_' . $user->basicInfo->scLastname . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('uploads/grade_reports', $fileName, 'public');
                $originalFilePath = storage_path('app/public/' . $filePath);

                try {
                    $apiKey = 'K83084843288957';  // Replace with your actual API key
                    $ocrSpaceUrl = 'https://api.ocr.space/parse/image';

                    // Make API request to extract text from the image
                    $response = Http::withHeaders([
                        'apikey' => $apiKey,  // Send the API key in the header
                    ])->attach(
                        'file',
                        file_get_contents($originalFilePath),
                        basename($originalFilePath)
                    )->post($ocrSpaceUrl, [
                        'filetype' => 'jpg',  // Set the correct file type
                        'OCREngine' => 2,
                        'isTable' => 'true',   // Use the advanced OCR engine
                    ]);

                    // Log the full OCR response for debugging
                    Log::info('OCR API Response:', $response->json());

                    // Check if the OCR API request was successful
                    if (!$response->successful()) {
                        // Log and throw an error if the API request fails
                        Log::error('OCR API failed with response: ', $response->json());
                        throw new \Exception('OCR API failed: ' . $response->json('ErrorMessage') ?? 'Unknown error');
                    }

                    // Extract the OCR text from the response
                    $ocrText = $response->json('ParsedResults')[0]['ParsedText'] ?? '';

                    if (empty($ocrText)) {
                        throw new \Exception('OCR could not extract text from the image.');
                    }

                    // Save the OCR output to a .txt file for debugging purposes
                    $txtFilePath = storage_path('app/public/uploads/grade_reports/' . $fileName . '_ocr_output.txt');
                    file_put_contents($txtFilePath, $ocrText);

                    // Extract GPA from the OCR text
                    $patterns = [
                        '/General Average[^0-9]*([\d.]+)/i',
                        '/General Average[^0-9]*([\d.]+)/i',
                        '/Average[^0-9]*([\d.]+)/i',
                        '/GPA[^0-9]*([\d.]+)/i',
                        '/GWA[^0-9]*([\d.]+)/i',
                        '/Grade Point Average[^0-9]*([\d.]+)/i',
                    ];

                    $ocrGpa = null;
                    foreach ($patterns as $pattern) {
                        if (preg_match($pattern, $ocrText, $matches)) {
                            $ocrGpa = floatval($matches[1]);
                            break;
                        }
                    }

                    if ($ocrGpa === null) {
                        return redirect()->back()->with('failure', 'Could not extract GPA from the uploaded document.')->withInput();
                    }

                    // Compare the extracted GPA with the user input
                    $inputGpa = $request->gwa ?? $request->genave;
                    if (abs($ocrGpa - $inputGpa) > 0.01) {
                        return redirect()->back()->with('failure', 'The GPA in the document (' . $ocrGpa . ') does not match the input GPA (' . $inputGpa . ').')->withInput();
                    }

                    // return redirect()->back()->with('success', 'OCR processed successfully. Results saved.')->withInput();
                } catch (\Exception $e) {
                    return redirect()->back()->with('failure', 'An error occurred: ' . $e->getMessage())->withInput();
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

            Log::info('User Data:', ['user' => $user]);
            Log::info('Basic Info:', ['basicInfo' => $user->basicInfo]);
            Log::info('Education Data:', ['educ' => $educ]);
            Log::info('Grading System:', ['gradingsystem' => $gradingsystem]);


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

            $failedGWAcount = grades::where('caseCode', $user->caseCode)
                ->where('GradeStatus', 'Failed GWA')->count();
            $scinfo = scholarshipinfo::where('caseCode', $user->caseCode)->first();

            if ($gradeStatus == 'Failed GWA' && $failedGWAcount < 3) {
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
            } else if ($gradeStatus == 'Failed GWA' && $failedGWAcount >= 3) {
                $scinfo->scholarshipstatus = 'Terminated';
                $scinfo->save();

                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();
                DB::commit();
                return redirect()->route('scholar-login')->with('failure', 'Your scholarship has been terminated due to the accumulation of 3 failed GWAs. Consequently, your account has been deactivated. If you believe this is a mistake, please contact our Social Welfare Officer for further assistance.');
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
            'explanation_' . time() . '.' . $request->file('explanation')->extension()
        );

        // Initialize the reason file path variable
        $reasonFilePath = null;

        // Depending on the selected reason, store the related file in the specified directory
        switch ($request->input('reason')) {
            case 'Medical':
                if ($request->hasFile('medical-file')) {
                    $reasonFilePath = $request->file('medical-file')->storeAs(
                        'public/' . $directoryPath,
                        'medical_' . time() . '.' . $request->file('medical-file')->extension()
                    );
                }
                break;
            case 'Academic Activity':
                if ($request->hasFile('academic-file')) {
                    $reasonFilePath = $request->file('academic-file')->storeAs(
                        'public/' . $directoryPath,
                        'academic_' . time() . '.' . $request->file('academic-file')->extension()
                    );
                }
                break;
            case 'Death of an Immediate Family Member':
                if ($request->hasFile('death-file')) {
                    $reasonFilePath = $request->file('death-file')->storeAs(
                        'public/' . $directoryPath,
                        'death_' . time() . '.' . $request->file('death-file')->extension()
                    );
                }
                break;
            case 'Natural and Human induced disasters':
                if ($request->hasFile('disaster-file')) {
                    $reasonFilePath = $request->file('disaster-file')->storeAs(
                        'public/' . $directoryPath,
                        'disaster_' . time() . '.' . $request->file('disaster-file')->extension()
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
        $fileExtensionExplanation = pathinfo($letter->explanation, PATHINFO_EXTENSION);
        $fileExtensionProof = pathinfo($letter->proof, PATHINFO_EXTENSION);

        return view('scholar.scholarship.sublteinfo', compact('letter', 'fileExtensionExplanation', 'fileExtensionProof', 'concerncsregistration', 'concernhcattendance'));
    }

    public function showspecialallowance(Request $request)
    {
        // Get the currently authenticated user and their education data
        $scholar = User::with('education')
            ->where('id', Auth::id())
            ->first();

        // Get the caseCode and scSchoolLevel
        $caseCode = $scholar->caseCode;
        $scSchoolLevel = $scholar->education->scSchoolLevel;

        // Retrieve the forms based on the scSchoolLevel
        // Initialize the $data array to store form data
        $data = [];

        $forms = CreateSpecialAllowanceForm::whereJsonContains('requestor', $scSchoolLevel)->get();

        foreach ($forms as $form) {
            $formname = $form->formname;
            $filePath = $form->database;

            // Check if the file exists in the public disk
            if (!Storage::disk('public')->exists($filePath)) {
                // Stop the process and return with failure message if file doesn't exist
                return view('scholar.allowancerequest.scspecial', compact('scholar', 'forms'))->with('failure', 'File Not Found.');
            }

            // Read the Excel file from the public disk
            $data[$formname] = Excel::toArray([], Storage::disk('public')->path($filePath));

            // The first row contains the column headers
            $headers = $data[$formname][0][0];

            // Find the index of the 'caseCode' column
            $caseCodeIndex = array_search('caseCode', $headers);

            // Filter the data by caseCode
            $filteredData = array_filter($data[$formname][0], function ($row) use ($caseCode, $caseCodeIndex) {
                return isset($row[$caseCodeIndex]) && $row[$caseCodeIndex] == $caseCode;
            });

            // Optional: Reset array keys after filtering (if you want the keys to start from 0)
            $filteredData = array_values($filteredData);

            // Map the filtered data to headers (replace numeric keys with headers)
            $mappedData = array_map(function ($row) use ($headers) {
                // Combine each row with headers as keys
                return array_combine($headers, $row);
            }, $filteredData);

            // You can store the mapped data in a separate array if you need to keep it
            $data[$formname] = $mappedData;
            // dd($data[$formname]);
        }
        return view('scholar.allowancerequest.scspecial', compact('scholar', 'forms', 'data'));

        // $status = $request->input('status', 'all');

        // if ($status !== 'all') {
        //     $requests = $mergedrequests->where('status', $status)->sortBy('created_at')->values();
        // } else {
        //     $requests = $mergedrequests->sortBy('created_at')->values();
        // }

        // Return the filtered data (passing scholar, forms, and filtered data to the view)
    }

    public function showrequestinstruction($requesttype)
    {
        $user = User::with(['basicInfo', 'education', 'scholarshipinfo'])
            ->find(Auth::id());

        $form = CreateSpecialAllowanceForm::where('csafid', $requesttype)->first();

        $fields = SpecialAllowanceFormStructure::where('csafid', $requesttype)->get();

        $downloadableFiles = json_decode($form->downloadablefiles, true);

        $files = specialallowanceforms::whereIn('id', $downloadableFiles)->get();
        return view('scholar.allowancerequest.specialallowanceform', compact('form', 'files', 'user', 'fields'));
    }

    public function requestSpecialAllowance($requesttype, Request $request)
    {
        try {
            DB::beginTransaction();
            // dd($request->all());
            $user = User::with(['basicInfo', 'education', 'scholarshipinfo'])
                ->find(Auth::id());

            $caseCode = $user->caseCode;

            $now = now();
            $formattedNow = $now->format('Ymd');

            $form = CreateSpecialAllowanceForm::where('csafid', $requesttype)->first();
            $database = $form->database;

            $fields = SpecialAllowanceFormStructure::where('csafid', $requesttype)->get();

            $requestType = $form->formname;
            $requestDate = today()->toDateString();
            $releaseDate = NULL;
            $requestStatus = 'Pending';

            $records[] = $caseCode;
            $records[] = $requestType;
            $records[] = $requestDate;
            $records[] = $releaseDate;
            $records[] = $requestStatus;

            // Validate required fields
            foreach ($fields as $field) {
                $rules = 'required';
                $fieldNameFormatted = str_replace(' ', '_', $field->fieldname);

                if ($field->fieldtype == 'text') {
                    $rules .= '|string|max:255';
                } elseif ($field->fieldtype == 'number') {
                    $rules .= '|numeric|min:1';
                } elseif ($field->fieldtype == 'file') {
                    $rules .= '|mimes:pdf,jpg,jpeg,png,zip|max:10240';
                }

                // Corrected validation method
                $request->validate([
                    $fieldNameFormatted => $rules // Corrected the assignment operator here
                ]);

                // Save inputs to array
                if ($field->fieldtype == 'file') {
                    $file = $request->file($fieldNameFormatted);
                    $directoryPath = "uploads/allowance_requests/special/{$field->fieldname}";
                    // Make sure the directory exists
                    if (!Storage::exists($directoryPath)) {
                        Storage::makeDirectory($directoryPath);
                    }
                    $filename = $caseCode . '_' . $formattedNow .  $file->extension();
                    $records[] = $file->storeAs($directoryPath, $filename, 'public');
                } else {
                    $records[] = $request->input($fieldNameFormatted);
                }
            }

            $downloadableFiles = json_decode($form->downloadablefiles, true);
            $files = specialallowanceforms::whereIn('id', $downloadableFiles)->get();

            // Validate required files
            foreach ($files as $file) {
                $rules = 'required|mimes:pdf|max:2560';
                $fileNameFormatted = str_replace(' ', '_', $file->filename);

                // Corrected validation method
                $request->validate([
                    $fileNameFormatted => $rules // Corrected the assignment operator here as well
                ]);

                // Save uploaded files
                $uploadedfile = $request->file($fileNameFormatted);

                $directoryPath = "uploads/allowance_requests/special/{$file->filename}";

                // Make sure the directory exists
                if (!Storage::exists($directoryPath)) {
                    Storage::makeDirectory($directoryPath);
                }

                $filename = $caseCode . '_' . $formattedNow . '.' . $uploadedfile->extension();

                $records[] = $uploadedfile->storeAs($directoryPath, $filename, 'public');
            }

            // dd($records);

            $this->storeSpecialRequest($database, $records);

            $specreqsummary = SpecialAllowanceSummary::first();
            $specreqsummary->totalrequests++;
            $specreqsummary->pending++;
            $specreqsummary->save();
            DB::commit();
            return redirect()->back()->with('success', "Your {$form->formname} has been successfully submitted. You can view the status and additional details of your request by navigating to Allowance Requests > Special Allowance.");
        } catch (\Exception $e) {
            DB::rollBack();

            // Return error message if an exception occurs
            return redirect()->back()->with('failure', 'Your request could not be processed. Please review the following errors: ' . $e->getMessage())->withInput();
        }
    }

    private function storeSpecialRequest($filePath, $records)
    {
        // Get the full file path inside the storage directory
        $fullFilePath = Storage::disk('public')->path($filePath);

        // Check if the file exists
        if (!Storage::disk('public')->exists($filePath)) {
            throw new \Exception("File not found: " . basename($fullFilePath));
        }

        // Load the spreadsheet
        $spreadsheet = IOFactory::load($fullFilePath);

        // Get the active sheet
        $sheet = $spreadsheet->getActiveSheet();

        // Find the next available row (assuming data starts from row 2)
        $row = $sheet->getHighestRow() + 1;

        // Calculate the new ID
        if ($row == 2) {
            $id = 1;
        } else {
            $cell = 'A' . $sheet->getHighestRow();
            $id = $sheet->getCell($cell)->getValue() + 1;
        }

        // Set the ID in column A of the next row
        $sheet->setCellValue(chr(65) . $row, $id);

        // Loop through the records and insert them into the next row
        $count = count($records);
        for ($i = 0; $i < $count; $i++) {
            // dd(chr(65 + $i + 1) . $row, $records[$i]);

            $sheet->setCellValue(chr(65 + $i + 1) . $row, $records[$i]);
        }

        // Save the updated Excel file back to the original file path
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($fullFilePath);  // Save to the full file path

        return;
    }

    public function specialRequestInfo($type, $id)
    {
        // Get the currently authenticated user and their related data
        $scholar = User::with(['basicInfo', 'education', 'scholarshipinfo'])
            ->where('id', Auth::id())
            ->first();

        // Retrieve the form based on the type
        $form = CreateSpecialAllowanceForm::where('formname', $type)->first();

        // Get the file path from the form's database column
        $filePath = $form->database;

        // Check if the file exists in the public disk
        if (!Storage::disk('public')->exists($filePath)) {
            return view('scholar.allowancerequest.scspecial', compact('scholar', 'form'))->with('failure', 'File Not Found.');
        }

        // Read the Excel file from the public disk
        $data = Excel::toArray([], Storage::disk('public')->path($filePath));

        // The first row contains the column headers
        $headers = $data[0][0];

        // Find the index of the 'id' column
        $idIndex = array_search('id', $headers);

        // Filter the data by id and return only the first matching record
        $filteredData = array_filter($data[0], function ($row) use ($id, $idIndex) {
            return isset($row[$idIndex]) && $row[$idIndex] == $id;
        });

        // Optional: Reset array keys after filtering (if you want the keys to start from 0)
        $filteredData = array_values($filteredData);

        // Get the first record from the filtered data
        $record = array_shift($filteredData);

        // Map the record to headers (replace numeric keys with headers)
        $data = array_combine($headers, $record);

        $fields = SpecialAllowanceFormStructure::where('csafid', $form->csafid)->get();

        $filesId = json_decode($form->downloadablefiles);

        $files = specialallowanceforms::whereIn('id', $filesId)->get();
        // dd($data);

        // Return the view with the single record and related data
        return view('scholar.allowancerequest.specialinfo', compact('data', 'scholar', 'form', 'fields', 'files'));
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
        $user = User::with('basicInfo', 'education')->where('caseCode', $appointment->caseCode)->first();

        return view('scholar.appointmentinfo', compact('appointment', 'user'));
    }
}
