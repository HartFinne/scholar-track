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
use Illuminate\Validation\ValidationException;

class ScholarController extends Controller
{

    public function showHome()
    {
        $user = Auth::user();
        $announcements = Announcement::whereJsonContains('recipients', 'all')
            ->orWhereJsonContains('recipients', $user->caseCode)
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
        $academicData = grades::selectRaw("CONCAT(grades.schoolyear, ' - ', grades.SemesterQuarter) AS period, grades.GWA")
            ->where('grades.caseCode', $user->caseCode) // Filter by user's caseCode
            ->orderBy('grades.schoolyear', 'asc')
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
        $educ = ScEducation::where('caseCode', $user->caseCode)->first(); // Access the caseCode property

        // Fetch grades associated with the education entry
        $grades = grades::where('caseCode', $user->caseCode)->get();

        // Pass the grades and academic year to the view
        return view('scholar/scholarship.gradesub', compact('grades', 'educ'));
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
            $educ = ScEducation::where('caseCode', $user->caseCode)->first();
            $grade = grades::where('caseCode', $user->caseCode)->where('schoolyear', $request->schoolyear)->first();

            // Check if an entry for the same academic year and semester already exists
            $existingGrade = grades::where('caseCode', $user->caseCode)
                ->where('SemesterQuarter', $request->semester)
                ->where('schoolyear', $request->schoolyear)
                ->first();


            if ($existingGrade) {
                return redirect()->back()->withErrors(['error' => 'A grade for this semester in the academic year ' . $grade->schoolyear . ' has already been submitted.'])->withInput();
            }

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
                'caseCode' => $user->caseCode, // Link the grade to the scholar
                'schoolyear' => $educ->scAcademicYear,
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

        // Pass the grade data and academic year to the view
        return view('scholar.scholarship.gradesinfo', compact('grade'));
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

        $classes = humanitiesclass::with(['hcattendance' => function ($query) use ($scholar) {
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

    public function showspecialallowance()
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

        $requests = $mergedrequests->sortBy('created_at')->values();

        return view('scholar.allowancerequest.scspecial', compact('requests', 'scholar'));
    }

    public function showrequestinstruction($requesttype)
    {
        $transpo = specialallowanceforms::where('filetype', 'TRF')->first();
        $cert = specialallowanceforms::where('filetype', 'PBCF')->first();
        $acknowledgement = specialallowanceforms::where('filetype', 'AR')->first();
        $liquidation = specialallowanceforms::where('filetype', 'LF')->first();

        if ($requesttype == 'TRF') {
            if ($transpo == NULL) {
                return redirect()->back()->with('error', 'We apologize, but this special request is currently unavailable. For urgent assistance, please contact one of our social workers for support.');
            }
            return view('scholar.allowancerequest.transporeq', compact('transpo'));
        } elseif ($requesttype == 'BAR') {
            if ($cert == NULL || $acknowledgement == NULL || $liquidation == NULL) {
                return redirect()->back()->with('error', 'We apologize, but this special request is currently unavailable. For urgent assistance, please contact one of our social workers for support.');
            }
            return view('scholar.allowancerequest.bookreq', compact('cert', 'acknowledgement', 'liquidation'));
        } elseif ($requesttype == 'TAR') {
            if ($acknowledgement == NULL || $liquidation == NULL) {
                return redirect()->back()->with('error', 'We apologize, but this special request is currently unavailable. For urgent assistance, please contact one of our social workers for support.');
            }
            return view('scholar.allowancerequest.thesisreq', compact('acknowledgement', 'liquidation'));
        } elseif ($requesttype == 'PAR') {
            if ($cert == NULL || $acknowledgement == NULL || $liquidation == NULL) {
                return redirect()->back()->with('error', 'We apologize, but this special request is currently unavailable. For urgent assistance, please contact one of our social workers for support.');
            }
            return view('scholar.allowancerequest.projectreq', compact('cert', 'acknowledgement', 'liquidation'));
        } elseif ($requesttype == 'UAR') {
            if ($acknowledgement == NULL || $liquidation == NULL) {
                return redirect()->back()->with('error', 'We apologize, but this special request is currently unavailable. For urgent assistance, please contact one of our social workers for support.');
            }
            return view('scholar.allowancerequest.uniformreq', compact('acknowledgement', 'liquidation'));
        } elseif ($requesttype == 'GAR') {
            if ($acknowledgement == NULL || $liquidation == NULL) {
                return redirect()->back()->with('error', 'We apologize, but this special request is currently unavailable. For urgent assistance, please contact one of our social workers for support.');
            }
            return view('scholar.allowancerequest.gradreq', compact('acknowledgement', 'liquidation'));
        } elseif ($requesttype == 'FTTSAR') {
            if ($acknowledgement == NULL || $liquidation == NULL) {
                return redirect()->back()->with('error', 'We apologize, but this special request is currently unavailable. For urgent assistance, please contact one of our social workers for support.');
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
            return redirect()->back()->with('error', 'Your request could not be processed. Please review the following errors: ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Your request could not be processed. Please review the following errors: ' . $e->getMessage());
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
            return redirect()->back()->with('error', 'Your request could not be processed. Please review the following errors: ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Your request could not be processed. Please review the following errors: ' . $e->getMessage());
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
            return redirect()->back()->with('error', 'Your request could not be processed. Please review the following errors: ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Your request could not be processed. Please review the following errors: ' . $e->getMessage());
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
            return redirect()->back()->with('error', 'Your request could not be processed. Please review the following errors: ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Your request could not be processed. Please review the following errors: ' . $e->getMessage());
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
            return redirect()->back()->with('error', 'Your request could not be processed. Please review the following errors: ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Your request could not be processed. Please review the following errors: ' . $e->getMessage());
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
            return redirect()->back()->with('error', 'Your request could not be processed. Please review the following errors: ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Your request could not be processed. Please review the following errors: ' . $e->getMessage());
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
            return redirect()->back()->with('error', 'Your request could not be processed. Please review the following errors: ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Your request could not be processed. Please review the following errors: ' . $e->getMessage());
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
            return redirect()->back()->with('error', 'The request could not be found. Please try again, and if the issue persists, contact us at inquiriescholartrack@gmail.com for assistance.');
        }
    }
}
