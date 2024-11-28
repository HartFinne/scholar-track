<?php

namespace App\Http\Controllers;

use App\Http\Middleware\applicant;
use App\Notifications\ApplicantAccountCreation;
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
use App\Models\ApplicationInstruction;
use App\Models\applicants;
use App\Models\apceducation;
use App\Models\apeheducation;
use App\Models\apfamilyinfo;
use App\Models\specialallowanceforms;
use App\Models\CreateSpecialAllowanceForm;
use App\Models\SpecialAllowanceFormStructure;
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
use App\Models\apcasedetails;
use App\Models\applicationforms;
use App\Models\Appointments;
use App\Models\RegularAllowance;
use App\Notifications\AccountCreationNotification;
use App\Notifications\LteAnnouncementCreated;
use App\Notifications\PenaltyNotification;
use App\Notifications\RegularAllowanceNotification;
use App\Notifications\SpecialAllowancesNotification;
use Illuminate\Console\Application;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Exports\SpecialAllowanceFormExport;
use App\Models\SpecialAllowanceSummary;
use Illuminate\Support\Facades\Storage;

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
        $applicants = applicants::orderBy('prioritylevel', 'DESC')->get();
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
        $iscollege = apceducation::where('casecode', $casecode)->exists();

        if ($iscollege) {
            $form = applicationforms::where('formname', 'College');
        } else {
            if ($applicant->educelemhs->schoollevel == 'Elementary') {
                $form = applicationforms::where('formname', 'College')->first();
            } else {
                $form = applicationforms::where('formname', 'High School')->first();
            }
        }

        $worker = Auth::guard('staff')->user();

        return view('staff.applicant-info', compact('applicant', 'father', 'mother', 'siblings', 'iscollege', 'worker', 'form'));
    }

    public function updateapplicationinstructions($level, Request $request)
    {
        try {
            $request->validate([
                'applicants' => 'required|string',
                'qualifications' => 'required|string',
                'documents' => 'required|string',
                'process' => 'required|string',
            ]);

            $instruction = ApplicationInstruction::where('schoollevel', $level)->first();
            $instruction->applicants = $request->applicants;
            $instruction->qualifications = $request->qualifications;
            $instruction->requireddocuments = $request->documents;
            $instruction->applicationprocess = $request->process;
            $instruction->save();

            return redirect()->back()->with('success', "The Application Instruction for {$level} has been successfully updated.");
        } catch (\Exception $e) {
            return redirect()->back()->with('failure', "Failed to update Application Instruction for {$level}." . $e->getMessage())->withInput();
        }
    }

    public function updateapplicantstatus($casecode, Request $request)
    {
        DB::beginTransaction();
        try {
            $applicant = applicants::where('casecode', $casecode)->first();
            $applicant->applicationstatus = $request->applicationstatus;
            $applicant->comment = $request->comment;
            $applicant->save();
            DB::commit();

            $api_key = env('MOVIDER_API_KEY');
            $api_secret = env('MOVIDER_API_SECRET');

            $user = applicants::where('casecode', $casecode)->first();;

            // Initialize the Guzzle client
            $client = new \GuzzleHttp\Client();

            // Track failed SMS and failed email notifications
            $failedSMS = [];
            $failedEmail = [];
            $message = 'Your application status has been updated. Please log in to your account to view the full details.';

            if ($user->notification_preference === 'sms') {
                // Send the SMS using the Movider API
                try {
                    $response = $client->post('https://api.movider.co/v1/sms', [
                        'form_params' => [
                            'api_key' => $api_key,
                            'api_secret' => $api_secret,
                            'to' => $user->phonenum,
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
                    $user->notify(new ApplicantAccountCreation($applicant));
                } catch (\Exception $e) {
                    // If email notification failed, add to failed list
                    $failedEmail[] = $user->email;
                }
            }

            return redirect()->back()->with('success', "Successfully updated application status.");
        } catch (\Exception $e) {
            // Roll back the transaction in case of error
            DB::rollBack();
            return redirect()->back()->with('failure', 'Failed to update application status. ' . $e->getMessage());
        }
    }

    public function updateApplicantCD($casecode, Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'needs' => 'required|string',
                'problemstatement' => 'required|string|max:255',
                'receivedby' => 'required|string|max:255',
                'datereceived' => 'required|date',
                'district' => 'required|string|max:50',
                'volunteer' => 'required|string|max:255',
                'referredby' => 'required|string|max:255',
                'referphonenum' => 'required|string|max:12|min:11',
                'relationship' => 'required|string|max:50',
                'datereported' => 'required|date',
            ]);

            $applicantcd = apcasedetails::where('casecode', $casecode)->first();

            // Format the phone number if it starts with 0
            $formattedphonenum = $request->referphonenum;
            if (str_starts_with($request->referphonenum, '0')) {
                $formattedphonenum = '63' . substr($request->referphonenum, 1);
            }

            if ($applicantcd) {
                $applicantcd->update([
                    'casecode' => $casecode,
                    'natureofneeds' => $request->needs,
                    'problemstatement' => $request->problemstatement,
                    'receivedby' => $request->receivedby,
                    'datereceived' => $request->datereceived,
                    'district' => $request->district,
                    'volunteer' => $request->volunteer,
                    'referredby' => $request->referredby,
                    'referphonenum' => $formattedphonenum,
                    'relationship' => $request->relationship,
                    'datereported' => $request->datereported,
                ]);
            } else {
                apcasedetails::create([
                    'casecode' => $casecode,
                    'natureofneeds' => $request->needs,
                    'problemstatement' => $request->problemstatement,
                    'receivedby' => $request->receivedby,
                    'datereceived' => $request->datereceived,
                    'district' => $request->district,
                    'volunteer' => $request->volunteer,
                    'referredby' => $request->referredby,
                    'referphonenum' => $formattedphonenum,
                    'relationship' => $request->relationship,
                    'datereported' => $request->datereported,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', "Successfully updated case details of applicant.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('failure', 'Failed to update case details of applicant. ' . $e->getMessage());
        }
    }

    public function  updateappformstatus($formname, $status, Request $request)
    {
        DB::beginTransaction();
        try {
            $form = applicationforms::where('formname', $formname)->first();
            $form->deadline = $status == 'Open' ? $request->deadline : NULL;
            $form->enddate = $status == 'Open' ? $request->enddate : NULL;
            $form->status = $status;
            $form->save();

            DB::commit();

            if ($status == 'Open') {
                return redirect()->back()->with('success', "{$formname} application is now open.");
            } else {
                return redirect()->back()->with('success', "{$formname} application is now closed.");
            }
        } catch (\Exception $e) {
            // Roll back the transaction in case of error
            DB::rollBack();
            return redirect()->back()->with('failure', 'Failed to update form status. ');
        }
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
            return redirect()->back()->with('failure', 'Failed to update scholarship status. ' . $e->getMessage());
        }
    }

    public function showgradesinfo($gid)
    {
        $grade = grades::where('gid', $gid)->first();
        $scholar = user::with('basicInfo', 'education')->where('caseCode', $grade->caseCode)->first();

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

            $lteExists = lte::where('caseCode', $grade->caseCode)
                ->where('conditionid', $gid)->exists();

            $scholarshipinfo = scholarshipinfo::where('caseCode', $grade->caseCode)->first();

            // Check if the grade status is neither "Passed" nor "Pending"
            if ($request->gradestatus != 'Passed' && $request->gradestatus != 'Pending' && !$lteExists) {
                lte::create([
                    'caseCode' => $grade->caseCode,
                    'violation' => $request->gradestatus,
                    'conditionid' => $gid,
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


                $ltecount = lte::where('caseCode', $grade->caseCode)
                    ->whereIN('violation', ['Failed GWA', 'Failed Grade', 'Mismatched GWA'])
                    ->count();

                if ($ltecount <= 2) {
                    $scholarshipinfo->scholarshipstatus = 'On-Hold';
                } else {
                    $scholarshipinfo->scholarshipstatus = 'Terminated';
                    $scholarshipinfo->save();
                }
            }

            // Commit the transaction
            DB::commit();
            return redirect()->back()->with('success', 'Successfully updated grade status.');
        } catch (\Exception $e) {
            // Roll back the transaction in case of error
            DB::rollBack();
            return redirect()->back()->with('failure', 'Failed to update grade status: ' . $e->getMessage());
        }
    }

    public function showLTE()
    {
        $lte = lte::with('hcattendance', 'csattendance')->get();
        $scholars = User::with(['basicInfo', 'education'])->get();
        return view('staff.lte', compact('lte', 'scholars'));
    }

    public function showlteinfo($lid)
    {
        $letter = lte::where('lid', $lid)->first();
        $scholar = User::with(['basicInfo'])->where('caseCode', $letter->caseCode)->first();
        if ($letter->violation == 'Failed GWA' || $letter->violation == 'Failed Grade' || $letter->violation == 'Mismatched GWA') {
            $academicData = grades::where('gid', $letter->conditionid)->first();

            return view('staff.lteinfo', compact('letter', 'scholar', 'academicData'));
        } else {
            if ($letter->eventtype == 'Humanities Class') {
                $violation = hcattendance::where('hcaid', $letter->conditionid)->first();
                $eventinfo = humanitiesclass::where('hcid', $violation->hcid)->first();
            } elseif ($letter->eventtype == 'Community Service') {
                $violation = csregistration::where('csrid', $letter->conditionid)->first();
                $eventinfo = communityservice::where('csid', $violation->csid)->first();
            }

            return view('staff.lteinfo', compact('letter', 'scholar', 'eventinfo'));
        }
    }

    public function updateltestatus($lid, Request $request)
    {
        try {
            $letter = lte::where('lid', $lid)->first();
            $letter->ltestatus = $request->ltestatus;
            $letter->save();
            $scinfo = scholarshipinfo::where('caseCode', $letter->caseCode)->first();
            $scholar = User::where('caseCode', $letter->caseCode)->first();

            if (in_array($letter->violation, ['Late', 'Absent', 'Left Early', 'Cancelled'])) {
                $condition = $letter->violation . ' in ' . $letter->eventtype;
            } else {
                $condition = $letter->violation;
            }

            if ($request->ltestatus == 'Unexcused') {
                $currentpenalty = penalty::where('caseCode', $letter->caseCode)
                    ->where('condition', $condition)
                    ->orderBy('remark', 'desc')
                    ->first();

                $date = today()->toDateString();

                if ($currentpenalty) {
                    $offenses = [
                        '1st Offense' => '2nd Offense',
                        '2nd Offense' => '3rd Offense',
                        '3rd Offense' => '4th Offense',
                    ];

                    $remark = $offenses[$currentpenalty->remark] ?? null;
                } else {
                    $remark = '1st Offense';
                }

                $failedGradeStatuses = ['Failed GWA', 'Failed GWA (Chinese Subject)', 'Failed Grade in Subject/s'];

                // Check if the violation matches one of the failed statuses
                if (in_array($condition, $failedGradeStatuses)) {
                    // Update scholarship status
                    // dd($scinfo->scholarshipstatus);
                    $scinfo->update([
                        'scholarshipstatus' => 'Terminated',
                        'updated_at' => now() // Ensure the timestamp is updated
                    ]);
                }

                if (
                    ($remark == '3rd Offense' && $letter->violation == 'Absent' && $letter->eventtype == 'Humanities Class') ||
                    ($remark == '4th Offense' && in_array($letter->violation, ['Late', 'Left Early']) && $letter->eventtype == 'Humanities Class')
                ) {
                    if ($scinfo) {
                        $scinfo->scholarshipstatus = 'On-Hold';
                        $scinfo->save();
                    }
                }

                penalty::create([
                    'caseCode' => $letter->caseCode,
                    'condition' => $condition,
                    'conditionid' => $lid,
                    'remark' => $remark,
                    'dateofpenalty' => $date,
                ]);
            } else if ($request->ltestatus == 'Excused') {
                $scinfo->scholarshipstatus = 'Continuing';
                $scinfo->save();
            }

            DB::commit();

            return redirect()->back()->with('success', 'Successfully updated LTE status.');
        } catch (\Exception $e) {
            DB::commit();

            return redirect()->back()->with('failure', 'Failed to update LTE status. ' . $e->getMessage());
        }
    }

    public function showPenalty()
    {
        $penaltys = Penalty::with('basicInfo')
            ->get(['caseCode', 'condition'])
            ->groupBy('caseCode')
            ->map(function ($penaltyGroup, $caseCode) {
                $conditions = $penaltyGroup->pluck('condition')->unique()->join('<br>');
                $basicInfo = $penaltyGroup->first()->basicInfo;
                return [
                    'caseCode' => $caseCode,
                    'conditions' => $conditions,
                    'basicInfo' => $basicInfo
                ];
            });
        // dd($penalty);
        $scholars = User::with(['basicInfo'])->get();
        $penalties = [];
        foreach ($scholars as $scholar) {
            $latestPenalty = penalty::where('caseCode', $scholar->caseCode)
                ->latest('dateofpenalty')
                ->first();
            $penalties[$scholar->caseCode] = $latestPenalty;
        }
        return view('staff.penalty', compact('penalties', 'scholars', 'penaltys'));
    }

    public function showpenaltyinfo($casecode)
    {
        $penalties = Penalty::with('basicInfo')
            ->where('caseCode', $casecode)
            ->get()
            ->groupBy('condition');
        $scholar = user::with('basicInfo')->where('caseCode', $casecode)->first();

        return view('staff.penaltyinfo', compact('penalties', 'scholar'));
    }

    public function storePenalty(Request $request)
    {
        $validatedData = $request->validate([
            'scholar_id' => 'required|string|exists:users,caseCode',
            'condition' => 'required|string|in:Lost Cash Card,Dress Code Violation',
        ]);

        $currentpenalty = penalty::where('caseCode', $validatedData['scholar_id'])
            ->where('condition', $validatedData['condition'])
            ->orderBy('remark', 'desc')
            ->first();

        $date = today()->toDateString();

        if ($currentpenalty) {
            $offenses = [
                '1st Offense' => '2nd Offense',
                '2nd Offense' => '3rd Offense',
                '3rd Offense' => '4th Offense',
            ];

            $remark = $offenses[$currentpenalty->remark] ?? null;
        } else {
            $remark = '1st Offense';
        }

        $penalty = penalty::create([
            'caseCode' => $validatedData['scholar_id'],
            'condition' => $validatedData['condition'],
            'conditionid' => NULL,
            'remark' => $remark,
            'dateofpenalty' => $date,
        ]);

        // Prepare notification settings
        $api_key = config('services.movider.api_key');
        $api_secret = config('services.movider.api_secret');
        Log::info('Movider API Key', ['api_key' => $api_key, 'api_secret' => $api_secret]);

        $user = User::where('caseCode', $validatedData['scholar_id'])->first();

        $client = new \GuzzleHttp\Client();
        $failedSMS = [];
        $failedEmail = [];
        $message = "Your penalty has been updated: " . $validatedData['condition'] . " (" . $remark . ")";

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

    public function showQualification()
    {
        $forms = applicationforms::all();
        $criteria = criteria::first();
        $courses = courses::where('level', 'College')->get();
        $strands = courses::where('level', 'Senior High')->get();
        $institutions = institutions::all();
        $instruction =  [
            'College' => ApplicationInstruction::where('schoollevel', 'College')->first(),
            'Senior High' => ApplicationInstruction::where('schoollevel', 'Senior High')->first(),
            'Junior High' => ApplicationInstruction::where('schoollevel', 'Junior High')->first(),
            'Elementary' => ApplicationInstruction::where('schoollevel', 'Elementary')->first(),
        ];
        return view('staff.qualification', compact('criteria', 'institutions', 'courses', 'strands', 'forms', 'instruction'));
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
            return redirect()->back()->with('success', 'Successfully updated scholarship requirements');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('failure', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('failure', 'Unable to update scholarship requirements.');
        }
    }

    public function addinstitution(Request $request)
    {
        try {
            $request->validate([
                'institute' => 'required|string|max:255',
                'schoollevel' => 'required|string|max:25',
                'academiccycle' => 'required|string|max:25',
                'highestgwa' => 'required|numeric|max:100|min:1',
            ]);

            $dataExists = institutions::where('schoolname', $request->institute)
                ->where('schoollevel', $request->schoollevel)
                ->exists();

            if ($dataExists) {
                return redirect()->back()->with('failure', 'Failed to add institution. Duplicate combination of institution and school level.');
            }

            DB::beginTransaction();

            institutions::create([
                'schoolname' => $request->institute,
                'schoollevel' => $request->schoollevel,
                'academiccycle' => $request->academiccycle,
                'highestgwa' => $request->highestgwa,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Successfully added an institution.');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('failure', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failure', 'Failed to add institution.');
        }
    }

    public function updateinstitution($inid, Request $request)
    {
        $request->validate([
            'newschoolname' => 'required|string|max:255',
            'newschoollevel' => 'required|string|max:25',
            'newacademiccycle' => 'required|string|max:25',
            'newgwa' => 'required|numeric|max:100|min:1',
        ]);

        $dataExists = institutions::where('schoolname', $request->newschoolname)
            ->where('schoollevel', $request->newschoollevel)
            ->where('inid', '!=', $inid)
            ->exists();

        if ($dataExists) {
            return redirect()->back()->with('failure', 'Failed to update institution. Duplicate combination of institution and school level.');
        }

        DB::beginTransaction();
        try {
            $institution = institutions::findOrFail($inid);

            $institution->update([
                'schoolname' => $request->newschoolname,
                'schoollevel' => $request->newschoollevel,
                'academiccycle' => $request->newacademiccycle,
                'highestgwa' => $request->newgwa,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Successfully updated the institution.');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('failure', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failure', 'Failed to update institution.') . $e->getMessage();
        }
    }

    public function deleteinstitution($inid)
    {
        DB::beginTransaction();
        try {
            $institution = institutions::findOrFail($inid);
            $institution->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Successfully deleted the institution.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('failure', 'Failed to delete institution.');
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

                return redirect()->back()->with('success', 'Successfully added a course.');
            } catch (ValidationException $e) {
                DB::rollback();
                return redirect()->back()->with('failure', $e->getMessage());
            } catch (\Exception $e) {
                DB::rollback();

                if (courses::where('coursename', $request->course)->exists()) {
                    return redirect()->back()->with('failure', 'Failed to add course. Duplicate course.');
                }

                return redirect()->back()->with('failure', 'Failed to add course.');
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

                return redirect()->back()->with('failure', 'Successfully added a strand.');
            } catch (ValidationException $e) {
                DB::rollback();
                return redirect()->back()->with('failure', $e->getMessage());
            } catch (\Exception $e) {
                DB::rollback();

                if (courses::where('coursename', $request->strand)->exists()) {
                    return redirect()->back()->with('failure', 'Failed to add strand. Duplicate strand.');
                }

                return redirect()->back()->with('failure', 'Failed to add strand.');
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
            return redirect()->back()->with('success', "Successfully updated {$type}.");
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('failure', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();

            if (courses::where('coursename', $request->newcoursename)->exists()) {
                return redirect()->back()->with('failure', "Failed to update {$type}. Duplicate {$type}.");
            }

            return redirect()->back()->with('failure', "Failed to update {$type}.");
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
            return redirect()->back()->with('success', "Successfully deleted {$type}.");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('failure', "Failed to delete {$type}.");
        }
    }

    public function showRenewal()
    {
        // Get renewal form information
        $renewal = applicationforms::where('formname', 'Renewal')->first();

        $startdate = $renewal->updated_at->format('Y-m-d');
        $enddate = $renewal->enddate ? $renewal->enddate->format('Y-m-d') : now()->addYear()->format('Y-m-d');

        // Generate summary data
        $summary = [
            'totalrenew' => renewal::whereBetween('datesubmitted', [$startdate, $enddate])->count(),
            'pending' => renewal::where('status', 'Pending')->whereBetween('datesubmitted', [$startdate, $enddate])->count(),
            'approved' => renewal::where('status', 'Approved')->whereBetween('datesubmitted', [$startdate, $enddate])->count(),
            'rejected' => renewal::where('status', 'Rejected')->whereBetween('datesubmitted', [$startdate, $enddate])->count(),
        ];

        // Fetch data for College
        $college = User::with('basicInfo', 'education', 'scholarshipinfo', 'renewal')
            ->whereHas('education', function ($query) {
                $query->where('scSchoolLevel', 'College');
            })
            ->whereHas('renewal', function ($query) use ($startdate, $enddate) {
                $query->whereBetween('datesubmitted', [$startdate, $enddate])
                    ->orderBy('datesubmitted', 'ASC')
                    ->orderByRaw("
        CASE
            WHEN renewal.status = 'Pending' THEN 1 
            WHEN renewal.status = 'Approved' THEN 2  
            WHEN renewal.status = 'Rejected' THEN 3 
            WHEN renewal.status = 'Withdrawn' THEN 4
            ELSE 5
        END");
            })
            ->get();

        // Fetch data for Senior High School
        $shs = User::with('basicInfo', 'education', 'scholarshipinfo', 'renewal')
            ->whereHas('education', function ($query) {
                $query->where('scSchoolLevel', 'Senior High');
            })
            ->whereHas('renewal', function ($query) use ($startdate, $enddate) {
                $query->whereBetween('datesubmitted', [$startdate, $enddate])
                    ->orderBy('datesubmitted', 'ASC')
                    ->orderByRaw("
        CASE
            WHEN renewal.status = 'Pending' THEN 1 
            WHEN renewal.status = 'Approved' THEN 2  
            WHEN renewal.status = 'Rejected' THEN 3 
            WHEN renewal.status = 'Withdrawn' THEN 4
            ELSE 5
        END");
            })
            ->get();

        // Fetch data for Junior High School
        $jhs = User::with('basicInfo', 'education', 'scholarshipinfo', 'renewal')
            ->whereHas('education', function ($query) {
                $query->where('scSchoolLevel', 'Junior High');
            })
            ->whereHas('renewal', function ($query) use ($startdate, $enddate) {
                $query->whereBetween('datesubmitted', [$startdate, $enddate])
                    ->orderBy('datesubmitted', 'ASC')
                    ->orderByRaw("
        CASE
            WHEN renewal.status = 'Pending' THEN 1 
            WHEN renewal.status = 'Approved' THEN 2  
            WHEN renewal.status = 'Rejected' THEN 3 
            WHEN renewal.status = 'Withdrawn' THEN 4
            ELSE 5
        END");
            })
            ->get();

        // Fetch data for Elementary
        $elem = User::with('basicInfo', 'education', 'scholarshipinfo', 'renewal')
            ->whereHas('education', function ($query) {
                $query->where('scSchoolLevel', 'Elementary');
            })
            ->whereHas('renewal', function ($query) use ($startdate, $enddate) {
                $query->whereBetween('datesubmitted', [$startdate, $enddate])
                    ->orderBy('datesubmitted', 'ASC')
                    ->orderByRaw("
        CASE
            WHEN renewal.status = 'Pending' THEN 1 
            WHEN renewal.status = 'Approved' THEN 2  
            WHEN renewal.status = 'Rejected' THEN 3 
            WHEN renewal.status = 'Withdrawn' THEN 4
            ELSE 5
        END");
            })
            ->get();

        // Pass data to the view
        return view('staff.renewal', compact('summary', 'college', 'shs', 'jhs', 'elem'));
    }


    public function showRenewalinfo()
    {
        return view('staff.renewalinfo');
    }

    public function showAllowanceRegular()
    {
        $requests = RegularAllowance::join('grades', 'grades.gid', '=', 'regular_allowance.gid')
            ->join('users', 'users.caseCode', '=', 'grades.caseCode')
            ->join('sc_basicinfo', 'sc_basicinfo.caseCode', '=', 'users.caseCode') // Joining with sc_basicInfo using user_id
            ->get();

        return view('staff.regularallowance', compact('requests'));
    }

    public function viewAllowanceRegularInfo()
    {
        return view('staff.regularallowanceinfo');
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
            return redirect()->back()->with('failure', 'Unable to update request. ' . $e->getMessage());
        }
    }

    // public function showAllowanceSpecial()
    // {
    //     $allowanceModels = [
    //         allowancebook::class,
    //         allowanceevent::class,
    //         allowancethesis::class,
    //         allowanceproject::class,
    //         allowancetranspo::class,
    //         allowanceuniform::class,
    //         allowancegraduation::class
    //     ];

    //     $data = SpecialAllowanceSummary::first();

    //     // Define an array of model classes
    //     $allowanceModels = [
    //         allowancebook::class,
    //         allowanceevent::class,
    //         allowancethesis::class,
    //         allowanceproject::class,
    //         allowancetranspo::class,
    //         allowanceuniform::class,
    //         allowancegraduation::class
    //     ];

    //     // Initialize an empty collection to store all results
    //     $mergedRequests = collect();

    //     // Loop through each model class, retrieve and merge the results
    //     foreach ($allowanceModels as $model) {
    //         // Retrieve records already ordered by created_at and add to the merged collection
    //         $records = $model::orderBy('created_at', 'asc')->get();
    //         $mergedRequests = $mergedRequests->concat($records);
    //     }

    //     // Define a custom order for statuses
    //     $statusOrder = ['Pending', 'Accepted', 'Completed', 'Rejected'];

    //     // Sort the merged collection by status first, then by created_at
    //     $requests = $mergedRequests->sort(function ($a, $b) use ($statusOrder) {
    //         // Compare status by predefined priority
    //         $statusComparison = array_search($a->status, $statusOrder) <=> array_search($b->status, $statusOrder);
    //         if ($statusComparison == 0) { // if statuses are the same, sort by created_at
    //             return $a->created_at <=> $b->created_at;
    //         }
    //         return $statusComparison;
    //     })->values();

    //     // Ensure keys are reset
    //     $requests = $requests->values();

    //     return view('staff.specialallowance', compact('data', 'requests'));
    // }

    public function showAllowanceSpecial()
    {
        $summary = SpecialAllowanceSummary::first();

        $forms = CreateSpecialAllowanceForm::get();
        $scholars = User::with('basicInfo', 'education', 'scholarshipinfo')->get();

        $data = [];
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

            // Ensure the file is not empty
            if (!empty($data[$formname][0])) {
                // The first row contains the column headers
                $headers = $data[$formname][0][0];

                // Skip the first row (headers) and map each remaining row
                $mappedData = array_map(function ($row) use ($headers) {
                    // Combine each row with headers as keys
                    return array_combine($headers, $row);
                }, array_slice($data[$formname][0], 1)); // Skip the first row (headers)

                // Store the mapped data
                $data[$formname] = $mappedData;
            } else {
                // If the file is empty, set an empty array
                $data[$formname] = [];
            }
        }
        // dd($data);

        return view('staff.specialallowance', compact('data', 'summary', 'scholars', 'forms'));
    }

    public function showspecrecinfo($requesttype, $id, $caseCode)
    {
        // Get the currently authenticated user and their related data
        $scholar = User::with(['basicInfo', 'education', 'scholarshipinfo'])
            ->where('caseCode', $caseCode)
            ->first();

        // Retrieve the form based on the type
        $form = CreateSpecialAllowanceForm::where('formname', $requesttype)->first();

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
        return view('staff.specreqinfo', compact('data', 'scholar', 'form', 'fields', 'files'));
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
                return redirect()->back()->with('failure', 'The request could not be found. Please try again, and if the issue persists, contact us at inquiriescholartrack@gmail.com for assistance.');
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
            return redirect()->back()->with('failure', 'Unable to update request. ' . $e->getMessage());
        }
    }

    public function managespecialforms()
    {
        $files = specialallowanceforms::all();
        $forms = CreateSpecialAllowanceForm::get();
        return view('staff.specialallowance-manage', compact('files', 'forms'));
    }

    public function createNewSpecialAllowanceForm(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate(
                [
                    'formname' => ['required', 'string', 'max:200'],
                    'formcode' => ['required', 'string', 'max:25'],
                    'requestor' => ['required', 'array', 'min:1'],
                    'instruction' => ['required', 'string'],
                    'downloadablefiles' => ['required', 'array', 'min:1'],
                    'fieldcount' => ['required', 'integer', 'min:1'],
                ],
                [
                    'formname.required' => 'The form name is required.',
                    'formname.max' => 'The form name must not exceed 200 characters.',
                    'formcode.required' => 'The form code is required.',
                    'formcode.max' => 'The form code must not exceed 25 characters.',
                    'requestor.required' => 'At least one requestor must be selected.',
                    'requestor.min' => 'At least one requestor must be selected.',
                    'instruction.required' => 'The instruction field is required.',
                    'downloadablefiles.required' => 'At least one downloadable file must be selected.',
                    'downloadablefiles.min' => 'At least one downloadable file must be selected.',
                    'fieldcount.required' => 'You must add at least one field.',
                    'fieldcount.min' => 'You must add at least one field.',
                ]
            );

            $existingFormname = CreateSpecialAllowanceForm::where('formname', $request->formname)->exists();
            $existingFormcode = CreateSpecialAllowanceForm::where('formname', $request->formname)->exists();

            if ($existingFormname || $existingFormcode) {
                return redirect()->back()->with('failure', "Form creation failed. The form you are trying to create already exists.");
            }

            DB::beginTransaction();

            $requiredFiles = specialallowanceforms::whereIn('id', $request->downloadablefiles)->get();

            // Get the filenames from the $requiredFiles collection
            $fieldNames = $requiredFiles->pluck('filename')->toArray(); // This will give you an array of filenames

            // Get the field names from the request
            $fieldNamesFromRequest = $request->only(array_map(function ($i) {
                return 'fieldName' . $i;
            }, range(1, $request->fieldcount)));

            // Merge the filenames from the database with the field names from the request
            $fieldNames = array_merge($fieldNamesFromRequest, $fieldNames);

            // Create the Excel file
            $excelPath = $this->createExcelFile($request->formcode, $fieldNames, range(1, $request->fieldcount));


            // Save form data to 'create_special_allowance_forms' table
            $createForm = CreateSpecialAllowanceForm::create([
                'formname' => $request->formname,
                'formcode' => $request->formcode,
                'requestor' => json_encode($request->requestor),
                'instruction' => $request->instruction,
                'downloadablefiles' => json_encode($request->downloadablefiles),
                'database' => $excelPath,
            ]);

            $csafId = $createForm->csafid;

            for ($i = 1; $i <= $request->fieldcount; $i++) {
                $fieldName = $request->input('fieldName' . $i);
                $fieldType = $request->input('fieldType' . $i);

                // Only save if field name and type are provided
                if ($fieldName && $fieldType) {
                    SpecialAllowanceFormStructure::create([
                        'csafid' => $csafId,
                        'fieldname' => $fieldName,
                        'fieldtype' => $fieldType,
                    ]);
                } else {
                    continue;
                }
            }

            DB::commit();

            // Return success message
            return redirect()->back()->with('success', 'Special Allowance Form created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Return error message
            return redirect()->back()->with('failure', 'An error occurred while creating the form: ' . $e->getMessage())->withInput();
        }
    }

    public function updateSpecialAllowanceForm($id, Request $request)
    {
        try {
            // dd($request->all());
            // Validate the incoming request
            $request->validate(
                [
                    'newformname' => ['required', 'string', 'max:200'],
                    'newformcode' => ['required', 'string', 'max:25'],
                    'newrequestor' => ['required', 'array', 'min:1'],
                    'newinstruction' => ['required', 'string'],
                    'newdownloadablefiles' => ['required', 'array', 'min:1'],
                    // 'newfieldName' => ['required', 'array', 'min:1'],
                    // 'newfieldType' => ['required', 'array', 'min:1'],
                ],
                [
                    'newformname.required' => 'The form name is required.',
                    'newformname.max' => 'The form name must not exceed 200 characters.',
                    'newformcode.required' => 'The form code is required.',
                    'newformcode.max' => 'The form code must not exceed 25 characters.',
                    'newrequestor.required' => 'At least one requestor must be selected.',
                    'newrequestor.min' => 'At least one requestor must be selected.',
                    'newinstruction.required' => 'The instruction field is required.',
                    'newdownloadablefiles.required' => 'At least one downloadable file must be selected.',
                    'newdownloadablefiles.min' => 'At least one downloadable file must be selected.',
                    // 'newfieldName.required' => 'You must add at least one field.',
                    // 'newfieldType.required' => 'Field type is required for each field.',
                ]
            );

            DB::beginTransaction();

            // Fetch the existing form using the provided ID
            $form = CreateSpecialAllowanceForm::where('csafid', $id)->first();

            if (!$form) {
                throw new \Exception('Form not found.');
            }

            $requiredFiles = specialallowanceforms::whereIn('id', $request->newdownloadablefiles)->get();

            // Get the filenames from the $requiredFiles collection
            $fieldNames = $requiredFiles->pluck('filename')->toArray(); // This will give you an array of filenames

            // Get the field names from the request
            $existingFieldNames = SpecialAllowanceFormStructure::where('csafid', $id)->pluck('fieldname')->toArray();

            // Merge the filenames
            $fieldNames = array_merge(['id', 'caseCode', 'requestType', 'requestDate', 'releaseDate', 'requestStatus'], $existingFieldNames, $fieldNames);

            // Update the Excel file for the form
            $excelPath = $this->updateExcelFile($form->formcode, $request->newformcode, $fieldNames);

            // Update form details
            $form->formname = $request->newformname;
            $form->formcode = $request->newformcode;
            $form->requestor = json_encode($request->newrequestor);
            $form->instruction = $request->newinstruction;
            $form->downloadablefiles = json_encode($request->newdownloadablefiles);
            $form->database = $excelPath;
            $form->save();

            // // Track the fields sent in the request
            // $existingFieldsInRequest = [];

            // // Get the dynamic fields from the form request
            // $fieldNames = $request->input('newfieldName', []);
            // $fieldTypes = $request->input('newfieldType', []);

            // // Loop through the fields and update or create them
            // foreach ($fieldNames as $index => $fieldName) {
            //     $fieldType = $fieldTypes[$index] ?? null;

            //     // Only process if both field name and field type are provided
            //     if ($fieldName && $fieldType) {
            //         // Track the fields that we are updating or creating
            //         $existingFieldsInRequest[] = ['fieldname' => $fieldName, 'fieldtype' => $fieldType];

            //         // Check if the field already exists in the database
            //         $fieldRecord = SpecialAllowanceFormStructure::where('csafid', $form->csafid)
            //             ->where('fieldname', $fieldName)
            //             ->first();

            //         if ($fieldRecord) {
            //             // Update the existing field record
            //             $fieldRecord->fieldname = $fieldName;
            //             $fieldRecord->fieldtype = $fieldType;
            //             $fieldRecord->save();
            //         } else {
            //             // Create a new field record if it doesn't exist
            //             SpecialAllowanceFormStructure::create([
            //                 'csafid' => $form->csafid,
            //                 'fieldname' => $fieldName,
            //                 'fieldtype' => $fieldType,
            //             ]);
            //         }
            //     }
            // }

            // // Remove fields that are no longer present in the request
            // SpecialAllowanceFormStructure::where('csafid', $form->csafid)
            //     ->whereNotIn('fieldname', array_column($existingFieldsInRequest, 'fieldname'))
            //     ->delete();

            DB::commit();

            // Return success message
            return redirect()->back()->with('success', 'Special Allowance Form updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Return error message if any exception occurs
            return redirect()->back()->with('failure', 'An error occurred while updating the form: ' . $e->getMessage())->withInput();
        }
    }

    private function createExcelFile($formcode, $fieldnames)
    {
        // Define the path to save the Excel file
        $directoryPath = 'uploads/allowance_forms/special/database';
        $filename = $formcode . '.xlsx';
        $fullPath = $directoryPath . '/' . $filename;

        // Make sure the directory exists
        if (!Storage::exists($directoryPath)) {
            Storage::makeDirectory($directoryPath);
        }

        // Use Maatwebsite\Excel to create and save the Excel file
        Excel::store(new SpecialAllowanceFormExport($formcode, $fieldnames), $fullPath, 'public');

        return $fullPath;
    }

    private function updateExcelFile($formcode, $newformcode, $fieldnames)
    {
        $directoryPath = 'uploads/allowance_forms/special/database'; // Relative path within storage
        $oldFilename = $formcode . '.xlsx';
        $newFilename = $newformcode . '.xlsx';

        // Relative paths for storage disk
        $oldFullPath = $directoryPath . '/' . $oldFilename;
        $newFullPath = $directoryPath . '/' . $newFilename;

        // Check if the old file exists
        if (!Storage::disk('public')->exists($oldFullPath)) {
            throw new \Exception("File not found: " . $oldFullPath);
        }

        // Load the existing Excel file using PhpSpreadsheet
        $filePath = Storage::disk('public')->path($oldFullPath); // Correct path format
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);

        // Get the active sheet to update the headers
        $sheet = $spreadsheet->getActiveSheet();

        // Update the first row with new headers using column letters (e.g., A1, B1, C1)
        foreach ($fieldnames as $index => $fieldname) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue("{$columnLetter}1", $fieldname);
        }

        // Save the updated Excel file to the new location
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $newFilePath = Storage::disk('public')->path($newFullPath);
        $writer->save($newFilePath);

        // Rename the old file to the new file name
        if ($oldFullPath !== $newFullPath) {
            Storage::disk('public')->move($oldFullPath, $newFullPath);
        }

        // Return the new file path
        return $newFullPath;
    }

    public function delSpecialAllowanceForm($id)
    {
        try {
            DB::beginTransaction();
            $form = CreateSpecialAllowanceForm::where('csafid', $id)->first();
            $filePath = $form->database;
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $form->delete();

            Db::commit();

            return redirect()->back()->with('success', "Successfully deleted the {$form->formname}.");
        } catch (\Exception $e) {
            Db::rollBack();

            return redirect()->back()->with('success', "Failed to delete the form. Error: " . $e->getMessage());
        }
    }

    public function addDownloadableFiles(Request $request)
    {
        try {
            $request->validate(
                [
                    'filename' => ['string', 'max:200', 'required'],
                    'file' => ['mimes:pdf', 'max:2048', 'required'],
                ],
                [
                    'file.mimes' => 'The file must be a valid file (doc, docx, or pdf).',
                    'file.max' => 'The file must not exceed 2 MB.',
                    'file.required' => 'You must upload a file.',
                    'filename.required' => 'You must provide a file name.',
                    'filename.string' => 'The file name must be a valid string',
                    'filename.max' => 'The file name must not exceed 200 characters'
                ]
            );

            $uploadedfile = $request->file('file');

            $filename = $request->filename . '.' . $uploadedfile->extension();

            $path = $uploadedfile->storeAs('uploads/allowance_forms/special', $filename, 'public');

            $fileexists = specialallowanceforms::where('filename', $request->filename)->first();

            if ($fileexists) {
                return redirect()->back()->with('failure', 'Duplicate file. Please try again.');
            }

            specialallowanceforms::create([
                'filename' => $request->filename,
                'pathname' => $path,
            ]);

            return redirect()->back()->with('success', "Successfully uploaded {$request->filename}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('failure', 'Duplicate file. Please try again.' . $e->getMessage());
        }
    }

    public function updateDownloadableFile($id, Request $request)
    {
        try {
            $request->validate(
                [
                    'newfilename' => ['string', 'max:200', 'required'],
                    'newfile' => ['mimes:pdf', 'max:2048', 'required'],
                ],
                [
                    'newfile.mimes' => 'The file must be a valid file (doc, docx, or pdf).',
                    'newfile.max' => 'The file must not exceed 2 MB.',
                    'newfile.required' => 'You must upload a file.',
                    'newfilename.required' => 'You must provide a file name.',
                    'newfilename.string' => 'The file name must be a valid string',
                    'newfilename.max' => 'The file name must not exceed 200 characters'
                ]
            );

            $file = specialallowanceforms::find($id);

            $filenameExists = specialallowanceforms::where('filename', $request->newfilename)
                ->where('id', '!=', $id)->exists();

            if ($filenameExists) {
                return redirect()->back()->with('failure', "File name already exists. Please provide a different file name.");
            }

            if (!$file) {
                return redirect()->back()->with('failure', "File not found.");
            }

            $uploadedfile = $request->file('newfile');

            $filename = $request->newfilename . '.' . $uploadedfile->extension();

            $path = $uploadedfile->storeAs('uploads/allowance_forms/special', $filename, 'public');

            $file->filename = $request->newfilename;
            $file->pathname = $path;
            $file->save();

            return redirect()->back()->with('success', "Successfully updated {$file->filename}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('failure', "Failed to delete the file. Please try again. Error: " . $e->getMessage());
        }
    }

    public function deleteDownloadableFile($id)
    {
        try {
            $file = specialallowanceforms::find($id);

            if (!$file) {
                return redirect()->back()->with('failure', "File not found.");
            }

            $file->delete();

            return redirect()->back()->with('success', "Successfully deleted {$file->filename}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('failure', "Failed to delete the file. Please try again. Error: " . $e->getMessage());
        }
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

        $colleges = User::with('basicInfo', 'education', 'scholarshipinfo')
            ->whereHas('education', function ($query) {
                $query->where('scSchoolLevel', 'College');
            })
            ->orderBy('caseCode', 'ASC')
            ->get();
        $shs = User::with('basicInfo', 'education', 'scholarshipinfo')
            ->whereHas('education', function ($query) {
                $query->where('scSchoolLevel', 'Senior High');
            })
            ->orderBy('caseCode', 'ASC')
            ->get();

        $jhs = User::with('basicInfo', 'education', 'scholarshipinfo')
            ->whereHas('education', function ($query) {
                $query->where('scSchoolLevel', 'Junior High');
            })
            ->orderBy('caseCode', 'ASC')
            ->get();

        $elem = User::with('basicInfo', 'education', 'scholarshipinfo')
            ->whereHas('education', function ($query) {
                $query->where('scSchoolLevel', 'Elementary');
            })
            ->orderBy('caseCode', 'ASC')
            ->get();

        return view('staff.scholars', compact(
            'totalscholars',
            'totalnewscholars',
            'areas',
            'scholarsperarea',
            'scholarsperlevel',
            'colleges',
            'shs',
            'jhs',
            'elem'
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
        $volunteers = csregistration::with('basicInfo')->where('csid', $csid)->get();
        $attendances = csattendance::where('csid', $csid)->get();
        return view('staff.cseventinfo', compact('event', 'volunteers', 'attendances'));
    }

    public function viewcsattendance($csid, $caseCode)
    {
        $event = communityservice::findOrFail($csid);
        $scholar = csattendance::with('basicInfo')->where('csid', $csid)->where('caseCode', $caseCode)->first();
        return view('staff.csattendance', compact('event', 'scholar'));
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
            return redirect()->back()->with('failure', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('communityservice')->with('failure', 'Activity creation was unsuccessful.');
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
                return redirect()->back()->with('failure', 'Event not found.');
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
            return redirect()->back()->with('failure', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('failure', 'Updating activity details was unsuccessful.');
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
            return redirect()->back()->with('failure', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('humanitiesclass')->with('failure', 'Activity creation was unsuccessful.');
        }
    }

    public function showAttendanceSystem($hcid)
    {
        $event = humanitiesclass::findOrFail($hcid);
        $attendees = hcattendance::where('hcid', $hcid)->pluck('caseCode')->toArray();
        $scholars = User::with('basicInfo')
            ->whereNotIn('caseCode', $attendees)
            ->get();

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
                        ->with('failure', 'Attendance was unsuccessful: Duplicate Entry.');
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
                return redirect()->route('attendancesystem', ['hcid' => $hcid])->with('failure', 'Failed to submit attendance.', $e->getMessage());
            }
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('failure', $e->getMessage());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('attendancesystem', ['hcid' => $hcid])->with('failure', 'Attendance failed: Humanities class not found.');
        } catch (\Exception $e) {
            return redirect()->route('attendancesystem', ['hcid' => $hcid])->with('failure', 'Attendance was unsuccessful.');
        }
    }

    public function viewhcattendees($hcid, Request $request)
    {
        try {
            $worker = Auth::guard('staff')->user();

            if (!Hash::check($request->password, $worker->password)) {
                return redirect()->back()->with('failure', 'Incorrect password.');
            }

            return redirect()->route('viewattendeeslist', $hcid);
        } catch (\Exception $e) {
            return redirect()->route('attendancesystem', ['hcId' => $hcid])
                ->with('failure', 'Access failed.');
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
                return redirect()->back()->with('failure', 'Incorrect password.');
            }
            return redirect()->route('humanitiesclass');
        } catch (\Exception $e) {
            return redirect()->route('attendancesystem', ['hcId' => $hcId])
                ->with('failure', 'Access failed');
        }
    }

    public function savehc($hcid)
    {
        try {
            DB::beginTransaction();

            $worker = Auth::guard('staff')->user();

            hcattendance::where('hcid', $hcid)
                ->whereNull('timeout')
                ->update(['timeout' => Carbon::now(new \DateTimeZone('Asia/Manila'))]);

            $event = humanitiesclass::where('hcid', $hcid)->first();
            $event->status = 'Done';
            $event->save();

            // Get list of attendees' caseCodes
            $attendees = hcattendance::where('hcid', $hcid)
                ->pluck('caseCode');

            // Get list of absentees whose caseCode is not in the attendees
            $absentees = User::whereNotIn('caseCode', $attendees->toArray())
                ->get();

            // Iterate over each absentee and create a new attendance record marking them as absent
            foreach ($absentees as $absent) {
                hcattendance::create([
                    'hcid' => $hcid,
                    'caseCode' => $absent->caseCode,
                    'timein' => null,
                    'timeout' => null,
                    'tardinessduration' => 0,
                    'hcastatus' => 'Absent',
                ]);

                $event->increment('totalabsentees', 1);

                $attendanceinfo = hcattendance::where('caseCode', $absent->caseCode)->where('hcid', $hcid)->first();

                lte::create([
                    'caseCode' => $absent->caseCode,
                    'violation' => 'Absent',
                    'conditionid' => $attendanceinfo->hcaid,
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
            }

            DB::commit();

            return redirect()->back()->with('success', "{$event->topic} has been successfully closed.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('failure', 'Attempt to save event has failed');
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

            return $this->viewattendeeslist($attendee->hcid)->with('failure', 'Checkout was unsuccessful.');
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

            if (Email::exists()) {
                Email::truncate();
            }

            // Import the file using Maatwebsite Excel
            Excel::import(new EmailsImport, $request->file('file'));

            return redirect()->back()->with('success', 'File imported successfully.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return redirect()->back()->with('failure', 'Import was unsuccessful. Please follow the given instruction.');
        } catch (\Exception $e) {
            return redirect()->back()->with('failure', 'Import was unsuccessful. Please follow the given instruction.');
        }
    }

    public function showappointments()
    {
        $appointments = Appointments::with('basicInfo', 'education')->get();
        return view('staff.appointments', compact('appointments'));
    }

    public function viewappointmentinfo($id)
    {
        $appointment = Appointments::with('basicInfo', 'education')->where('id', $id)->first();

        return view('staff.appointmentinfo', compact('appointment'));
    }

    public function updateappointmentstatus($id, Request $request)
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
            $appointment->status = $request->status;
            $appointment->save();

            // Commit the transaction
            DB::commit();

            // Redirect with a success message
            return redirect()->back()->with('success', 'Successfully updated appointment status.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Redirect with an error message
            return redirect()->back()->with('failure', 'Failed to update appointment status: ' . $e->getMessage());
        }
    }
}
