<?php

namespace App\Http\Controllers;

use App\Http\Middleware\applicant;
use Illuminate\Http\Request;
use App\Models\staccount;
use App\Models\User;
use App\Models\communityservice;
use App\Models\csregistration;
use App\Models\csattendance;
use App\Models\humanitiesclass;
use App\Models\hcattendance;
use App\Models\lte;
use App\Models\penalty;
use App\Models\renewal;
use App\Models\ScEducation;
use App\Models\scholarshipinfo;
use App\Models\grades;
use App\Models\criteria;
use App\Models\institutions;
use App\Models\courses;
use App\Models\applicants;
use App\Models\apceducation;
use App\Models\apeheducation;
use App\Models\apfamilyinfo;
use App\Models\specialallowanceforms;
use App\Models\allowancebook;
use App\Models\allowanceevent;
use App\Models\allowancegraduation;
use App\Models\allowanceproject;
use App\Models\allowancethesis;
use App\Models\allowancetranspo;
use App\Models\allowanceuniform;
use App\Models\Email;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\UsesTrait;
use App\Imports\EmailsImport;
use App\Models\RegularAllowance;
use App\Notifications\LteAnnouncementCreated;
use App\Notifications\PenaltyNotification;
use App\Notifications\RegularAllowanceNotification;
use App\Notifications\SpecialAllowancesNotification;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class StaffController extends Controller
{
    public function showAccountSW()
    {
        $worker = Auth::guard('staff')->user();
        return view('staff.profile-socialworker', compact('worker'));
    }

    public function showAccountSA()
    {
        $worker = Auth::guard('staff')->user();
        return view('staff.profile-admin', compact('worker'));
    }

    public function showApplicants()
    {
        $totalapplicants = applicants::get()->count();
        $applicants = applicants::get();
        $pending = applicants::whereNotIn('applicationstatus', ['Accepted', 'Rejected', 'Withdrawn'])->count();
        $accepted = applicants::where('applicationstatus', 'Accepted')->count();
        $rejected = applicants::where('applicationstatus', 'Rejected')->count();
        $withdrawn = applicants::where('applicationstatus', 'Withdrawn')->count();
        $college = apceducation::get()->count();
        $shs = apeheducation::whereIN('ingrade', ['Grade 11', 'Grade 12'])->get()->count();
        $jhs = apeheducation::whereIN('ingrade', ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])->get()->count();
        $elem = apeheducation::whereIN('ingrade', ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'])->get()->count();
        return view('staff.applicants', compact('totalapplicants', 'applicants', 'pending', 'accepted', 'rejected', 'withdrawn', 'college', 'shs', 'jhs', 'elem'));
    }

    public function showapplicantinfo($casecode)
    {
        $applicant = applicants::with('educcollege', 'educelemhs', 'otherinfo', 'requirements', 'casedetails')
            ->where('casecode', $casecode)
            ->first();
        $father = apfamilyinfo::where('casecode', $casecode)
            ->where('relationship', 'Father')->first();
        $mother = apfamilyinfo::where('casecode', $casecode)
            ->where('relationship', 'Mother')->first();
        $siblings = apfamilyinfo::where('casecode', $casecode)
            ->where('relationship', 'Sibling')->get();
        $iscollege = apceducation::where('casecode', $casecode)->first()->exists();

        return view('staff.applicant-info', compact('applicant', 'father', 'mother', 'siblings', 'iscollege'));
    }

    public function showApplicationForms()
    {
        return view('staff.applicationforms');
    }

    public function showScholarsCollege()
    {
        $scholars = User::with(['basicInfo', 'education', 'scholarshipinfo'])
            ->whereHas('education', function ($query) {
                $query->where('scSchoolLevel', 'College');
            })
            ->get();

        // Define academic year range based on a scholar's scholarship info (assuming each scholar may have a different range)
        foreach ($scholars as $scholar) {
            $acadyearend = $scholar->scholarshipinfo->enddate;  // Ensure `enddate` is retrieved correctly
            $acadyearstart = date('Y-m-d', strtotime('-1 year', strtotime($acadyearend)));  // Subtract one year from end date

            // Latest GWA based on `caseCode`
            $scholar->latestgwa = DB::table('grades')
                ->where('caseCode', $scholar->caseCode)
                ->orderBy('schoolyear', 'desc')
                ->value('gwa');

            // Total Community Service Hours within academic year
            $scholar->totalcshours = csattendance::where('caseCode', $scholar->caseCode)
                ->whereHas('communityservice', function ($query) use ($acadyearstart, $acadyearend) {
                    $query->whereBetween('eventdate', [$acadyearstart, $acadyearend]);
                })
                ->sum('hoursspent');

            // Total Humanities Class Attendance within academic year
            $scholar->totalhcattendance = hcattendance::where('caseCode', $scholar->caseCode)
                ->whereHas('humanitiesclass', function ($query) use ($acadyearstart, $acadyearend) {
                    $query->whereBetween('hcdate', [$acadyearstart, $acadyearend]);
                })
                ->count();

            // Total Penalties
            $scholar->penaltycount = penalty::where('caseCode', $scholar->caseCode)->count();
        }

        // Count humanities events within the academic year range
        $hcevents = humanitiesclass::whereBetween('hcdate', [$acadyearstart, $acadyearend])->count();

        return view('staff.listcollege', compact('scholars', 'hcevents'));
    }

    public function showScholarsElem()
    {
        $scholars = User::with(['basicInfo', 'education', 'scholarshipinfo'])
            ->whereHas('education', function ($query) {
                $query->where('scSchoolLevel', 'Elementary');
            })
            ->get();

        // Define academic year range based on a scholar's scholarship info (assuming each scholar may have a different range)
        foreach ($scholars as $scholar) {
            $acadyearend = $scholar->scholarshipinfo->enddate;  // Ensure `enddate` is retrieved correctly
            $acadyearstart = date('Y-m-d', strtotime('-1 year', strtotime($acadyearend)));  // Subtract one year from end date

            // Latest GWA based on `caseCode`
            $scholar->latestgwa = DB::table('grades')
                ->where('caseCode', $scholar->caseCode)
                ->orderBy('schoolyear', 'desc')
                ->value('gwa');

            // Total Humanities Class Attendance within academic year
            $scholar->totalhcattendance = hcattendance::where('caseCode', $scholar->caseCode)
                ->whereHas('humanitiesclass', function ($query) use ($acadyearstart, $acadyearend) {
                    $query->whereBetween('hcdate', [$acadyearstart, $acadyearend]);
                })
                ->count();

            // Total Penalties
            $scholar->penaltycount = penalty::where('caseCode', $scholar->caseCode)->count();
        }

        // Count humanities events within the academic year range
        $hcevents = humanitiesclass::whereBetween('hcdate', [$acadyearstart, $acadyearend])->count();

        return view('staff.listelementary', compact('scholars', 'hcevents'));
    }

    public function showScholarsHS()
    {
        $scholars = User::with(['basicInfo', 'education', 'scholarshipinfo'])
            ->whereHas('education', function ($query) {
                $query->whereIn('scSchoolLevel', ['Junior High', 'Senior High']);
            })
            ->get();

        // Define academic year range based on a scholar's scholarship info (assuming each scholar may have a different range)
        foreach ($scholars as $scholar) {
            $acadyearend = $scholar->scholarshipinfo->enddate;  // Ensure `enddate` is retrieved correctly
            $acadyearstart = date('Y-m-d', strtotime('-1 year', strtotime($acadyearend)));  // Subtract one year from end date

            // Latest GWA based on `caseCode`
            $scholar->latestgwa = DB::table('grades')
                ->where('caseCode', $scholar->caseCode)
                ->orderBy('schoolyear', 'desc')
                ->value('gwa');

            // Total Humanities Class Attendance within academic year
            $scholar->totalhcattendance = hcattendance::where('caseCode', $scholar->caseCode)
                ->whereHas('humanitiesclass', function ($query) use ($acadyearstart, $acadyearend) {
                    $query->whereBetween('hcdate', [$acadyearstart, $acadyearend]);
                })
                ->count();

            // Total Penalties
            $scholar->penaltycount = penalty::where('caseCode', $scholar->caseCode)->count();
        }

        // Count humanities events within the academic year range
        $hcevents = humanitiesclass::whereBetween('hcdate', [$acadyearstart, $acadyearend])->count();
        return view('staff.listhighschool', compact('scholars', 'hcevents'));
    }

    public function showScholarProfile($id)
    {
        // personal info
        $data = User::with(['basicInfo', 'education', 'addressInfo'])->findOrFail($id);

        // grades info
        $grades = grades::where('caseCode', $data->caseCode)->get();

        // cs info
        $csattendances = csattendance::with('communityservice')
            ->where('caseCode', $data->caseCode)->get();

        // hc info
        $hcattendances = hcattendance::with('humanitiesclass')
            ->where('caseCode', $data->caseCode)->get();

        return view('staff.scholarsinfo', compact('data', 'grades', 'csattendances', 'hcattendances'));
    }

    public function  updatescholarshipstatus($caseCode, Request $request)
    {
        DB::beginTransaction();
        try {
            $scholar = scholarshipinfo::where('caseCode', $caseCode)->first();
            $scholar->scholarshipstatus = $request->scholarshipstatus;
            $scholar->save();
            DB::commit();
            return redirect()->back()->with('success', 'Successfully updated scholarship status.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update scholarship status. ' . $e->getMessage());
        }
    }

    public function showgradesinfo($gid)
    {
        $grade = grades::where('gid', $gid)->first();
        $scholar = user::with('basicInfo')->where('caseCode', $grade->caseCode)->first();

        return view('staff.gradesinfo', compact('grade', 'scholar'));
    }

    public function updategradestatus($gid, Request $request)
    {
        DB::beginTransaction();
        try {
            $worker = Auth::guard('staff')->user();
            $grade = Grades::where('gid', $gid)->first();

            // Update the grade status
            $grade->GradeStatus = $request->gradestatus;
            $grade->save();

            // Check if the grade status is neither "Passed" nor "Pending"
            if ($request->gradestatus != 'Passed' && $request->gradestatus != 'Pending') {
                lte::create([
                    'caseCode' => $grade->caseCode,
                    'violation' => $request->gradestatus,
                    'conditionid' => null,
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
            }

            // Commit the transaction
            DB::commit();
            return redirect()->back()->with('success', 'Successfully updated grade status.');
        } catch (\Exception $e) {
            // Roll back the transaction in case of error
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update grade status: ' . $e->getMessage());
        }
    }

    public function showLTE()
    {
        $lte = lte::with('hcattendance', 'csattendance')->get();
        $scholars = User::with(['basicInfo'])->get();
        return view('staff.lte', compact('lte', 'scholars'));
    }

    public function showPenalty()
    {
        $penalty = penalty::all();
        $scholars = User::with(['basicInfo'])->get();

        $penalties = [];
        foreach ($scholars as $scholar) {
            $latestPenalty = penalty::where('caseCode', $scholar->caseCode)
                ->latest('dateofpenalty')
                ->first();
            $penalties[$scholar->caseCode] = $latestPenalty;
        }
        return view('staff.penalty', compact('penalties', 'scholars', 'penalty'));
    }

    public function storePenalty(Request $request)
    {
        $validatedData = $request->validate([
            'scholar_id' => 'required|string|exists:users,caseCode', // Ensures the scholar ID exists in the users table
            'condition' => 'required|string|in:Lost Cash Card,Dress Code Violation', // Restrict to specific values
            'remark' => 'required|string|in:1st Offense,2nd Offense,3rd Offense,4th Offense', // Restrict to specific values
            'date' => 'required|date', // Ensure the date is a valid date format
        ]);

        // Check if a penalty already exists for the given scholar ID and condition
        $penalty = penalty::where('caseCode', $validatedData['scholar_id'])
            ->first();

        if ($penalty) {
            // Map the offense levels for comparison
            $offenseLevels = [
                '1st Offense' => 1,
                '2nd Offense' => 2,
                '3rd Offense' => 3,
                '4th Offense' => 4,
            ];

            // Check if the new remark is greater than the current remark
            if ($offenseLevels[$validatedData['remark']] <= $offenseLevels[$penalty->remark]) {
                return redirect()->route('penalty')->with('failure', 'The remark must be greater than the current remark level.');
            }

            // Update the existing penalty record
            $penalty->update([
                'condition' => $validatedData['condition'],
                'remark' => $validatedData['remark'],
                'dateofpenalty' => $validatedData['date'],
            ]);
        } else {
            // If no penalty exists, create a new record
            $penalty = penalty::create([
                'caseCode' => $validatedData['scholar_id'],
                'condition' => $validatedData['condition'],
                'remark' => $validatedData['remark'],
                'dateofpenalty' => $validatedData['date'],
            ]);
        }

        // Prepare notification settings
        $api_key = config('services.movider.api_key');
        $api_secret = config('services.movider.api_secret');
        Log::info('Movider API Key', ['api_key' => $api_key, 'api_secret' => $api_secret]);

        $user = User::where('caseCode', $validatedData['scholar_id'])->first();

        $client = new \GuzzleHttp\Client();
        $failedSMS = [];
        $failedEmail = [];
        $message = "Your penalty has been updated: " . $validatedData['condition'] . " (" . $validatedData['remark'] . ")";

        // Send notification based on user preference
        if ($user->notification_preference === 'sms') {
            try {
                $response = $client->post('https://api.movider.co/v1/sms', [
                    'form_params' => [
                        'api_key' => $api_key,
                        'api_secret' => $api_secret,
                        'to' => $user->scPhoneNum,
                        'text' => $message,
                    ],
                ]);

                $decodedResponse = json_decode($response->getBody()->getContents(), true);
                Log::info('Movider SMS Response', ['response_body' => $decodedResponse]);

                if (!isset($decodedResponse['phone_number_list']) || !is_array($decodedResponse['phone_number_list']) || count($decodedResponse['phone_number_list']) == 0) {
                    $failedSMS[] = $user->scPhoneNum; // Track failed SMS
                }
            } catch (\Exception $e) {
                $failedSMS[] = $user->scPhoneNum;
                Log::error('Movider SMS Exception', ['error' => $e->getMessage()]);
            }
        } else {
            try {
                $user->notify(new PenaltyNotification($penalty));
            } catch (\Exception $e) {
                $failedEmail[] = $user->email;
                Log::error('Email Notification Error', ['error' => $e->getMessage()]);
            }
        }

        // Determine the message based on failed notifications
        if (empty($failedSMS) && empty($failedEmail)) {
            return redirect()->route('penalty')->with('success', 'Penalty recorded and notification sent successfully.');
        } else {
            $failureDetails = "";
            if (!empty($failedSMS)) {
                $failureDetails .= " SMS failed for: " . implode(", ", $failedSMS) . ".";
            }
            if (!empty($failedEmail)) {
                $failureDetails .= " Email failed for: " . implode(", ", $failedEmail) . ".";
            }
            return redirect()->route('penalty')->with('failure', 'Penalty recorded, but some notifications failed.' . $failureDetails);
        }
    }


    // SCHOLARSHIP CRITERIA
    public function showQualification()
    {
        $criteria = criteria::first();
        $courses = courses::where('level', 'College')->get();
        $strands = courses::where('level', 'Senior High')->get();
        $institutions = institutions::all();
        return view('staff.qualification', compact('criteria', 'institutions', 'courses', 'strands'));
    }

    public function updatecriteria(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'cshours' => 'required|numeric|min:1',
                    'cgwa' => 'required|numeric|min:1|max:5',
                    'shsgwa' => 'required|numeric|min:1|max:100',
                    'jhsgwa' => 'required|numeric|min:1|max:100',
                    'elemgwa' => 'required|numeric|min:1|max:100',
                    'fincome' => 'required|numeric|min:0',
                    'mincome' => 'required|numeric|min:0',
                    'sincome' => 'required|numeric|min:0',
                    'aincome' => 'required|numeric|min:0',
                ],
                [
                    'cshours.min' => 'The required community service hours must not be less than 1.',
                    'cgwa.min' => 'Please input a valid GWA in college.',
                    'cgwa.max' => 'Please input a valid GWA in college.',
                    'shsgwa.min' => 'Please input a valid General Average in Senior High.',
                    'shsgwa.max' => 'Please input a valid General Average in Senior High.',
                    'jhsgwa.min' => 'Please input a valid General Average in Junior High.',
                    'jhsgwa.max' => 'Please input a valid General Average in Junior High.',
                    'elemgwa.min' => 'Please input a valid General Average in Elementary.',
                    'elemgwa.max' => 'Please input a valid General Average in Elementary.',
                ]
            );

            $criteria = criteria::first();

            if (is_null($criteria)) {
                criteria::create([
                    'cshours' => $request->cshours,
                    'cgwa' => $request->cgwa,
                    'shsgwa' => $request->shsgwa,
                    'jhsgwa' => $request->jhsgwa,
                    'elemgwa' => $request->elemgwa,
                    'fincome' => $request->fincome,
                    'mincome' => $request->mincome,
                    'sincome' => $request->sincome,
                    'aincome' => $request->aincome,
                ]);
            } else {
                $criteria->update([
                    'cshours' => $request->cshours,
                    'cgwa' => $request->cgwa,
                    'shsgwa' => $request->shsgwa,
                    'jhsgwa' => $request->jhsgwa,
                    'elemgwa' => $request->elemgwa,
                    'fincome' => $request->fincome,
                    'mincome' => $request->mincome,
                    'sincome' => $request->sincome,
                    'aincome' => $request->aincome,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('critsuccess', 'Successfully updated scholarship requirements');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('criterror', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('criterror', 'Unable to update scholarship requirements.');
        }
    }

    public function addinstitution(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'institute' => 'required|string|max:255',
            ]);

            institutions::create([
                'schoolname' => $request->institute,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Successfully added an institution.')->withFragment('confirmmsg2');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();

            if (institutions::where('schoolname', $request->institute)->exists()) {
                return redirect()->back()->with('error', 'Failed to add institution. Duplicate institution.')->withFragment('confirmmsg2');
            }

            return redirect()->back()->with('error', 'Failed to add institution.')->withFragment('confirmmsg2');
        }
    }

    public function updateinstitution($inid, Request $request)
    {
        $request->validate([
            'newschoolname' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $institution = institutions::findOrFail($inid);

            $institution->update([
                'schoolname' => $request->newschoolname
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Successfully updated the institution name.')->withFragment('confirmmsg2');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();

            if (institutions::where('schoolname', $request->institute)->exists()) {
                return redirect()->back()->with('error', 'Failed to update institution name. Duplicate institution.')->withFragment('confirmmsg2');
            }

            return redirect()->back()->with('error', 'Failed to update institution name.')->withFragment('confirmmsg2');
        }
    }

    public function deleteinstitution($inid)
    {
        DB::beginTransaction();
        try {
            $institution = institutions::findOrFail($inid);

            $institution->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Successfully deleted the institution.')->withFragment('confirmmsg2');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to delete institution.')->withFragment('confirmmsg2');
        }
    }

    public function addcourse($level, Request $request)
    {
        DB::beginTransaction();
        if ($level == 'College') {
            try {
                $request->validate([
                    'course' => 'required|string|max:255',
                ]);

                courses::create([
                    'level' => $level,
                    'coursename' => $request->course
                ]);

                DB::commit();

                return redirect()->back()->with('success', 'Successfully added a course.')->withFragment('confirmmsg2');
            } catch (ValidationException $e) {
                DB::rollback();
                return redirect()->back()->with('error', $e->getMessage());
            } catch (\Exception $e) {
                DB::rollback();

                if (courses::where('coursename', $request->course)->exists()) {
                    return redirect()->back()->with('error', 'Failed to add course. Duplicate course.')->withFragment('confirmmsg2');
                }

                return redirect()->back()->with('error', 'Failed to add course.')->withFragment('confirmmsg2');
            }
        } elseif ($level == 'Senior High') {
            try {
                $request->validate([
                    'strand' => 'required|string|max:255',
                ]);

                courses::create([
                    'level' => $level,
                    'coursename' => $request->strand
                ]);

                DB::commit();

                return redirect()->back()->with('success', 'Successfully added a strand.')->withFragment('confirmmsg2');
            } catch (ValidationException $e) {
                DB::rollback();
                return redirect()->back()->with('error', $e->getMessage());
            } catch (\Exception $e) {
                DB::rollback();

                if (courses::where('coursename', $request->strand)->exists()) {
                    return redirect()->back()->with('error', 'Failed to add strand. Duplicate strand.')->withFragment('confirmmsg2');
                }

                return redirect()->back()->with('error', 'Failed to add strand.')->withFragment('confirmmsg2');
            }
        }
    }

    public function updatecourse($coid, Request $request)
    {
        $request->validate([
            'newcoursename' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $course = courses::findOrFail($coid);

            $type = '';

            if ($course->level == 'College') {
                $type = 'course';
            } else {
                $type = 'strand';
            }

            $course->update([
                'coursename' => $request->newcoursename
            ]);

            DB::commit();
            return redirect()->back()->with('success', "Successfully updated {$type}.")->withFragment('confirmmsg2');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();

            if (courses::where('coursename', $request->newcoursename)->exists()) {
                return redirect()->back()->with('error', "Failed to update {$type}. Duplicate {$type}.")->withFragment('confirmmsg2');
            }

            return redirect()->back()->with('error', "Failed to update {$type}.")->withFragment('confirmmsg2');
        }
    }

    public function deletecourse($coid)
    {
        DB::beginTransaction();
        try {
            $course = courses::findOrFail($coid);

            $type = '';

            if ($course->level == 'College') {
                $type = 'course';
            } else {
                $type = 'strand';
            }

            $course->delete();

            DB::commit();
            return redirect()->back()->with('success', "Successfully deleted {$type}.")->withFragment('confirmmsg2');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', "Failed to delete {$type}.")->withFragment('confirmmsg2');
        }
    }

    public function showRenewal()
    {
        $totalrenew = renewal::all()->count();
        $pending = renewal::where('status', 'Pending')->count();
        $approved = renewal::where('status', 'Approved')->count();
        $rejected = renewal::where('status', 'Rejected')->count();
        return view('staff.renewal', compact('totalrenew', 'pending', 'approved', 'rejected'));
    }

    public function showRenewalCollege()
    {
        $scholars = User::with('education', 'basicInfo');
        $renewals = renewal::all();
        return view('staff.renewcollege', compact('renewals', 'scholars'));
    }

    public function showRenewalElem()
    {
        $scholars = User::with('education', 'basicInfo');
        $renewals = renewal::all();
        return view('staff.renewelementary', compact('renewals', 'scholars'));
    }

    public function showRenewalHS()
    {
        $scholars = User::with('education', 'basicInfo');
        $renewals = renewal::all();
        return view('staff.renewhighschool', compact('renewals', 'scholars'));
    }

    public function showAllowanceRegular()
    {
        $requests = RegularAllowance::join('grades', 'grades.gid', '=', 'regular_allowance.gid')
            ->join('users', 'users.caseCode', '=', 'grades.caseCode')
            ->join('sc_basicInfo', 'sc_basicInfo.caseCode', '=', 'users.caseCode') // Joining with sc_basicInfo using user_id
            ->get();
        return view('staff.regularallowance', compact('requests'));
    }

    public function viewAllowanceRegularInfo($id)
    {

        $requests = RegularAllowance::join('grades', 'grades.gid', '=', 'regular_allowance.gid')
            ->join('users', 'users.caseCode', '=', 'grades.caseCode')
            ->join('sc_basicInfo', 'sc_basicInfo.caseCode', '=', 'users.caseCode') // Join basic info
            ->join('scholarshipinfo', 'scholarshipinfo.caseCode', '=', 'users.caseCode') // Join scholarship info
            ->join('sc_education', 'sc_education.caseCode', '=', 'users.caseCode') // Join education info
            ->where('regular_allowance.regularID', $id) // Filter by specific RegularAllowance ID
            ->firstOrFail(); // Retrieve single record or fail if not found


        // Retrieve the regular allowance request by ID with related information
        $regularAllowance = RegularAllowance::with([
            'classReference.classSchedules',
            'travelItinerary.travelLocations',
            'lodgingInfo',
            'ojtTravelItinerary.ojtLocations'
        ])->findOrFail($id);

        return view('staff.regularallowanceinfo', compact('id', 'requests', 'regularAllowance'));
    }


    public function updateRegularAllowance(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $req = RegularAllowance::join('grades', 'grades.gid', '=', 'regular_allowance.gid')
                ->join('users', 'users.caseCode', '=', 'grades.caseCode')
                ->where('regular_allowance.regularID', $id)
                ->firstOrFail();

            if ($request->releasedate == NULL) {
                $req->status = $request->status;
            } else {
                $req->status = $request->status;
                $req->date_of_release = $request->releasedate;
            }

            $req->save();
            DB::commit();

            // Prepare notification settings
            $api_key = config('services.movider.api_key');
            $api_secret = config('services.movider.api_secret');
            Log::info('Movider API Key', ['api_key' => $api_key, 'api_secret' => $api_secret]);

            $user = User::where('caseCode', $req->caseCode)->first();

            $client = new \GuzzleHttp\Client();
            $failedSMS = [];
            $failedEmail = [];
            $message = "Your request has been updated: " . $req->status;

            // Send notification based on user preference
            if ($user->notification_preference === 'sms') {
                // Send SMS using the Movider API
                try {
                    $response = $client->post('https://api.movider.co/v1/sms', [
                        'form_params' => [
                            'api_key' => $api_key,
                            'api_secret' => $api_secret,
                            'to' => $user->scPhoneNum,
                            'text' => $message,
                        ],
                    ]);

                    $decodedResponse = json_decode($response->getBody()->getContents(), true);
                    if (!isset($decodedResponse['phone_number_list']) || !is_array($decodedResponse['phone_number_list']) || count($decodedResponse['phone_number_list']) == 0) {
                        $failedSMS[] = $user->scPhoneNum;
                    }
                } catch (\Exception $e) {
                    $failedSMS[] = $user->scPhoneNum;
                    Log::error('Movider SMS Exception', ['error' => $e->getMessage()]);
                }
            } else {
                // Send email notification
                try {
                    $user->notify(new RegularAllowanceNotification($req));
                } catch (\Exception $e) {
                    $failedEmail[] = $user->email;
                    Log::error('Email Notification Error', ['error' => $e->getMessage()]);
                }
            }

            if (empty($failedSMS) && empty($failedEmail)) {
                return redirect()->back()->with('success', 'Regular Allowance Request has been updated.');
            } else {
                $failureDetails = "";
                if (!empty($failedSMS)) {
                    $failureDetails .= " SMS failed for: " . implode(", ", $failedSMS) . ".";
                }
                if (!empty($failedEmail)) {
                    $failureDetails .= " Email failed for: " . implode(", ", $failedEmail) . ".";
                }
                return redirect()->back()->with('failure', 'Regular Allowances recorded, but some notifications failed.' . $failureDetails);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Unable to update request. ' . $e->getMessage());
        }
    }



    public function showAllowanceSpecial()
    {
        // Define an array of the models to simplify access
        $allowanceModels = [
            allowancebook::class,
            allowanceevent::class,
            allowancethesis::class,
            allowanceproject::class,
            allowancetranspo::class,
            allowanceuniform::class,
            allowancegraduation::class
        ];

        $statuses = ['pending', 'accepted', 'completed', 'rejected'];
        $data = [];

        foreach ($statuses as $status) {
            $count = 0;
            foreach ($allowanceModels as $model) {
                // Directly count only records with the specific status using where condition
                $count += $model::where('status', $status)->count();
            }
            $data[$status] = $count;
        }

        // Calculate the total count of all statuses
        $data['total'] = array_sum($data);

        // Define an array of model classes
        $allowanceModels = [
            allowancebook::class,
            allowanceevent::class,
            allowancethesis::class,
            allowanceproject::class,
            allowancetranspo::class,
            allowanceuniform::class,
            allowancegraduation::class
        ];

        // Initialize an empty collection to store all results
        $mergedRequests = collect();

        // Loop through each model class, retrieve and merge the results
        foreach ($allowanceModels as $model) {
            // Retrieve records already ordered by created_at and add to the merged collection
            $records = $model::orderBy('created_at', 'asc')->get();
            $mergedRequests = $mergedRequests->concat($records);
        }

        // Define a custom order for statuses
        $statusOrder = ['Pending', 'Accepted', 'Completed', 'Rejected'];

        // Sort the merged collection by status first, then by created_at
        $requests = $mergedRequests->sort(function ($a, $b) use ($statusOrder) {
            // Compare status by predefined priority
            $statusComparison = array_search($a->status, $statusOrder) <=> array_search($b->status, $statusOrder);
            if ($statusComparison == 0) { // if statuses are the same, sort by created_at
                return $a->created_at <=> $b->created_at;
            }
            return $statusComparison;
        })->values();

        // Ensure keys are reset
        $requests = $requests->values();

        return view('staff.specialallowance', compact('data', 'requests'));
    }

    public function showspecrecinfo($requesttype, $id)
    {
        if ($requesttype == 'TRF') {
            $request = allowancetranspo::where('id', $id)->first();
            $reqtype = 'transpoinfo';
        } elseif ($requesttype == 'BAR') {
            $request = allowancebook::where('id', $id)->first();
            $reqtype = 'bookinfo';
        } elseif ($requesttype == 'TAR') {
            $request = allowancethesis::where('id', $id)->first();
            $reqtype = 'thesisinfo';
        } elseif ($requesttype == 'PAR') {
            $request = allowanceproject::where('id', $id)->first();
            $reqtype = 'projectinfo';
        } elseif ($requesttype == 'UAR') {
            $request = allowanceuniform::where('id', $id)->first();
            $reqtype = 'uniforminfo';
        } elseif ($requesttype == 'GAR') {
            $request = allowancegraduation::where('id', $id)->first();
            $reqtype = 'gradinfo';
        } elseif ($requesttype == 'FTTSAR') {
            $request = allowanceevent::where('id', $id)->first();
            $reqtype = 'fieldtripinfo';
        } else {
            return redirect()->back()->with('error', 'The request could not be found. Please try again, and if the issue persists, contact us at inquiriescholartrack@gmail.com for assistance.');
        }

        $scholar = User::with(['basicInfo', 'education'])
            ->where('caseCode', $request->caseCode)
            ->first();

        return view("staff.specialreqs.{$reqtype}", compact('request', 'scholar'));
    }

    public function updatespecreq($requesttype, $id, Request $request)
    {
        DB::beginTransaction();
        try {
            if ($requesttype == 'TRF') {
                $requestname = 'Transportation Reimbursement Request';
                $req = allowancetranspo::where('id', $id)->first();
            } elseif ($requesttype == 'BAR') {
                $requestname = 'Book Allowance Request';
                $req = allowancebook::where('id', $id)->first();
            } elseif ($requesttype == 'TAR') {
                $requestname = 'Thesis Allowance Request';
                $req = allowancethesis::where('id', $id)->first();
            } elseif ($requesttype == 'PAR') {
                $requestname = 'Project Allowance Request';
                $req = allowanceproject::where('id', $id)->first();
            } elseif ($requesttype == 'UAR') {
                $requestname = 'Uniform Allowance Request';
                $req = allowanceuniform::where('id', $id)->first();
            } elseif ($requesttype == 'GAR') {
                $requestname = 'Graduation Allowance Request';
                $req = allowancegraduation::where('id', $id)->first();
            } elseif ($requesttype == 'FTTSAR') {
                $requestname = 'Field Trip, Training, Seminar Allowance Request';
                $req = allowanceevent::where('id', $id)->first();
            } else {
                return redirect()->back()->with('error', 'The request could not be found. Please try again, and if the issue persists, contact us at inquiriescholartrack@gmail.com for assistance.');
            }

            if ($request->releasedate == NULL) {
                $req->status = $request->status;
            } else {
                $req->status = $request->status;
                $req->releasedate = $request->releasedate;
            }

            $req->save();
            DB::commit();

            // Prepare notification settings
            $api_key = config('services.movider.api_key');
            $api_secret = config('services.movider.api_secret');
            Log::info('Movider API Key', ['api_key' => $api_key, 'api_secret' => $api_secret]);

            $user = User::where('caseCode', $req->caseCode)->first();

            $client = new \GuzzleHttp\Client();
            $failedSMS = [];
            $failedEmail = [];
            $message = "Your request has been updated: " . $req->status;

            // Send notification based on user preference
            if ($user->notification_preference === 'sms') {
                // Send SMS using the Movider API
                try {
                    $response = $client->post('https://api.movider.co/v1/sms', [
                        'form_params' => [
                            'api_key' => $api_key,
                            'api_secret' => $api_secret,
                            'to' => $user->scPhoneNum,
                            'text' => $message,
                        ],
                    ]);

                    $decodedResponse = json_decode($response->getBody()->getContents(), true);
                    if (!isset($decodedResponse['phone_number_list']) || !is_array($decodedResponse['phone_number_list']) || count($decodedResponse['phone_number_list']) == 0) {
                        $failedSMS[] = $user->scPhoneNum;
                    }
                } catch (\Exception $e) {
                    $failedSMS[] = $user->scPhoneNum;
                    Log::error('Movider SMS Exception', ['error' => $e->getMessage()]);
                }
            } else {
                // Send email notification
                try {
                    $user->notify(new SpecialAllowancesNotification($req, $requestname));
                } catch (\Exception $e) {
                    $failedEmail[] = $user->email;
                    Log::error('Email Notification Error', ['error' => $e->getMessage()]);
                }
            }

            if (empty($failedSMS) && empty($failedEmail)) {
                return redirect()->back()->with('success', 'Allowance Request has been updated.');
            } else {
                $failureDetails = "";
                if (!empty($failedSMS)) {
                    $failureDetails .= " SMS failed for: " . implode(", ", $failedSMS) . ".";
                }
                if (!empty($failedEmail)) {
                    $failureDetails .= " Email failed for: " . implode(", ", $failedEmail) . ".";
                }
                return redirect()->back()->with('failure', 'Special Allowances recorded, but some notifications failed.' . $failureDetails);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Unable to update request. ' . $e->getMessage());
        }
    }

    public function updatetransporeimbursenment(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'transporeimbursement' => ['mimes:doc,docx,pdf', 'max:2048'],
                ],
                [
                    'transporeimbursement.mimes' => 'The transportation reimbursement form must be a valid file (doc, docx, or pdf).',
                    'transporeimbursement.max' => 'The transportation reimbursement form must not exceed 2 MB.',
                ]
            );

            $uploadedfile = $request->file('transporeimbursement');

            $filename = 'Transportation Reimbursement Form.' . $uploadedfile->getClientOriginalExtension();

            $path = $uploadedfile->storeAs('uploads/allowance_forms/special', $filename, 'public');

            $filetype = 'TRF';

            $fileexists = specialallowanceforms::where('filetype', $filetype)->first();

            if ($fileexists) {
                $fileexists->pathname = $path;
                $fileexists->save();
            } else {
                specialallowanceforms::create([
                    'filetype' => $filetype,
                    'pathname' => $path,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'File has been successfully uploaded.');
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
            return redirect()->back()->with('error', 'Unable to update file. ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Unable to update file. ' . $e->getMessage());
        };
    }

    public function updateacknowledgementreceipt(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'acknowledgementreceipt' => ['mimes:doc,docx,pdf', 'max:2048'],
                ],
                [
                    'acknowledgementreceipt.mimes' => 'The acknowledgement receipt must be a valid file (doc, docx, or pdf).',
                    'acknowledgementreceipt.max' => 'The acknowledgement receipt must not exceed 2 MB.',
                ]
            );

            $uploadedfile = $request->file('acknowledgementreceipt');

            $filename = 'Acknowledgement Receipt.' . $uploadedfile->getClientOriginalExtension();

            $path = $uploadedfile->storeAs('uploads/allowance_forms/special', $filename, 'public');

            $filetype = 'AR';

            $fileexists = specialallowanceforms::where('filetype', $filetype)->first();

            if ($fileexists) {
                $fileexists->pathname = $path;
                $fileexists->save();
            } else {
                specialallowanceforms::create([
                    'filetype' => $filetype,
                    'pathname' => $path,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'File has been successfully uploaded.');
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
            return redirect()->back()->with('error', 'Unable to update file. ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Unable to update file. ' . $e->getMessage());
        };
    }

    public function updateliquidationform(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'liquidationform' => ['mimes:doc,docx,pdf', 'max:2048'],
                    'certificationform' => ['mimes:doc,docx,pdf', 'max:2048'],
                ],
                [
                    'liquidationform.mimes' => 'The liquidation form must be a valid file (doc, docx, or pdf).',
                    'liquidationform.max' => 'The liquidation form must not exceed 2 MB.',
                ]
            );

            $uploadedfile = $request->file('liquidationform');

            $filename = 'Liquidation Form.' . $uploadedfile->getClientOriginalExtension();

            $path = $uploadedfile->storeAs('uploads/allowance_forms/special', $filename, 'public');

            $filetype = 'LF';

            $fileexists = specialallowanceforms::where('filetype', $filetype)->first();

            if ($fileexists) {
                $fileexists->pathname = $path;
                $fileexists->save();
            } else {
                specialallowanceforms::create([
                    'filetype' => $filetype,
                    'pathname' => $path,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'File has been successfully uploaded.');
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
            return redirect()->back()->with('error', 'Unable to update file. ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Unable to update file. ' . $e->getMessage());
        };
    }

    public function updatecertificationform(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'certificationform' => ['mimes:doc,docx,pdf', 'max:2048'],
                ],
                [
                    'certificationform.mimes' => 'The certification form must be a valid file (doc, docx, or pdf).',
                    'certificationform.max' => 'The certification form must not exceed 2 MB.',
                ]
            );

            $uploadedfile = $request->file('certificationform');

            $filename = 'Project and Book Certification Form.' . $uploadedfile->getClientOriginalExtension();

            $path = $uploadedfile->storeAs('uploads/allowance_forms/special', $filename, 'public');

            $filetype = 'PBCF';

            $fileexists = specialallowanceforms::where('filetype', $filetype)->first();

            if ($fileexists) {
                $fileexists->pathname = $path;
                $fileexists->save();
            } else {
                specialallowanceforms::create([
                    'filetype' => $filetype,
                    'pathname' => $path,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'File has been successfully uploaded.');
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
            return redirect()->back()->with('error', 'Unable to update file. ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Unable to update file. ' . $e->getMessage());
        };
    }

    public function showScholarsoverview()
    {
        $totalscholars = User::count();

        $totalnewscholars = scholarshipinfo::where('scholartype', 'New Scholar')->count();

        $areas = scholarshipinfo::selectRaw('area')->distinct()->pluck('area');
        $scholarsperarea = [];
        foreach ($areas as $area) {
            $scholarsperarea[$area] = scholarshipinfo::where('area', $area)->count();
        }

        $levels = ScEducation::selectRaw('scSchoolLevel')->distinct()->pluck('scSchoolLevel');
        $scholarsperlevel = [];
        foreach ($levels as $level) {
            $scholarsperlevel[$level] = ScEducation::where('scSchoolLevel', $level)->count();
        }

        return view('staff.scholars', compact(
            'totalscholars',
            'totalnewscholars',
            'areas',
            'scholarsperarea',
            'scholarsperlevel'
        ));
    }

    public function showUsersScholar()
    {
        $scholarAccounts = User::all();

        return view('staff.users-scholar', compact('scholarAccounts'));
    }

    public function showUserApplicants()
    {
        $applicants = applicants::all();

        return view('staff.users-applicant', compact('applicants'));
    }

    public function showUserStaff()
    {
        $staffAccounts = Staccount::all();

        return view('staff.users-staff', compact('staffAccounts'));
    }

    public function showDashboard()
    {
        $worker = Auth::guard('staff')->user();
        $totalscholar = user::all()->count();
        $totalstaff = staccount::all()->count();
        $totalapplicant = applicants::all()->count();
        $totalusers = $totalapplicant + $totalstaff + $totalscholar;

        return view('staff.dashboard-admin', compact('totalscholar', 'totalstaff', 'totalapplicant', 'totalusers', 'worker'));
    }

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

    public function activateapplicant($apid)
    {
        $user = applicants::findOrFail($apid);
        $user->accountstatus = 'Active';
        $user->save();

        return redirect()->back()->with('success', 'User activated successfully.');
    }

    public function deactivateapplicant($apid)
    {
        $user = applicants::findOrFail($apid);
        $user->accountstatus = 'Inactive';
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
        $user = Staccount::findOrFail($id);

        return view('staff.admstaffinfo', compact('user'));
    }

    public function showScholarInfo($id)
    {
        $user = User::findOrFail($id);

        return view('staff.admscholarinfo', compact('user'));
    }

    public function showapplicantaccount($apid)
    {
        $user = applicants::findOrFail($apid);

        return view('staff.admapplicantinfo', compact('user'));
    }

    public function showCommunityService()
    {
        communityservice::where('slotnum', 0)
            ->where('eventstatus', '!=', 'Closed')
            ->update(['eventstatus' => 'Closed']);
        $events = communityservice::all();
        $totalevents = communityservice::count();
        $openevents = communityservice::where('eventstatus', 'Open')->count();
        $closedevents = communityservice::where('eventstatus', 'Closed')->count();

        $requiredHours = 8;

        $scholarsWithCompletedHours = DB::table('csattendance')
            ->select('caseCode', DB::raw('SUM(hoursspent) as total_hours'))
            ->groupBy('caseCode')
            ->having('total_hours', '>=', $requiredHours)
            ->count();

        $scholarsWithRemainingHours = DB::table('csattendance')
            ->select('caseCode', DB::raw('SUM(hoursspent) as total_hours'))
            ->groupBy('caseCode')
            ->having('total_hours', '<', $requiredHours)
            ->count();

        return view('staff.managecs', compact('events', 'totalevents', 'openevents', 'closedevents', 'scholarsWithCompletedHours', 'scholarsWithRemainingHours'));
    }

    public function showCSOpenEvents()
    {
        $events = communityservice::where('eventstatus', 'Open')->get();
        $totalevents = communityservice::count();
        $openevents = communityservice::where('eventstatus', 'Open')->count();
        $closedevents = communityservice::where('eventstatus', 'Closed')->count();

        return view(
            'staff.openevents',
            compact('events', 'totalevents', 'openevents', 'closedevents')
        );
    }

    public function showCSClosedEvents()
    {
        $events = communityservice::where('eventstatus', 'Closed')->get();
        $totalevents = communityservice::count();
        $openevents = communityservice::where('eventstatus', 'Open')->count();
        $closedevents = communityservice::where('eventstatus', 'Closed')->count();

        return view(
            'staff.closedevents',
            compact(
                'events',
                'totalevents',
                'openevents',
                'closedevents'
            )
        );
    }


    public function showcseventinfo($csid)
    {
        $event = communityservice::findOrFail($csid);
        $volunteers = csregistration::where('csid', $csid)->get();
        return view('staff.cseventinfo', compact('event', 'volunteers'));
    }

    public function createcsevent(Request $request)
    {
        try {
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
            $worker = Auth::guard('staff')->user();

            $volunteersnum = 0;
            $eventstatus = 'Open';



            $event = communityservice::create([
                'staffID' => $worker->id,
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
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('communityservice')->with('error', 'Activity creation was unsuccessful.');
        }
    }

    public function updatecsevent($csid, Request $request)
    {
        try {
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

            $event = communityservice::where('csid', $csid)->first();

            if (!$event) {
                return redirect()->back()->with('error', 'Event not found.');
            }

            $event->update([
                'title' => $request->title,
                'eventloc' => $request->eventloc,
                'eventdate' => $request->eventdate,
                'meetingplace' => $request->meetingplace,
                'calltime' => $request->calltime,
                'starttime' => $request->starttime,
                'facilitator' => $request->facilitator,
                'slotnum' => $request->slotnum,
                'volunteersnum' => $event->volunteersnum,
                'eventstatus' => $request->eventstatus
            ]);

            return redirect()->back()->with('success', 'Successfully updated activity details.');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Updating activity details was unsuccessful.');
        }
    }

    public function showHumanitiesClass()
    {
        $classes = humanitiesclass::all();
        return view('staff.managehc', compact('classes'));
    }

    public function createhc(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:255',
            'hclocation' => 'required|string|max:255',
            'hcstarttime' => 'required',
            'hcendtime' => 'required',
        ]);

        try {
            $totalattendees = 0;
            $hcdate = now();

            $event = humanitiesclass::create([
                'topic' => $request->topic,
                'hcdate' => $hcdate,
                'hclocation' => $request->hclocation,
                'hcstarttime' => $request->hcstarttime,
                'hcendtime' => $request->hcendtime,
                'totalattendees' => $totalattendees,
            ]);

            return redirect()->route('attendancesystem', $event->hcid);
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('humanitiesclass')->with('error', 'Activity creation was unsuccessful.');
        }
    }

    public function showAttendanceSystem($hcid)
    {
        $event = humanitiesclass::findOrFail($hcid);
        $scholars = User::with(['basicInfo'])->get();

        return view('staff.hcattendancesystem', compact('scholars', 'event'));
    }

    public function saveattendance($hcid, Request $request)
    {
        $request->validate([
            'scholar' => 'required',
        ]);

        try {
            $event = humanitiesclass::findOrFail($hcid);
            $timeIn = Carbon::now(new \DateTimeZone('Asia/Manila'));

            if ($timeIn->greaterThan($event->hcstarttime)) {
                $tardinessDuration = $timeIn->diffInMinutes($event->hcstarttime, true);
                $hcstatus = 'Late';
            } else {
                $tardinessDuration = 0;
                $hcstatus = 'Present';
            }

            try {
                DB::beginTransaction();

                $existingAttendance = HCAttendance::where('hcid', $hcid)
                    ->where('caseCode', $request->scholar)
                    ->first();



                if ($existingAttendance) {
                    DB::rollBack();

                    return redirect()->route('attendancesystem', ['hcid' => $hcid])
                        ->with('error', 'Attendance was unsuccessful: Duplicate Entry.');
                }

                HCAttendance::create([
                    'hcid' => $hcid,
                    'caseCode' => $request->scholar,
                    'timein' => $timeIn->toTimeString(),
                    'timeout' => null,
                    'tardinessduration' => $tardinessDuration,
                    'hcastatus' => $hcstatus,
                ]);

                humanitiesclass::where('hcid', $hcid)->increment('totalattendees', 1);

                DB::commit();

                if ($hcstatus == 'Late') {
                    $worker = Auth::guard('staff')->user();
                    $attendee = hcattendance::where('hcid', $hcid)
                        ->where('caseCode', $request->scholar)
                        ->first();

                    $lte = lte::create([
                        'caseCode' => $attendee->caseCode,
                        'violation' => 'Late',
                        'conditionid' => $attendee->hcaid,
                        'eventtype' => "Humanities Class",
                        'dateissued' => $event->hcdate,
                        'deadline' => Carbon::parse($event->hcdate)->addDays(3),
                        'datesubmitted' => NULL,
                        'reason' => NULL,
                        'explanation' => NULL,
                        'proof' => NULL,
                        'ltestatus' => 'No Response',
                        'workername' => strtoupper($worker->name) . ", RSW",
                    ]);

                    $api_key = env('MOVIDER_API_KEY');
                    $api_secret = env('MOVIDER_API_SECRET');

                    $user = User::where('caseCode', $attendee->caseCode)->first();

                    // Initialize the Guzzle client
                    $client = new \GuzzleHttp\Client();

                    // Track failed SMS and failed email notifications
                    $failedSMS = [];
                    $failedEmail = [];
                    $message = $hcstatus;

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

                    DB::commit();
                }

                return redirect()->route('attendancesystem', ['hcid' => $hcid])->with('success', 'Attendance successfully submitted');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('attendancesystem', ['hcid' => $hcid])->with('error', 'Failed to submit attendance.', $e->getMessage());
            }
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('attendancesystem', ['hcid' => $hcid])->with('error', 'Attendance failed: Humanities class not found.');
        } catch (\Exception $e) {
            return redirect()->route('attendancesystem', ['hcid' => $hcid])->with('error', 'Attendance was unsuccessful.');
        }
    }

    public function viewhcattendees($hcid, Request $request)
    {
        try {
            $worker = Auth::guard('staff')->user();

            if (!Hash::check($request->password, $worker->password)) {
                return redirect()->back()->with('error', 'Incorrect password.');
            }

            return $this->viewattendeeslist($hcid);
        } catch (\Exception $e) {
            return redirect()->route('attendancesystem', ['hcId' => $hcid])
                ->with('error', 'Access failed.');
        }
    }

    public function viewattendeeslist($hcid)
    {
        $event = HumanitiesClass::findOrFail($hcid);

        $attendees = HcAttendance::with(['basicInfo'])
            ->where('hcId', $hcid)
            ->get();

        return view('staff.viewhcattendeeslist', compact('event', 'attendees'));
    }

    public function exitattendancesystem($hcId, Request $request)
    {
        try {
            $worker = Auth::guard('staff')->user();

            if (!Hash::check($request->password, $worker->password)) {
                return redirect()->back()->with('error', 'Incorrect password.');
            }
            return redirect()->route('humanitiesclass');
        } catch (\Exception $e) {
            return redirect()->route('attendancesystem', ['hcId' => $hcId])
                ->with('error', 'Access failed');
        }
    }

    public function savehc($hcid)
    {
        try {
            DB::beginTransaction();
            hcattendance::where('hcid', $hcid)
                ->whereNull('timeout')
                ->update(['timeout' => Carbon::now(new \DateTimeZone('Asia/Manila'))]);

            DB::commit();

            return $this->viewattendeeslist($hcid)->with('success', 'Checkout was successful.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->viewattendeeslist($hcid)->with('error', 'Checkout was successful.');
        }
    }

    public function checkouthc($hcaid)
    {
        try {
            DB::beginTransaction();

            $attendee = hcattendance::findOrFail($hcaid);
            $event = humanitiesclass::findOrFail($attendee->hcid);
            $worker = Auth::guard('staff')->user();

            if ($attendee->timeout == NULL) {
                $timeout = Carbon::now(new \DateTimeZone('Asia/Manila'));
                $newhcstatus = 'Left Early';
                $tardinessduration = $timeout->diffInMinutes($event->hcendtime, true);

                $attendee->update([
                    'timeout' => $timeout,
                    'tardinessduration' => $tardinessduration,
                    'hcastatus' => $newhcstatus,
                ]);

                DB::commit();

                if ($attendee->hcastatus != 'Late') {
                    $lte = lte::create([
                        'caseCode' => $attendee->caseCode,
                        'violation' => $newhcstatus,
                        'conditionid' => $attendee->hcaid,
                        'eventtype' => "Humanities Class",
                        'dateissued' => $event->hcdate,
                        'deadline' => Carbon::parse($event->hcdate)->addDays(3),
                        'datesubmitted' => NULL,
                        'reason' => NULL,
                        'explanation' => NULL,
                        'proof' => NULL,
                        'ltestatus' => 'No Response',
                        'workername' => strtoupper($worker->name) . ", RSW",
                    ]);


                    $api_key = env('MOVIDER_API_KEY');
                    $api_secret = env('MOVIDER_API_SECRET');

                    $user = User::where('caseCode', $attendee->caseCode)->first();

                    // Initialize the Guzzle client
                    $client = new \GuzzleHttp\Client();

                    // Track failed SMS and failed email notifications
                    $failedSMS = [];
                    $failedEmail = [];
                    $message = $newhcstatus;

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
                }

                DB::commit();
            }

            return $this->viewattendeeslist($attendee->hcid)->with('success', 'Checkout was successful.');
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->viewattendeeslist($attendee->hcid)->with('error', 'Checkout was unsuccessful.');
        }
    }

    public function importemails(Request $request)
    {
        try {
            // Validate the file upload
            $request->validate([
                'file' => ['required', 'mimes:xls,xlsx', 'max:25600'], // Ensure file is required and within limits
            ], [
                'file.mimes' => 'The uploaded file must be an Excel file (.xls, .xlsx).',
                'file.max' => 'The uploaded file may not be larger than 25MB.',
            ]);

            // Check if the Email table is empty
            if (Email::exists()) { // Returns true if there are records
                Email::truncate();
            }

            // Import the file using Maatwebsite Excel
            Excel::import(new EmailsImport, $request->file('file'));

            return redirect()->back()->with('importsuccess', 'File imported successfully.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return redirect()->back()->with('importerror', 'Import was unsuccessful. ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('importerror', 'Import was unsuccessful. ' . $e->getMessage());
        }
    }
}
