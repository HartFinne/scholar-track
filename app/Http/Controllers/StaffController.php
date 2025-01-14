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
use App\Models\Areas;
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
use App\Models\RnwCaseDetails;
use App\Models\RnwEducation;
use App\Models\RnwFamilyInfo;
use App\Models\RnwOtherInfo;
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
use App\Models\approgress;
use App\Models\KnownSchools;
use App\Models\SpecialAllowanceSummary;
use App\Notifications\appointment;
use App\Notifications\EventUpdate;
use App\Notifications\GradeUpdate;
use App\Notifications\LteStatusUpdate;
use App\Notifications\RenewalUpdate;
use App\Notifications\ScholarshipStatus;
use App\Models\summarycollege;
use App\Models\summaryelem;
use App\Models\summaryjhs;
use App\Models\summaryshs;
use App\Models\Reports;
use App\Notifications\EventCreate;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\VarDumper\Caster\RedisCaster;
use Barryvdh\DomPDF\Facade\Pdf;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class StaffController extends Controller
{

    // for the ip address and user agent
    private function getRequestDetails()
    {
        return [
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ];
    }
    public function showAccountSW()
    {
        $worker = Auth::guard('staff')->user();
        return view('staff.profile-socialworker', compact('worker'));
    }

    public function updateStaffAccount(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'contactno' => 'required|string|max:12|min:11',
            ]);

            DB::beginTransaction();
            $worker = Auth::guard('staff')->user();
            $worker = staccount::where('id', $worker->id)->first();

            // Log the current staff details before updating
            Log::info('Staff account update initiated', [
                'staff_id' => $worker->id,
                'name' => $worker->name,
                'email' => $worker->email,
                'contactno' => $worker->mobileno,
                'action' => 'update',
                'user_id' => Auth::guard('staff')->id(),  // Use this to get the staff ID
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);

            $worker->name = $request->name;
            $worker->email = $request->email;
            $worker->mobileno = $request->contactno;
            $worker->save();
            DB::commit();

            // Log the successful update
            Log::info('Staff account updated successfully', [
                'staff_id' => $worker->id,
                'name' => $worker->name,
                'email' => $worker->email,
                'contactno' => $worker->mobileno,
                'action' => 'update',
                'user_id' => Auth::guard('staff')->id(),  // Again use this for staff ID
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);

            return redirect()->back()->with('success', 'Successfully updated account information.');
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
            return redirect()->back()->with('failure', 'Failed to update account information.');
        }
    }

    public function showAccountSA()
    {
        $worker = Auth::guard('staff')->user();
        return view('staff.profile-admin', compact('worker'));
    }

    public function showApplicants()
    {
        $applicants = applicants::with('educcollege', 'progress', 'educelemhs')
            ->orderByRaw("
            CASE
                WHEN applicationstatus = 'Under Review' THEN 1
                WHEN applicationstatus = 'Initial Interview' THEN 2
                WHEN applicationstatus = 'Home Visit' THEN 3
                WHEN applicationstatus = 'Panel Interview' THEN 4
                WHEN applicationstatus = 'Accepted' THEN 5
                WHEN applicationstatus = 'Denied' THEN 6
                WHEN applicationstatus = 'Withdrawn' THEN 7
                ELSE 8
            END")
            ->get();

        $data = [
            'totalapplicants' => applicants::get()->count(),
            'review' => applicants::where('applicationstatus', 'Under Review')->count(),
            'initial' => applicants::where('applicationstatus', 'Initial Interview')->count(),
            'home' => applicants::where('applicationstatus', 'Home Visit')->count(),
            'panel' => applicants::where('applicationstatus', 'Panel Interview')->count(),
            'accepted' => applicants::where('applicationstatus', 'Accepted')->count(),
            'denied' => applicants::where('applicationstatus', 'Denied')->count(),
            'withdrawn' => applicants::where('applicationstatus', 'Withdrawn')->count(),
        ];

        $college = apceducation::get()->count();
        $shs = apeheducation::whereIN('ingrade', ['Grade 11', 'Grade 12'])->get()->count();
        $jhs = apeheducation::whereIN('ingrade', ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])->get()->count();
        $elem = apeheducation::whereIN('ingrade', ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'])->get()->count();
        return view('staff.applicants', compact('data', 'applicants', 'college', 'shs', 'jhs', 'elem'));
    }

    public function showapplicantinfo($casecode)
    {
        $applicant = applicants::with('educcollege', 'educelemhs', 'otherinfo', 'requirements', 'casedetails')
            ->where('casecode', $casecode)
            ->first();
        $progress = [
            'Under Review' => approgress::where('casecode', $casecode)->where('phase', 'Under Review')->first(),
            'Initial Interview' => approgress::where('casecode', $casecode)->where('phase', 'Initial Interview')->first(),
            'Home Visit' => approgress::where('casecode', $casecode)->where('phase', 'Home Visit')->first(),
            'Panel Interview' => approgress::where('casecode', $casecode)->where('phase', 'Panel Interview')->first(),
        ];
        $father = apfamilyinfo::where('casecode', $casecode)
            ->where('relationship', 'Father')->first();
        $mother = apfamilyinfo::where('casecode', $casecode)
            ->where('relationship', 'Mother')->first();
        $siblings = apfamilyinfo::where('casecode', $casecode)
            ->where('relationship', 'Sibling')->get();
        $iscollege = apceducation::where('casecode', $casecode)->exists();

        if ($iscollege) {
            $form = applicationforms::where('formname', 'College')->first();
        } else {
            if ($applicant->educelemhs->schoollevel == 'Elementary') {
                $form = applicationforms::where('formname', 'College')->first();
            } else if ($applicant->educelemhs->schoollevel == 'Junior High') {
                $form = applicationforms::where('formname', 'Junior High')->first();
            } else if ($applicant->educelemhs->schoollevel == 'Senior High') {
                $form = applicationforms::where('formname', 'Senior High')->first();
            }
        }

        $needs = ['Financial', 'Medical', 'Food', 'Material', 'Education'];

        $worker = Auth::guard('staff')->user();

        return view('staff.applicant-info', compact('applicant', 'progress', 'needs', 'father', 'mother', 'siblings', 'iscollege', 'worker', 'form'));
    }

    public function downloadApplicationForm($casecode)
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
            $form = applicationforms::where('formname', 'College')->first();
        } else {
            if ($applicant->educelemhs->schoollevel == 'Elementary') {
                $form = applicationforms::where('formname', 'College')->first();
            } else if ($applicant->educelemhs->schoollevel == 'Junior High') {
                $form = applicationforms::where('formname', 'Junior High')->first();
            } else if ($applicant->educelemhs->schoollevel == 'Senior High') {
                $form = applicationforms::where('formname', 'Senior High')->first();
            }
        }

        // Prepare the data array with all necessary details
        $data = [
            'applicant'  => $applicant,
            'father'     => $father,
            'mother'     => $mother,
            'siblings'   => $siblings,
            'iscollege'       => $iscollege,
            'form'       => $form,
            'needs'      => ['Financial', 'Medical', 'Food', 'Material', 'Education']
        ];

        // Log the download activity
        $requestDetails = $this->getRequestDetails();
        $downloadedBy = Auth::guard('staff')->user(); // Adjust if you're using a different guard

        Log::info('Application form downloaded', [
            'casecode' => $casecode,
            'downloaded_by' => [
                'user_id' => $downloadedBy->id ?? 'Guest',
                'name' => $downloadedBy->name ?? 'Guest',
                'email' => $downloadedBy->email ?? 'Guest',
                'role' => $downloadedBy->role,
            ],
            'request_details' => $requestDetails,
            'timestamp' => now(),
        ]);

        $pdf = Pdf::loadView('application-form', $data);
        return $pdf->stream("Application-Form-{$applicant->casecode}.pdf");
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

            // Save old data for logging
            $oldData = $instruction->only(['applicants', 'qualifications', 'requireddocuments', 'applicationprocess']);

            $instruction = ApplicationInstruction::where('schoollevel', $level)->first();
            $instruction->applicants = $request->applicants;
            $instruction->qualifications = $request->qualifications;
            $instruction->requireddocuments = $request->documents;
            $instruction->applicationprocess = $request->process;
            $instruction->save();

            // Log the update activity
            $requestDetails = $this->getRequestDetails();
            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            Log::info('Application instructions updated', [
                'level' => $level,
                'updated_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $requestDetails,
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('success', "The Application Instruction for {$level} has been successfully updated.");
        } catch (\Exception $e) {

            $requestDetails = $this->getRequestDetails();
            Log::error('Failed to update application instructions', [
                'level' => $level,
                'error' => $e->getMessage(),
                'request_details' => $requestDetails,
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('failure', "Failed to update Application Instruction for {$level}." . $e->getMessage())->withInput();
        }
    }

    public function updateapplicantstatus($casecode, Request $request)
    {
        DB::beginTransaction();
        try {
            // dd($request->all());
            $request->validate([
                'status' => 'required|in:Passed,Failed',
                'remark' => 'required|string|max:255',
                'comment' => 'required|string|max:255',
                'natureofneeds' => 'nullable|required_if:cbcasedeets,on|string|max:50',
                'othersnatureofneeds' => 'nullable|required_if:natureofneeds,Others|string|max:50',
                'problemstatement' => 'nullable|required_if:cbcasedeets,on|string|max:255',
                'receivedby' => 'nullable|required_if:cbcasedeets,on|string|max:255',
                'datereceived' => 'nullable|required_if:cbcasedeets,on|date',
                'district' => 'nullable|required_if:cbcasedeets,on|string|max:50',
                'volunteer' => 'nullable|required_if:cbcasedeets,on|string|max:255',
                'referredby' => 'nullable|string|max:255',
                'referphonenum' => 'nullable|string|digits_between:11,12',
                'relationship' => 'nullable|string|max:255',
                'datereported' => 'nullable|date',
            ]);

            $nextphase = match ($request->curphase) {
                'Under Review' => 'Initial Interview',
                'Initial Interview' => 'Home Visit',
                'Home Visit' => 'Panel Interview',
                'Panel Interview' => 'Accepted',
                default => null,
            };

            $applicant = applicants::where('casecode', $casecode)->first();
            if ($request->status == 'Passed') {
                $applicant->applicationstatus = $nextphase;
                $applicant->save();

                if ($nextphase == 'Accepted') {
                    $this->createScholarAccount($casecode);
                }
            } else {
                $applicant->applicationstatus = 'Denied';
                $applicant->save();
            }

            approgress::create([
                'casecode' => $casecode,
                'phase' => $request->curphase,
                'status' => $request->status,
                'remark' => $request->remark,
                'msg' => $request->comment,
            ]);

            if ($request->cbcasedeets === 'on') {
                apcasedetails::create([
                    'casecode' => $casecode,
                    'natureofneeds' => $request->natureofneeds == 'Others' ? $request->othersnatureofneeds : $request->natureofneeds,
                    'problemstatement' => $request->problemstatement,
                    'receivedby' => $request->receivedby,
                    'datereceived' => $request->datereceived,
                    'district' => $request->district,
                    'volunteer' => $request->volunteer,
                    'referredby' => $request->referredby ?? null,
                    'referphonenum' => $request->referphonenum ?? null,
                    'relationship' => $request->relationship ?? null,
                    'datereported' => $request->datereported ?? null,
                ]);
            }

            DB::commit();

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Logging update
            Log::info('Applicant status updated', [
                'casecode' => $casecode,
                'updated_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);


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

            return redirect()->back()->with('success', "Successfully updated application progress of applicant {$casecode}.");
        } catch (ValidationException $e) {
            DB::rollback();

            // Log error
            Log::error('Validation error during application status update', [
                'error' => $e->getMessage(),
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            $errors = $e->errors();
            $errorMessages = '<ul>';
            foreach ($errors as $fieldErrors) {
                foreach ($fieldErrors as $errorMessage) {
                    $errorMessages .= '<li>' . $errorMessage . '</li>';
                }
            }
            $errorMessages .= '</ul>';
            return redirect()->back()->with('failure', 'Updating application progress has failed due to the following errors: ' . $errorMessages)->withInput();
        } catch (\Exception $e) {
            // Roll back the transaction in case of error
            DB::rollBack();

            // Log error
            Log::error('Exception during application status update', [
                'error' => $e->getMessage(),
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('failure', 'Failed to update application progress. ')->withInput();
        }
    }

    private function createScholarAccount($casecode)
    {
        $applicant = applicants::with('educcollege', 'educelemhs', 'otherinfo', 'requirements', 'casedetails')
            ->where('casecode', $casecode)
            ->first();
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


            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard
            // Log the update
            Log::info('Applicant case details updated', [
                'casecode' => $casecode,
                'updated_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('success', "Successfully updated case details of applicant.");
        } catch (\Exception $e) {

            // Log the error
            Log::error('Error updating applicant case details', [
                'error' => $e->getMessage(),
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            DB::rollBack();
            return redirect()->back()->with('failure', 'Failed to update case details of applicant. ');
        }
    }

    public function  updateappformstatus($formname, $status, Request $request)
    {
        DB::beginTransaction();
        try {
            $form = applicationforms::where('formname', $formname)->first();
            $form->deadline = $status == 'Open' ? $request->deadline : NULL;
            $form->status = $status;
            $form->save();

            DB::commit();

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard
            // Log the form status update
            Log::info('Application form status updated', [
                'formname' => $formname,
                'status' => $status,
                'updated_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            if ($status == 'Open') {

                if ($formname == 'Renewal') {

                    $api_key = env('MOVIDER_API_KEY');
                    $api_secret = env('MOVIDER_API_SECRET');

                    $users = User::all(); // Fetch all users
                    $client = new \GuzzleHttp\Client();

                    $failedSMS = [];
                    $failedEmail = [];
                    $message = 'Renewal application is now open. Deadline: ' . $form->deadline;

                    foreach ($users as $user) {
                        if ($user->notification_preference === 'sms') {
                            // Send SMS using Movider API
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
                                if (!isset($decodedResponse['phone_number_list']) || !is_array($decodedResponse['phone_number_list']) || count($decodedResponse['phone_number_list']) == 0) {
                                    $failedSMS[] = $user->scPhoneNum; // Track failed SMS
                                }
                            } catch (\Exception $e) {
                                $failedSMS[] = $user->scPhoneNum;
                                Log::info('Movider SMS Error', ['error' => $e->getMessage()]);
                            }
                        } else {
                            // Send email notification
                            try {
                                $user->notify(new RenewalUpdate($form)); // Notify via email
                            } catch (\Exception $e) {
                                $failedEmail[] = $user->email; // Track failed emails
                                Log::info('Email Notification Error', ['error' => $e->getMessage()]);
                            }
                        }
                    }

                    // Log any failed notifications
                    if (!empty($failedSMS)) {
                        Log::warning('Failed to send SMS to:', $failedSMS);
                    }
                    if (!empty($failedEmail)) {
                        Log::warning('Failed to send emails to:', $failedEmail);
                    }
                }


                return redirect()->back()->with('success', "{$formname} application is now open.");
            } else {
                return redirect()->back()->with('success', "{$formname} application is now closed.");
            }
        } catch (\Exception $e) {
            // Roll back the transaction in case of error
            DB::rollBack();
            Log::error('Error updating form status', [
                'error' => $e->getMessage(),
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);
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

            $api_key = env('MOVIDER_API_KEY');
            $api_secret = env('MOVIDER_API_SECRET');

            $user = User::where('caseCode', $caseCode)->first();

            // Initialize the Guzzle client
            $client = new \GuzzleHttp\Client();

            // Track failed SMS and failed email notifications
            $failedSMS = [];
            $failedEmail = [];
            $message = 'Scholarshipt Status Update';

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
                    $user->notify(new ScholarshipStatus($scholar));
                } catch (\Exception $e) {
                    // If email notification failed, add to failed list
                    $failedEmail[] = $user->email;
                }
            }

            DB::commit();

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard
            // Log the scholarship status update
            Log::info('Scholarship status updated', [
                'caseCode' => $caseCode,
                'scholarshipstatus' => $request->scholarshipstatus,
                'updated_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('success', 'Successfully updated scholarship status.');
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
            DB::rollBack();
            return redirect()->back()->with('failure', 'Failed to update scholarship status. ');
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


            $api_key = env('MOVIDER_API_KEY');
            $api_secret = env('MOVIDER_API_SECRET');

            $user = User::where('caseCode', $grade->caseCode)->first();

            // Initialize the Guzzle client
            $client = new \GuzzleHttp\Client();

            // Track failed SMS and failed email notifications
            $failedSMS = [];
            $failedEmail = [];
            $message = 'Updated Grade Status';

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
                    $user->notify(new GradeUpdate($grade));
                } catch (\Exception $e) {
                    // If email notification failed, add to failed list
                    $failedEmail[] = $user->email;
                }
            }

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

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Log the grade status update
            Log::info('Grade status updated', [
                'gid' => $gid,
                'gradestatus' => $request->gradestatus,
                'updated_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('success', 'Successfully updated grade status.');
        } catch (\Exception $e) {
            // Roll back the transaction in case of error
            DB::rollBack();
            return redirect()->back()->with('failure', 'Failed to update grade status: ');
        }
    }

    public function showLTE()
    {
        $lte = lte::orderByRaw('
                    CASE 
                        WHEN ltestatus = "To Review" THEN 1
                        WHEN ltestatus = "No Response" THEN 2
                        WHEN ltestatus = "Excused" THEN 3
                        WHEN ltestatus = "Continuing" THEN 4
                        WHEN ltestatus = "Unexcused" THEN 5
                        WHEN ltestatus = "Terminated" THEN 6
                        ELSE 7
                    END
                ')
            ->orderBy('created_at', 'ASC')
            ->get();

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
            DB::beginTransaction();
            $letter = lte::where('lid', $lid)->first();
            $letter->ltestatus = $request->ltestatus;
            $letter->save();
            $scinfo = scholarshipinfo::where('caseCode', $letter->caseCode)->first();


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

                    $api_key = env('MOVIDER_API_KEY');
                    $api_secret = env('MOVIDER_API_SECRET');

                    $user = User::where('caseCode', $letter->caseCode)->first();

                    // Initialize the Guzzle client
                    $client = new \GuzzleHttp\Client();

                    // Track failed SMS and failed email notifications
                    $failedSMS = [];
                    $failedEmail = [];
                    $message = 'scholarship status update';

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
                            $user->notify(new LteStatusUpdate($scinfo));
                        } catch (\Exception $e) {
                            // If email notification failed, add to failed list
                            $failedEmail[] = $user->email;
                        }
                    }
                }

                if (
                    ($remark == '3rd Offense' && $letter->violation == 'Absent' && $letter->eventtype == 'Humanities Class') ||
                    ($remark == '4th Offense' && in_array($letter->violation, ['Late', 'Left Early']) && $letter->eventtype == 'Humanities Class')
                ) {
                    if ($scinfo) {
                        $scinfo->scholarshipstatus = 'On-Hold';
                        $scinfo->save();

                        $api_key = env('MOVIDER_API_KEY');
                        $api_secret = env('MOVIDER_API_SECRET');

                        $user = User::where('caseCode', $letter->caseCode)->first();

                        // Initialize the Guzzle client
                        $client = new \GuzzleHttp\Client();

                        // Track failed SMS and failed email notifications
                        $failedSMS = [];
                        $failedEmail = [];
                        $message = 'scholarship status update';

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
                                $user->notify(new LteStatusUpdate($scinfo));
                            } catch (\Exception $e) {
                                // If email notification failed, add to failed list
                                $failedEmail[] = $user->email;
                            }
                        }
                    }
                }

                penalty::create([
                    'caseCode' => $letter->caseCode,
                    'condition' => $condition,
                    'conditionid' => $lid,
                    'remark' => $remark,
                    'dateofpenalty' => $date,
                ]);
            } else if ($request->ltestatus == 'Continuing') {
                $scinfo->scholarshipstatus = 'Continuing';
                $scinfo->save();

                $api_key = env('MOVIDER_API_KEY');
                $api_secret = env('MOVIDER_API_SECRET');

                $user = User::where('caseCode', $letter->caseCode)->first();

                // Initialize the Guzzle client
                $client = new \GuzzleHttp\Client();

                // Track failed SMS and failed email notifications
                $failedSMS = [];
                $failedEmail = [];
                $message = 'scholarship status update';

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
                        $user->notify(new LteStatusUpdate($scinfo));
                    } catch (\Exception $e) {
                        // If email notification failed, add to failed list
                        $failedEmail[] = $user->email;
                    }
                }
            }

            DB::commit();

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            Log::info('Penalty successfully created', [
                'user_lid' => $lid,
                'user_scholarshipstatus' => $scinfo->scholarshipstatus,
                'created_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);


            return redirect()->back()->with('success', 'Successfully updated LTE status.');
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
            DB::rollBack();

            return redirect()->back()->with('failure', 'Failed to update LTE status. ');
        }
    }

    public function showPenalty()
    {
        // Fetch all penalties with related basicInfo, ordered by dateofpenalty (latest first)
        $penaltys = Penalty::with('basicInfo')
            ->orderBy('dateofpenalty', 'desc') // Order by penalty date to get the latest
            ->get();

        // Group the penalties by caseCode
        $penaltysGrouped = $penaltys->groupBy('caseCode')->map(function ($penaltyGroup, $caseCode) {
            $conditions = $penaltyGroup->pluck('condition')->unique()->join('<br>');
            $basicInfo = $penaltyGroup->first()->basicInfo;
            return [
                'caseCode' => $caseCode,
                'conditions' => $conditions,
                'basicInfo' => $basicInfo,
            ];
        });

        // Get all scholars
        $scholars = User::with('basicInfo')->get();

        // Get the latest penalty for each scholar
        $penalties = [];
        foreach ($scholars as $scholar) {
            $latestPenalty = Penalty::where('caseCode', $scholar->caseCode)
                ->latest('dateofpenalty')
                ->first();
            $penalties[$scholar->caseCode] = $latestPenalty;
        }

        return view('staff.penalty', compact('penalties', 'scholars', 'penaltysGrouped', 'penaltys'));
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

        $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

        // Log the beginning of penalty creation
        Log::info('Penalty creation initiated', [
            'scholar_id' => $validatedData['scholar_id'],
            'condition' => $validatedData['condition'],
            'updated_by' => [
                'user_id' => $updatedBy->id ?? 'Guest',
                'name' => $updatedBy->name ?? 'Guest',
                'email' => $updatedBy->email ?? 'Guest',
            ],
            'request_details' => $this->getRequestDetails(),
            'timestamp' => now(),
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

        // Log penalty creation
        Log::info('Penalty successfully created', [
            'penalty_id' => $penalty->id,
            'scholar_id' => $validatedData['scholar_id'],
            'condition' => $validatedData['condition'],
            'remark' => $remark,
            'dateofpenalty' => $date,
            'created_by' => [
                'user_id' => $updatedBy->id ?? 'Guest',
                'name' => $updatedBy->name ?? 'Guest',
                'email' => $updatedBy->email ?? 'Guest',
            ],
            'request_details' => $this->getRequestDetails(),
            'timestamp' => now(),
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

    public function showQualification(Request $request)
    {
        // Retrieve all necessary data
        $forms = applicationforms::all();
        $criteria = criteria::all();
        $areas = Areas::orderBy('areaname')->get();
        $institutions = institutions::orderByRaw('CASE
            WHEN schoollevel = "Elementary" THEN 4
            WHEN schoollevel = "Junior High" THEN 3
            WHEN schoollevel = "Senior High" THEN 2
            WHEN schoollevel = "College" THEN 1
            ELSE 5 END')
            ->orderBy('schoolname')->get();

        $courses = courses::where('level', 'College')->orderBy('coursename')->get();
        $strands = courses::where('level', 'Senior High')->orderBy('coursename')->get();

        // Retrieve ApplicationInstruction grouped by schoollevel
        $instructionLevels = ['College', 'Senior High', 'Junior High', 'Elementary'];
        $instructions = ApplicationInstruction::whereIn('schoollevel', $instructionLevels)
            ->get()
            ->keyBy('schoollevel');

        // Prepare instructions array to match the desired format
        $instruction = [];
        foreach ($instructionLevels as $level) {
            $instruction[$level] = $instructions->get($level, null);
        }

        // Return all data to the view
        return view('staff.qualification', compact(
            'areas',
            'criteria',
            'institutions',
            'courses',
            'strands',
            'forms',
            'instruction',
            'instructionLevels',
        ));
    }

    public function fetchSchool(Request $request)
    {
        $inputSchool = $request->input('institute');

        if (!$inputSchool) {
            return response()->json(null, 400); // Bad request if no input is provided
        }

        // Perform a case-insensitive search for the school
        $school = KnownSchools::whereRaw('LOWER(schoolname) = ?', [strtolower($inputSchool)])
            ->orWhereRaw('? LIKE CONCAT("%", schoolname, "%")', [$inputSchool])
            ->first();

        return response()->json($school); // Return school data or null if not found
    }

    public function addCriteria(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate the incoming request
            $validated = $request->validate([
                'criteriaName' => 'required|string|max:255',
                'criteriaValue' => 'required',
            ]);

            // Save the new criterion
            criteria::create([
                'criteria_name' => $validated['criteriaName'],
                'criteria_value' => $validated['criteriaValue'],
            ]);

            // Commit the transaction
            DB::commit();

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Log successful creation of the criteria
            Log::info('Criteria successfully added', [
                'criteria_name' => $validated['criteriaName'],
                'criteria_value' => $validated['criteriaValue'],
                'added_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('success', 'New criteria added successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollback();

            // Log the error for debugging purposes
            Log::error('Error adding criteria: ' . $e->getMessage());

            return redirect()->back()->with('failure', 'Unable to add new criteria. Please try again.');
        }
    }


    public function updatecriteria(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate the incoming request
            $validated = $request->validate([
                'criteria.*.name' => 'required|string|max:255',
                'criteria.*.value' => 'required|numeric|min:1',
            ]);

            // Loop through the submitted data
            foreach ($validated['criteria'] as $crid => $data) {
                criteria::where('crid', $crid)->update([
                    'criteria_name' => $data['name'],
                    'criteria_value' => $data['value'],
                ]);
            }

            DB::commit();


            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Log the successful update
            Log::info('Criteria successfully updated', [
                'updated_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('success', 'Criteria updated successfully.');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
            DB::rollback();
            return redirect()->back()->with('failure', 'Unable to update criteria. Please try again.');
        }
    }


    public function deletecriteria($id)
    {
        try {
            Criteria::findOrFail($id)->delete();

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Log the successful deletion
            Log::info('Criterion successfully deleted', [
                'criterion_id' => $id,
                'deleted_by' => [
                    'user_id' => $deletedBy->id ?? 'Guest',
                    'name' => $deletedBy->name ?? 'Guest',
                    'email' => $deletedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('success', 'Criterion deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete criterion.');
        }
    }

    public function addArea(Request $request)
    {
        try {
            $request->validate([
                'areaname' => 'required|string|max:100',
                'areacode' => 'required|string|max:3',
            ]);

            $nameExists = Areas::where('areaname', $request->areaname)
                ->exists();

            $codeExists = Areas::where('areacode', $request->areacode)
                ->exists();

            if ($nameExists) {
                return redirect()->back()->with('failure', 'Failed to update area. Duplicate area name.')->withInput();
            } else if ($codeExists) {
                return redirect()->back()->with('failure', 'Failed to update area. Duplicate area code.')->withInput();
            }

            DB::beginTransaction();
            Areas::create([
                'areaname' => $request->areaname,
                'areacode' => $request->areacode,
            ]);

            DB::commit();

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard
            // Log successful area addition
            Log::info('Area added successfully', [
                'areaname' => $request->areaname,
                'areacode' => $request->areacode,
                'created_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),

                'timestamp' => now(),
            ]);

            return redirect()->back()->with('success', "Successfully added area: {$request->areaname}.");
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
            DB::rollback();
            Log::error("Failed to update area: {$e->getMessage()}");
            return redirect()->back()->with('failure', "Failed to add area.")->withInput();
        }
    }

    public function updateArea($id, Request $request)
    {
        try {
            $request->validate([
                'newareaname' => 'required|string|max:100',
                'newareacode' => 'required|string|max:3',
            ]);

            $nameExists = Areas::where('areaname', $request->newareaname)
                ->where('id', '!=', $id)
                ->exists();

            $codeExists = Areas::where('areacode', $request->newareacode)
                ->where('id', '!=', $id)
                ->exists();

            if ($nameExists) {
                return redirect()->back()->with('failure', 'Failed to update area. Duplicate area name.');
            } else if ($codeExists) {
                return redirect()->back()->with('failure', 'Failed to update area. Duplicate area code.');
            }

            $area = Areas::where('id', $id)->first();
            DB::beginTransaction();
            $area->areaname = $request->newareaname;
            $area->areacode = $request->newareacode;
            $area->save();
            DB::commit();

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard
            // Log successful update
            Log::info('Area updated successfully', [
                'area_id' => $id,
                'newareaname' => $request->newareaname,
                'newareacode' => $request->newareacode,
                'created_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('success', "Successfully updated area: {$request->newareaname}.");
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
            DB::rollback();
            Log::error("Failed to update area: {$e->getMessage()}");
            return redirect()->back()->with('failure', "Failed to update area.");
        }
    }

    public function deleteArea($id)
    {
        DB::beginTransaction();
        try {
            $area = Areas::findOrFail($id);
            $area->delete();

            DB::commit();

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Log successful deletion
            Log::info('Area successfully deleted', [
                'area_id' => $id,
                'areaname' => $area->areaname,
                'created_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('success', "Successfully deleted the area: {$area->areaname}.");
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
            DB::rollback();
            Log::error("Failed to delete area: {$e->getMessage()}");
            return redirect()->back()->with('failure', "Failed to delete area.");
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

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Log the successful addition
            Log::info('Institution added successfully', [
                'schoolname' => $request->institute,
                'schoollevel' => $request->schoollevel,
                'academiccycle' => $request->academiccycle,
                'highestgwa' => $request->highestgwa,
                'created_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('success', 'Successfully added an institution.');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('failure', $e->getMessage());
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
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

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Log successful update
            Log::info('Institution updated successfully', [
                'institution_id' => $inid,
                'newschoolname' => $request->newschoolname,
                'newschoollevel' => $request->newschoollevel,
                'newacademiccycle' => $request->newacademiccycle,
                'newgwa' => $request->newgwa,
                'created_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('success', 'Successfully updated the institution.');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('failure', $e->getMessage());
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
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

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Log successful deletion
            Log::info('Institution deleted successfully', [
                'institution_id' => $inid,
                'schoolname' => $institution->schoolname,
                'created_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('success', 'Successfully deleted the institution.');
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
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


                $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

                // Log the successful addition of a course
                Log::info('Successfully added a course', [
                    'level' => $level,
                    'coursename' => $request->course,
                    'created_by' => [
                        'user_id' => $updatedBy->id ?? 'Guest',
                        'name' => $updatedBy->name ?? 'Guest',
                        'email' => $updatedBy->email ?? 'Guest',
                    ],
                    'request_details' => $this->getRequestDetails(),
                    'timestamp' => now(),
                ]);

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

                $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

                // Log the successful addition of a course
                Log::info('Successfully added a course', [
                    'level' => $level,
                    'coursename' => $request->course,
                    'created_by' => [
                        'user_id' => $updatedBy->id ?? 'Guest',
                        'name' => $updatedBy->name ?? 'Guest',
                        'email' => $updatedBy->email ?? 'Guest',
                    ],
                    'request_details' => $this->getRequestDetails(),
                    'timestamp' => now(),
                ]);

                return redirect()->back()->with('success', 'Successfully added a strand.');
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

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard
            // Log the successful update
            Log::info("Successfully updated {$type}", [
                'course_id' => $coid,
                'newcoursename' => $request->newcoursename,
                'type' => $type,
                'created_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('success', "Successfully updated {$type}.");
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('failure', $e->getMessage());
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
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
            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Log successful deletion
            Log::info("Successfully deleted {$type}", [
                'course_id' => $coid,
                'coursename' => $course->coursename,
                'type' => $type,
                'deleted_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('success', "Successfully deleted {$type}.");
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
            DB::rollback();
            return redirect()->back()->with('failure', "Failed to delete {$type}.");
        }
    }

    public function showRenewal()
    {
        // Get renewal form information
        $renewal = applicationforms::where('formname', 'Renewal')->first();

        $startdate = $renewal->updated_at->format('Y-m-d');
        $enddate = $renewal->deadline
            ? Carbon::parse($renewal->deadline)->format('Y-m-d')
            : now()->addYear()->format('Y-m-d');

        // Generate summary data
        $summary = [
            'totalrenew' => renewal::whereBetween('datesubmitted', [$startdate, $enddate])->count(),
            'pending' => renewal::where('status', 'Pending')->whereBetween('datesubmitted', [$startdate, $enddate])->count(),
            'approved' => renewal::where('status', 'Accepted')->whereBetween('datesubmitted', [$startdate, $enddate])->count(),
            'rejected' => renewal::where('status', 'Rejected')->whereBetween('datesubmitted', [$startdate, $enddate])->count(),
        ];

        // Fetch data for College
        $college = Renewal::with('otherinfo', 'casedetails', 'grade', 'education', 'basicInfo')
            ->whereHas('education', function ($query) {
                $query->where('scSchoolLevel', 'College');
            })
            ->whereBetween('datesubmitted', [$startdate, $enddate])
            ->orderBy('datesubmitted', 'ASC')
            ->orderByRaw("
            CASE
                WHEN status = 'Pending' THEN 1 
                WHEN status = 'Approved' THEN 2  
                WHEN status = 'Rejected' THEN 3 
                WHEN status = 'Withdrawn' THEN 4
                ELSE 5
            END
        ")
            ->get();

        // Fetch data for Senior High School
        $shs = Renewal::with('otherinfo', 'casedetails', 'grade', 'education', 'basicInfo')
            ->whereHas('education', function ($query) {
                $query->where('scSchoolLevel', 'Senior High');
            })
            ->whereBetween('datesubmitted', [$startdate, $enddate])
            ->orderBy('datesubmitted', 'ASC')
            ->orderByRaw("
        CASE
            WHEN status = 'Pending' THEN 1 
            WHEN status = 'Approved' THEN 2  
            WHEN status = 'Rejected' THEN 3 
            WHEN status = 'Withdrawn' THEN 4
            ELSE 5
        END
        ")
            ->get();

        // Fetch data for Junior High School
        $jhs = Renewal::with('otherinfo', 'casedetails', 'grade', 'education', 'basicInfo')
            ->whereHas('education', function ($query) {
                $query->where('scSchoolLevel', 'Junior High');
            })
            ->whereBetween('datesubmitted', [$startdate, $enddate])
            ->orderBy('datesubmitted', 'ASC')
            ->orderByRaw("
        CASE
            WHEN status = 'Pending' THEN 1 
            WHEN status = 'Approved' THEN 2  
            WHEN status = 'Rejected' THEN 3 
            WHEN status = 'Withdrawn' THEN 4
            ELSE 5
        END
        ")
            ->get();

        // Fetch data for Elementary
        $elem = Renewal::with('otherinfo', 'casedetails', 'grade', 'education', 'basicInfo')
            ->whereHas('education', function ($query) {
                $query->where('scSchoolLevel', 'Elementary');
            })
            ->whereBetween('datesubmitted', [$startdate, $enddate])
            ->orderBy('datesubmitted', 'ASC')
            ->orderByRaw("
        CASE
            WHEN status = 'Pending' THEN 1 
            WHEN status = 'Approved' THEN 2  
            WHEN status = 'Rejected' THEN 3 
            WHEN status = 'Withdrawn' THEN 4
            ELSE 5
        END
        ")
            ->get();

        // Pass data to the view
        return view('staff.renewal', compact('summary', 'college', 'shs', 'jhs', 'elem'));
    }

    public function showRenewalinfo($id)
    {
        $worker = Auth::guard('staff')->user();
        $renewal = renewal::with('grade', 'casedetails', 'otherinfo')->where('rid', $id)->first();
        $user = User::with(
            'basicInfo',
            'addressinfo',
            'education',
            'scholarshipinfo'
        )->where('caseCode', $renewal->caseCode)
            ->first();

        $iscollege = ScEducation::where('scSchoolLevel', 'College')->where('caseCode', $user->caseCode)->exists();


        $father = RnwFamilyInfo::where('caseCode', $user->caseCode)->where('relationship', 'Father')->first();
        $mother = RnwFamilyInfo::where('caseCode', $user->caseCode)->where('relationship', 'Mother')->first();
        $siblings = RnwFamilyInfo::where('caseCode', $user->caseCode)->where('relationship', 'Sibling')->get();

        $form = applicationforms::where('formname', 'Renewal')->first();

        $needs = ['Financial', 'Medical', 'Food', 'Material', 'Education'];

        return view('staff.renewalinfo', compact('needs', 'user', 'father', 'mother', 'siblings', 'form', 'renewal', 'iscollege', 'worker'));
    }

    public function updateRenewalInfo($id, Request $request)
    {
        try {
            // Server-side validation
            $request->validate([
                'renewalstatus' => 'required|in:Accepted,Rejected',
                'natureofneeds' => 'required|string|max:50',
                'problemstatement' => 'required|string|max:255',
                'receivedby' => 'required|string|max:255',
                'datereceived' => 'required|date|before_or_equal:today',
                'district' => 'required|string|max:50',
                'volunteer' => 'required|string|max:255',
                'referredby' => 'required|string|max:255',
                'referphonenum' => 'required|min:11|max:12',
                'relationship' => 'required|string|max:50',
                'datereported' => 'required|date',
            ]);

            // Start transaction
            DB::beginTransaction();

            // Find the renewal record by ID
            $renewal = Renewal::findOrFail($id);  // Use `findOrFail()` to retrieve the model or throw a 404 error

            // Update the renewal status
            $renewal->status = $request->renewalstatus;
            $renewal->save();  // Persist changes

            // Create or update case details
            RnwCaseDetails::create([
                'rid' => $id,
                'natureofneeds' => $request->natureofneeds,
                'problemstatement' => $request->problemstatement,
                'receivedby' => $request->receivedby,
                'datereceived' => $request->datereceived,
                'district' => $request->district,
                'volunteer' => $request->volunteer,
                'referredby' => $request->referredby,
                'referphonenum' => $request->referphonenum,
                'relationship' => $request->relationship,
                'datereported' => $request->datereported
            ]);

            // Commit transaction
            DB::commit();


            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Log success
            Log::info('Successfully updated renewal info and case details', [
                'renewal_id' => $id,
                'status' => $request->renewalstatus,
                'requester' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            // Return success response
            return redirect()->back()->with('success', 'Successfully updated renewal status and case details.');
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            // Log error with details
            Log::error("Error: {$e->getMessage()}", ['exception' => $e]);

            // Return failure response with error message
            return redirect()->back()->with('failure', 'Failed to update renewal status and case details. ')->withInput();
        }
    }

    public function showAllowanceRegular()
    {
        $requests = RegularAllowance::with('basicInfo', 'education')
            ->orderBy('created_at', 'DESC')
            ->orderBy('schoolyear', 'DESC')
            ->orderByRaw("CASE
                WHEN semester = '1st Semester' THEN 3
                WHEN semester = '2nd Semester' THEN 2
                WHEN semester = '3rd Semester' THEN 1
                ELSE 4
                END")->get();

        $data = [
            'All' => RegularAllowance::all()->count(),
            'Pending' => RegularAllowance::where('status', 'Pending')->count(),
            'Completed' => RegularAllowance::where('status', 'Completed')->count(),
        ];

        return view('staff.regularallowance', compact('requests', 'data'));
    }

    public function viewAllowanceRegularInfo($id)
    {
        $req = RegularAllowance::with([
            'classReference.classSchedules',
            'travelItinerary.travelLocations',
            'lodgingInfo',
            'ojtTravelItinerary.ojtLocations'
        ])->findOrFail($id);

        $data = User::with(['basicInfo', 'education', 'scholarshipinfo', 'addressinfo'])
            ->where('caseCode', $req->caseCode)
            ->first();

        $worker = staccount::where('area', $data->scholarshipinfo->area)->first();
        return view('staff.regularallowanceinfo', compact('data', 'req', 'worker'));
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

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard


            // Log the successful update
            Log::info('Regular allowance updated successfully', [
                'regularID' => $id,
                'new_status' => $req->status,
                'date_of_release' => $req->date_of_release ?? 'Not set',
                'requestor' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);


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
            Log::error("Error: {$e->getMessage()}");
            DB::rollBack();
            return redirect()->back()->with('failure', 'Unable to update request. ');
        }
    }

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
                return view('scholar.allowancerequest.scspecial', compact('scholars', 'forms'))->with('failure', 'File Not Found.');
            }

            // Read the Excel file from the public disk
            $excelData = Excel::toArray([], Storage::disk('public')->path($filePath));

            // Ensure the file is not empty
            if (!empty($excelData[0])) {
                // The first row contains the column headers
                $headers = $excelData[0][0];

                // Skip the first row (headers) and map each remaining row
                $mappedData = array_map(function ($row) use ($headers) {
                    // Combine each row with headers as keys
                    return array_combine($headers, $row);
                }, array_slice($excelData[0], 1)); // Skip the first row (headers)

                // Define the custom sorting order for requestStatus
                $statusOrder = ['Pending' => 1, 'Accepted' => 2, 'Completed' => 3, 'Rejected' => 4];

                // Sort the mapped data based on requestStatus
                usort($mappedData, function ($a, $b) use ($statusOrder) {
                    $statusA = $statusOrder[$a['requestStatus']] ?? 5; // Default to 5 for unknown statuses
                    $statusB = $statusOrder[$b['requestStatus']] ?? 5;
                    return $statusA <=> $statusB;
                });

                // Store the sorted data
                $data[$formname] = $mappedData;
            } else {
                // If the file is empty, set an empty array
                $data[$formname] = [];
            }
        }

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

    public function updatespecreq(Request $request)
    {
        try {
            DB::beginTransaction();
            // Validate the request
            $validated = $request->validate([
                'requestId' => 'required|integer',
                'requestType' => 'required|string',
                'requestStatus' => 'required|string',
                'oldStatus' => 'required|string',
                'releasedate' => 'nullable|date',
            ]);

            // Retrieve the form based on the type
            $form = CreateSpecialAllowanceForm::where('formname', $request->requestType)->first();

            if (!$form) {
                return back()->with('failure', 'Form not found.');
            }

            // Get the file path from the form's database column
            $filePath = $form->database;

            // Check if the file exists in the public disk
            if (!Storage::disk('public')->exists($filePath)) {
                return back()->with('failure', 'File not found.');
            }

            // Load the Excel file using PhpSpreadsheet
            $fileFullPath = Storage::disk('public')->path($filePath);
            $spreadsheet = IOFactory::load($fileFullPath);
            $sheet = $spreadsheet->getActiveSheet();

            // Get the headers (first row)
            $headers = $sheet->rangeToArray('A1:' . $sheet->getHighestColumn() . '1')[0];

            // Find the index of key columns
            $idIndex = array_search('id', $headers);
            $statusIndex = array_search('requestStatus', $headers);
            $releaseDateIndex = array_search('releaseDate', $headers);

            if ($idIndex === false || $statusIndex === false || $releaseDateIndex === false) {
                return back()->with('failure', 'Required columns are missing in the file.');
            }

            // Iterate through the rows to find the matching record
            $rowIterator = $sheet->getRowIterator(2); // Start from the second row (data rows)
            foreach ($rowIterator as $row) {
                $rowIndex = $row->getRowIndex();
                $rowData = $sheet->rangeToArray("A{$rowIndex}:" . $sheet->getHighestColumn() . "{$rowIndex}")[0];

                if ($rowData[$idIndex] == $request->requestId) {
                    // Calculate column letters for the status and release date
                    $statusColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($statusIndex + 1);
                    $releaseDateColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($releaseDateIndex + 1);

                    // Update the relevant cells
                    $sheet->setCellValue("{$statusColumn}{$rowIndex}", $request->requestStatus);
                    $sheet->setCellValue("{$releaseDateColumn}{$rowIndex}", $request->releasedate ?? '');

                    break; // Stop after finding and updating the record
                }
            }

            // Save the updated file back to the same location
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($fileFullPath);

            $summary = SpecialAllowanceSummary::first();

            // Decrement the count for the old status
            $oldStatus = strtolower($request->oldStatus);
            if (in_array($request->oldStatus, ['Pending', 'Accepted', 'Completed', 'Rejected'])) {
                $summary->{$oldStatus}--;
            }

            // Increment the count for the new status
            $newStatus = strtolower($request->requestStatus);  // Assuming `requestStatus` holds the new status
            if (in_array($request->requestStatus, ['Accepted', 'Completed', 'Rejected'])) {
                $summary->{$newStatus}++;
            }

            $summary->save();
            DB::commit();

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard


            $specialUpdateInfo = [
                'request_id' => $request->requestId,
                'old_status' => $request->oldStatus,
                'new_status' => $request->requestStatus,
                'releasedate' => $request->releasedate ?? null,
                'created_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ];

            Log::info('Special Request Status Updated', $specialUpdateInfo);

            return back()->with('success', 'Successfully updated request status.');
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
            DB::rollBack();
            return back()->with('failure', 'Failed to update request status.');
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

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Log commit success
            Log::info('Transaction committed successfully', [
                'csafid' => $csafId,
                'created_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);


            // Return success message
            return redirect()->back()->with('success', 'Special Allowance Form created successfully.');
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
            DB::rollBack();

            // Return error message
            return redirect()->back()->with('failure', 'An error occurred while creating the form: ')->withInput();
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

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            Log::info('Special Allowance Form updated successfully', [
                'form_id' => $form->csafid,
                'formname' => $form->formname,
                'updated_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            // Return success message
            return redirect()->back()->with('success', 'Special Allowance Form updated successfully.');
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
            DB::rollBack();

            // Return error message if any exception occurs
            return redirect()->back()->with('failure', 'An error occurred while updating the form: ')->withInput();
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

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            Log::info('Special Allowance Form deleted successfully', [
                'form_id' => $form->csafid,
                'formname' => $form->formname,
                'deleted_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

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

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            Log::info('Downloadable file uploaded successfully', [
                'filename' => $request->filename,
                'file_path' => $path,
                'uploaded_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return redirect()->back()->with('success', "Successfully uploaded {$request->filename}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('failure', 'Duplicate file. Please try again.');
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

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard


            Log::info('Downloadable file updated successfully', [
                'old_filename' => $file->filename,
                'new_filename' => $request->newfilename,
                'old_file_path' => $file->pathname,
                'new_file_path' => $path,
                'updated_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

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

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard
            Log::info('Downloadable file deleted successfully', [
                'filename' => $file->filename,
                'file_path' => $file->pathname,
                'deleted_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

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

        $scholars = scholarshipinfo::with('basicInfo', 'education', 'User')
            ->orderByRaw("CASE 
                        WHEN scholarshipstatus = 'Continuing' THEN 1
                        WHEN scholarshipstatus = 'On-Hold' THEN 2
                        WHEN scholarshipstatus = 'Terminated' THEN 3
                        ELSE 4
                      END")
            ->get();

        return view('staff.scholars', compact(
            'totalscholars',
            'totalnewscholars',
            'areas',
            'scholarsperarea',
            'scholarsperlevel',
            'scholars'
        ));
    }

    public function showUsersScholar()
    {
        $scholarAccounts = User::get();

        return view('staff.users-scholar', compact('scholarAccounts'));
    }

    public function showUserApplicants()
    {
        $applicants = applicants::get();

        return view('staff.users-applicant', compact('applicants'));
    }

    public function showUserStaff()
    {
        $staffAccounts = staccount::get();

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
        $user = staccount::findOrFail($id);
        $user->status = 'Active';
        $user->save();

        return redirect()->back()->with('success', 'User activated successfully.');
    }

    public function deactivateStaff($id)
    {
        $user = staccount::findOrFail($id);
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
        $user = staccount::findOrFail($id);

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
        $events = [
            'all' => communityservice::orderBy('eventdate', 'DESC')
                ->orderByRaw("CASE
                            WHEN eventstatus = 'Open' THEN 1
                            WHEN eventstatus = 'Closed' THEN 2
                            ELSE 3
                            END")
                ->orderBy('updated_at', 'DESC')->get(),
            'open' => communityservice::where('eventstatus', 'Open')
                ->orderByRaw("CASE
                        WHEN eventstatus = 'Open' THEN 1
                        WHEN eventstatus = 'Closed' THEN 2
                        ELSE 3
                        END")->orderBy('eventdate', 'DESC')->orderBy('updated_at', 'DESC')->get(),
            'closed' => communityservice::where('eventstatus', 'Closed')
                ->orderByRaw("CASE
                        WHEN eventstatus = 'Open' THEN 1
                        WHEN eventstatus = 'Closed' THEN 2
                        ELSE 3
                        END")->orderBy('eventdate', 'DESC')->orderBy('updated_at', 'DESC')->get(),
        ];

        $totalevents = communityservice::count();
        $openevents = communityservice::where('eventstatus', 'Open')->count();
        $closedevents = communityservice::where('eventstatus', 'Closed')->count();

        $criteria = criteria::first();
        $csHours = $criteria->cshours;

        // Get all scholars with their start and end dates
        $scholars = scholarshipinfo::selectRaw('caseCode, TIMESTAMPDIFF(YEAR, startdate, enddate) as years')->get();

        // Get all attendees' caseCodes
        $attendees = csattendance::pluck('caseCode')->toArray(); // Extract caseCodes into an array

        $scholarsWithCompletedHours = 0;
        $scholarsWithRemainingHours = 0;

        foreach ($scholars as $scholar) {
            // Check if the scholar has attendance records
            if (in_array($scholar->caseCode, $attendees)) {
                // Calculate required hours based on the number of years
                $requiredHours = $scholar->years * $csHours;

                // Get the sum of hoursspent for this scholar
                $renderedHours = csattendance::where('caseCode', $scholar->caseCode)->sum('hoursspent');

                // Check if rendered hours meet the required hours
                if ($renderedHours >= $requiredHours) {
                    $scholarsWithCompletedHours++;
                } else {
                    $scholarsWithRemainingHours++;
                }
            } else {
                $scholarsWithRemainingHours++;
            }
        }

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
        $volunteers = csregistration::with('basicInfo', 'csattendance')->where('csid', $csid)->get();
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

            $workername = $worker->name;

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

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Log the successful creation of the event
            Log::info('Community service event created successfully', [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'created_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            $api_key = env('MOVIDER_API_KEY');
            $api_secret = env('MOVIDER_API_SECRET');

            // Get all users
            $users = User::all();

            // Initialize the Guzzle client
            $client = new \GuzzleHttp\Client();

            // Track failed SMS and failed email notifications
            $failedSMS = [];
            $failedEmail = [];
            $message = 'Changes in the community service event';

            foreach ($users as $user) {
                // Check if the user prefers SMS
                if ($user->notification_preference === 'sms') {
                    // Send the SMS using the Movider API
                    try {
                        $response = $client->post('https://api.movider.co/v1/sms', [
                            'form_params' => [
                                'api_key' => $api_key,
                                'api_secret' => $api_secret,
                                'to' => $user->scPhoneNum, // Ensure that the user has a valid phone number
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
                        // Catch and handle any exception related to SMS
                        $failedSMS[] = $user->scPhoneNum;
                        Log::info('Movider SMS Error', ['error' => $e->getMessage()]);
                    }
                } else {
                    // Send an email notification if not SMS
                    try {
                        $user->notify(new EventCreate($workername)); // Ensure the EventUpdate notification is correct
                    } catch (\Exception $e) {
                        // If email notification failed, add to failed list
                        $failedEmail[] = $user->email;
                        Log::info('Email Notification Error', ['error' => $e->getMessage()]);
                    }
                }
            }

            // After the loop, you can handle failed SMS and email notifications as needed.
            if (!empty($failedSMS)) {
                Log::warning('Failed to send SMS to the following numbers:', $failedSMS);
            }

            if (!empty($failedEmail)) {
                Log::warning('Failed to send emails to the following addresses:', $failedEmail);
            }

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

            $workername = DB::table('communityservice')
                ->join('staccounts', 'communityservice.staffID', '=', 'staccounts.id')
                ->where('communityservice.csid', $csid) // Replace $eventId with the actual event ID
                ->value('staccounts.name'); // Assuming the `name` column exists in `staccounts` table

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

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Log the successful udate of the event
            Log::info('Community service event updated successfully', [
                'csid' => $event->csid,
                'updated_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),

            ]);

            $api_key = env('MOVIDER_API_KEY');
            $api_secret = env('MOVIDER_API_SECRET');

            // Get all users
            $users = User::all();

            // Initialize the Guzzle client
            $client = new \GuzzleHttp\Client();

            // Track failed SMS and failed email notifications
            $failedSMS = [];
            $failedEmail = [];
            $message = 'Changes in the community service event';

            foreach ($users as $user) {
                // Check if the user prefers SMS
                if ($user->notification_preference === 'sms') {
                    // Send the SMS using the Movider API
                    try {
                        $response = $client->post('https://api.movider.co/v1/sms', [
                            'form_params' => [
                                'api_key' => $api_key,
                                'api_secret' => $api_secret,
                                'to' => $user->scPhoneNum, // Ensure that the user has a valid phone number
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
                        // Catch and handle any exception related to SMS
                        $failedSMS[] = $user->scPhoneNum;
                        Log::info('Movider SMS Error', ['error' => $e->getMessage()]);
                    }
                } else {
                    // Send an email notification if not SMS
                    try {
                        $user->notify(new EventUpdate($event, $workername)); // Ensure the EventUpdate notification is correct
                    } catch (\Exception $e) {
                        // If email notification failed, add to failed list
                        $failedEmail[] = $user->email;
                        Log::info('Email Notification Error', ['error' => $e->getMessage()]);
                    }
                }
            }

            // After the loop, you can handle failed SMS and email notifications as needed.
            if (!empty($failedSMS)) {
                Log::warning('Failed to send SMS to the following numbers:', $failedSMS);
            }

            if (!empty($failedEmail)) {
                Log::warning('Failed to send emails to the following addresses:', $failedEmail);
            }


            return redirect()->back()->with('success', 'Successfully updated activity details.');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('failure', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error updating community service event: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'csid' => $csid,
                'request' => $request->all()
            ]);
            return redirect()->back()->with('failure', 'Updating activity details was unsuccessful.');
        }
    }

    public function showHumanitiesClass()
    {
        $classes = humanitiesclass::orderBy('hcdate', 'DESC')->get();
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

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            Log::info('Humanities class event created successfully', [
                'hcid' => $event->hcid,
                'created_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
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

            // Mark attendees with timeout
            hcattendance::where('hcid', $hcid)
                ->whereNull('timeout')
                ->update(['timeout' => Carbon::now(new \DateTimeZone('Asia/Manila'))]);

            // Update event status
            $event = humanitiesclass::where('hcid', $hcid)->first();
            $event->status = 'Done';
            $event->save();

            // Get list of attendees' caseCodes
            $attendees = hcattendance::where('hcid', $hcid)->pluck('caseCode')->toArray();

            // Get list of absentees whose caseCode is not in the attendees
            $absentees = User::whereNotIn('caseCode', $attendees)->get();

            foreach ($absentees as $absent) {
                // Create attendance record for absentees
                $attendanceRecord = hcattendance::create([
                    'hcid' => $hcid,
                    'caseCode' => $absent->caseCode,
                    'timein' => null,
                    'timeout' => null,
                    'tardinessduration' => 0,
                    'hcastatus' => 'Absent',
                ]);

                $event->increment('totalabsentees', 1);

                $absenteeDistrict = scholarshipinfo::where('caseCode', $absent->caseCode)->first();

                $assignedWorker = staccount::where('area', $absenteeDistrict->area)->first();

                // Create an LTE record for the absentee
                $lte = lte::create([
                    'caseCode' => $absent->caseCode,
                    'violation' => 'Absent',
                    'conditionid' => $attendanceRecord->hcaid,
                    'eventtype' => "Humanities Class",
                    'dateissued' => $event->hcdate,
                    'deadline' => Carbon::parse($event->hcdate)->addDays(3),
                    'datesubmitted' => null,
                    'reason' => null,
                    'explanation' => null,
                    'proof' => null,
                    'ltestatus' => 'No Response',
                    'workername' => strtoupper($assignedWorker->name) . ", RSW",
                ]);

                // Notify absentee via SMS or email
                $this->notifyAbsentee($absent, $lte);
            }

            DB::commit();

            return redirect()->back()->with('success', "{$event->topic} has been successfully closed.");
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
            DB::rollBack();
            return redirect()->back()->with('failure', 'Attempt to save event has failed: ');
        }
    }

    private function notifyAbsentee($user, $lte)
    {
        $api_key = env('MOVIDER_API_KEY');
        $api_secret = env('MOVIDER_API_SECRET');
        $client = new \GuzzleHttp\Client();
        $message = 'absent';
        $failedSMS = [];
        $failedEmail = [];

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
                $responseBody = json_decode($response->getBody()->getContents(), true);

                if (!isset($responseBody['phone_number_list']) || !is_array($responseBody['phone_number_list']) || empty($responseBody['phone_number_list'])) {
                    $failedSMS[] = $user->scPhoneNum;
                }
            } catch (\Exception $e) {
                $failedSMS[] = $user->scPhoneNum;
                Log::error('Movider SMS Error', ['exception' => $e->getMessage()]);
            }
        } else {
            try {
                $user->notify(new LteAnnouncementCreated($lte));
            } catch (\Exception $e) {
                $failedEmail[] = $user->email;
                Log::error('Email Notification Error', ['exception' => $e->getMessage()]);
            }
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
                if ($timeout->greaterThan($event->hcstarttime)) {
                    $tardinessduration = $timeout->diffInMinutes($event->hcendtime, true);
                } else {
                    $tardinessduration = $timeout->diffInMinutes($event->hcstarttime, true);
                }

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

            return redirect()->route('viewattendeeslist', $attendee->hcid)->with('success', 'Checkout was successful.');
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
            DB::rollBack();

            return redirect()->route('viewattendeeslist', $attendee->hcid)->with('failure', 'Checkout was unsuccessful.');
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
        $appointments = Appointments::with('basicInfo', 'education')
            ->orderByRaw("CASE
                            WHEN status = 'Pending' THEN 1
                            WHEN status = 'Accepted' THEN 2
                            WHEN status = 'Rejected' THEN 3
                            WHEN status = 'Completed' THEN 4
                            WHEN status = 'Cancelled' THEN 5
                        END")
            ->get();
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



            $api_key = env('MOVIDER_API_KEY');
            $api_secret = env('MOVIDER_API_SECRET');

            $user = User::where('caseCode', $appointment->caseCode)->first();

            // Initialize the Guzzle client
            $client = new \GuzzleHttp\Client();

            // Track failed SMS and failed email notifications
            $failedSMS = [];
            $failedEmail = [];
            $message = 'Youre status has been ' . $appointment->status;

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
                    $user->notify(new appointment($appointment));
                } catch (\Exception $e) {
                    // If email notification failed, add to failed list
                    $failedEmail[] = $user->email;
                }
            }

            // Commit the transaction
            DB::commit();

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Log the successful status update
            Log::info('Appointment status updated successfully', [
                'appointment_id' => $appointment->id,
                'status' => $appointment->status,
                'caseCode' => $appointment->caseCode,
                'user_email' => $user->email,
                'updated_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),

            ]);

            // Redirect with a success message
            return redirect()->back()->with('success', 'Successfully updated appointment status.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Redirect with an error message
            return redirect()->back()->with('failure', 'Failed to update appointment status: ');
        }
    }

    // public function ajaxReports(Request $request)
    // {
    //     $query = summarycollege::with('basicInfo', 'education', 'scholarshipinfo');

    //     // Apply filters
    //     if ($request->cycle) {
    //         $query->where('acadcycle', $request->cycle);
    //     }

    //     if ($request->status) {
    //         $query->where('scholarshipinfo.scholarshipstatus', $request->status);
    //     }

    //     if ($request->remark) {
    //         $query->where('remark', $request->remark);
    //     }

    //     return datatables()->eloquent($query)->make(true);
    // }

    public function showreports(Request $request)
    {
        // datatables start -----------------------------------------------------------------
        $search = $request->input('search'); // Get the search query from the request
        $startdate = Carbon::now();
        $enddate = $startdate->copy()->addYear();
        $acadyear = $startdate->format('Y') . '-' . $enddate->format('Y');

        $collegeCycles = ['Semester', 'Trimester'];
        $shsCycles = ['Semester', 'Trimester'];
        $jhsCycles = ['Quarter'];


        $filteredColleges = [];
        $filteredShs = [];
        $filteredJhs = [];
        $filteredElem = [];

        $filters = $request->only(['cycle', 'status', 'remark']); // Get filter values

        // College Filtering
        foreach ($collegeCycles as $cycle) {
            $collegeQuery = summarycollege::with('basicInfo', 'education', 'scholarshipinfo')
                ->join('scholarshipinfo', 'scholarshipinfo.caseCode', '=', 'summarycollege.caseCode')
                ->where('acadcycle', $cycle);

            // Apply filters to College
            if (!empty($filters['cycle']) && $filters['cycle'] !== $cycle) {
                continue; // Skip the cycle if it doesn't match the filter
            }

            if (!empty($filters['status'])) {
                $collegeQuery->where('scholarshipinfo.scholarshipstatus', $filters['status']);
            }

            if (!empty($filters['remark'])) {
                $collegeQuery->where('summarycollege.remark', $filters['remark']);
            }

            $filteredColleges[$cycle] = $collegeQuery
                ->orderByRaw("remark = 'Satisfactory Performance' DESC")
                ->orderByRaw("
                CASE 
                    WHEN scholarshipinfo.scholarshipstatus = 'Continuing' THEN 1
                    WHEN scholarshipinfo.scholarshipstatus = 'On-Hold' THEN 2
                    WHEN scholarshipinfo.scholarshipstatus = 'Terminated' THEN 3
                    ELSE 4 
                END
            ")
                ->orderBy('endcontract', 'ASC')
                ->select('summarycollege.*')
                ->paginate(100);
        }

        // SHS Filtering
        foreach ($shsCycles as $cycle) {
            $shsQuery = summaryshs::with('basicInfo', 'education', 'scholarshipinfo')
                ->join('scholarshipinfo', 'scholarshipinfo.caseCode', '=', 'summaryshs.caseCode')
                ->where('acadcycle', $cycle);

            // Apply filters to SHS
            if (!empty($filters['cycle']) && $filters['cycle'] !== $cycle) {
                continue; // Skip the cycle if it doesn't match the filter
            }

            if (!empty($filters['status'])) {
                $shsQuery->where('scholarshipinfo.scholarshipstatus', $filters['status']);
            }

            if (!empty($filters['remark'])) {
                $shsQuery->where('summaryshs.remark', $filters['remark']);
            }

            $filteredShs[$cycle] = $shsQuery
                ->orderByRaw("remark = 'Satisfactory Performance' DESC")
                ->orderByRaw("
                CASE 
                    WHEN scholarshipinfo.scholarshipstatus = 'Continuing' THEN 1
                    WHEN scholarshipinfo.scholarshipstatus = 'On-Hold' THEN 2
                    WHEN scholarshipinfo.scholarshipstatus = 'Terminated' THEN 3
                    ELSE 4 
                END
            ")
                ->orderBy('endcontract', 'ASC')
                ->select('summaryshs.*')
                ->paginate(100);
        }

        // JHS Filtering
        foreach ($jhsCycles as $cycle) {
            $jhsQuery = summaryjhs::with('basicInfo', 'education', 'scholarshipinfo')
                ->join('scholarshipinfo', 'scholarshipinfo.caseCode', '=', 'summaryjhs.caseCode')
                ->where('acadcycle', $cycle);

            // Apply filters to JHS
            if (!empty($filters['cycle']) && $filters['cycle'] !== $cycle) {
                continue; // Skip the cycle if it doesn't match the filter
            }

            if (!empty($filters['status'])) {
                $jhsQuery->where('scholarshipinfo.scholarshipstatus', $filters['status']);
            }

            if (!empty($filters['remark'])) {
                $jhsQuery->where('summaryjhs.remark', $filters['remark']);
            }

            $filteredJhs[$cycle] = $jhsQuery
                ->orderByRaw("remark = 'Satisfactory Performance' DESC")
                ->orderByRaw("
                CASE 
                    WHEN scholarshipinfo.scholarshipstatus = 'Continuing' THEN 1
                    WHEN scholarshipinfo.scholarshipstatus = 'On-Hold' THEN 2
                    WHEN scholarshipinfo.scholarshipstatus = 'Terminated' THEN 3
                    ELSE 4 
                END
            ")
                ->orderBy('endcontract', 'ASC')
                ->select('summaryjhs.*')
                ->paginate(100);


            $elemQuery = summaryelem::with('basicInfo', 'education', 'scholarshipinfo')
                ->join('scholarshipinfo', 'scholarshipinfo.caseCode', '=', 'summaryelem.caseCode')
                ->where('acadcycle', $cycle);

            // Apply filters to JHS
            if (!empty($filters['cycle']) && $filters['cycle'] !== $cycle) {
                continue; // Skip the cycle if it doesn't match the filter
            }

            if (!empty($filters['status'])) {
                $jhsQuery->where('scholarshipinfo.scholarshipstatus', $filters['status']);
            }

            if (!empty($filters['remark'])) {
                $jhsQuery->where('summaryjhs.remark', $filters['remark']);
            }

            $filteredElem[$cycle] = $elemQuery
                ->orderByRaw("remark = 'Satisfactory Performance' DESC")
                ->orderByRaw("
            CASE 
                WHEN scholarshipinfo.scholarshipstatus = 'Continuing' THEN 1
                WHEN scholarshipinfo.scholarshipstatus = 'On-Hold' THEN 2
                WHEN scholarshipinfo.scholarshipstatus = 'Terminated' THEN 3
                ELSE 4 
            END
        ")
                ->orderBy('endcontract', 'ASC')
                ->select('summaryelem.*')
                ->paginate(100);
        }



        // datatables end -----------------------------------------------------------------------------------------------------


        // $elem = summaryelem::with('basicInfo', 'education', 'scholarshipinfo')
        //     ->join('scholarshipinfo', 'scholarshipinfo.caseCode', '=', 'summaryelem.caseCode')
        //     ->orderByRaw("remark = 'Satisfactory Performance' DESC") // New sorting priority
        //     ->orderByRaw("
        //     CASE 
        //         WHEN scholarshipinfo.scholarshipstatus = 'Continuing' THEN 1
        //         WHEN scholarshipinfo.scholarshipstatus = 'On-Hold' THEN 2
        //         WHEN scholarshipinfo.scholarshipstatus = 'Terminated' THEN 3
        //         ELSE 4 
        //     END
        // ")
        //     ->orderBy('endcontract', 'ASC')
        //     ->select('summaryelem.*')
        //     ->get();

        $reports = [
            'All' => Reports::where('level', 'All')->orderBy('dategenerated', "DESC")->paginate(10),
            'College' => Reports::where('level', 'College')->orderBy('created_at', "DESC")->paginate(10),
            'Senior High' => Reports::where('level', 'Senior High')->orderBy('created_at', "DESC")->paginate(10),
            'Junior High' => Reports::where('level', 'Junior High')->orderBy('created_at', "DESC")->paginate(10),
            'Elementary' => Reports::where('level', 'Elementary')->orderBy('created_at', "DESC")->paginate(10),
        ];

        return view('staff.scholarship-report', compact('filteredColleges', 'filteredShs', 'filteredJhs', 'filteredElem', 'acadyear', 'reports'));
    }


    public function generateSummaryReport(Request $request)
    {
        try {
            $request->validate([
                'schoollevel' => 'required|string',
                'periodtype' => 'required|string',
                'period' => 'required_if:periodtype,Monthly,Quarterly|string',
                'year' => 'required|string'
            ]);

            $worker = Auth::guard('staff')->user();

            $level = $request->schoollevel;
            $type = $request->periodtype;
            $date = $request->period;
            $year = $request->year;
            $monthNames = [
                'January' => 1,
                'February' => 2,
                'March' => 3,
                'April' => 4,
                'May' => 5,
                'June' => 6,
                'July' => 7,
                'August' => 8,
                'September' => 9,
                'October' => 10,
                'November' => 11,
                'December' => 12,
            ];
            $quarters = [
                'First Quarter' => [$year . '-01-01', $year . '-03-31'],
                'Second Quarter' => [$year . '-04-01', $year . '-06-30'],
                'Third Quarter' => [$year . '-07-01', $year . '-09-30'],
                'Fourth Quarter' => [$year . '-10-01', $year . '-12-31'],
            ];
            $college = '';
            $shs = '';
            $jhs = '';
            $elem = '';
            $scholars = '';
            $scperlevelschool = '';

            // Define the date range based on type
            if ($type == 'Monthly') {
                $month = $monthNames[$date];
                $datescope = "$date, $year";
                $startDate = Carbon::createFromDate($year, $month, 1);  // create a Carbon instance for the start of the month
                $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();  // get the end of the month using endOfMonth()

                // You can now use the startDate and endDate for your query
                $startdate = $startDate->toDateString();
                $enddate = $endDate->toDateString();
            } elseif ($type == 'Quarterly') {
                [$startdate, $enddate] = $quarters[$date];
                $datescope = Carbon::parse($startdate)->format('F j, Y') . ' - ' . Carbon::parse($enddate)->format('F j, Y');
            } elseif ($type == 'Annually') {
                $datescope = $year;
                $startdate = "$year-01-01";
                $enddate = "$year-12-31";
            }

            // Build common queries
            $commonQuery = scholarshipinfo::query();
            $commonQuery->whereBetween('startdate', [$startdate, $enddate]);

            if ($level !== 'All') {
                $commonQuery->whereHas('education', function ($query) use ($level) {
                    $query->where('scSchoolLevel', $level);
                });
            }

            $totalscholars = $commonQuery->count();
            $scpertypestatus = $commonQuery->selectRaw('scholartype, scholarshipstatus, COUNT(*) as sccount')
                ->groupBy('scholartype', 'scholarshipstatus')
                ->orderBy('scholartype')
                ->orderBy('scholarshipstatus')
                ->get();

            $scperarea = $commonQuery->selectRaw('area, COUNT(*) as sccount')
                ->groupBy('area')
                ->orderBy('area')
                ->get();

            if ($level == 'All') {
                $scperlevelschool = ScEducation::selectRaw('scSchoolName, scSchoolLevel, COUNT(*) as sccount')
                    ->whereHas('scholarshipinfo', function ($query) use ($startdate, $enddate) {
                        $query->whereBetween('startdate', [$startdate, $enddate]);
                    })
                    ->groupBy('scSchoolName', 'scSchoolLevel')
                    ->orderBy('scSchoolName')
                    ->orderBy('scSchoolLevel')
                    ->get();
                $college = $this->getUsersBySchoolLevel($year, 'College', $type, $date);
                $shs = $this->getUsersBySchoolLevel($year, 'Senior High', $type, $date);
                $jhs = $this->getUsersBySchoolLevel($year, 'Junior High', $type, $date);
                $elem = $this->getUsersBySchoolLevel($year, 'Elementary', $type, $date);
            }

            // $renewalsperstatus = renewal::selectRaw('status, COUNT(*) as sccount')
            //     ->whereBetween('datesubmitted', [$startdate, $enddate])
            //     ->groupBy('status')
            //     ->orderBy('status')
            //     ->get();

            // Additional data for specific levels (e.g., Senior High, College)
            if ($level !== 'All' && in_array($level, ['Senior High', 'College'])) {
                $scpercoursestrand = ScEducation::selectRaw('scCourseStrandSec, COUNT(*) as sccount')
                    ->where('scSchoolLevel', $level)
                    ->whereHas('scholarshipinfo', function ($query) use ($startdate, $enddate) {
                        $query->whereBetween('startdate', [$startdate, $enddate]);
                    })
                    ->groupBy('scCourseStrandSec')
                    ->get();
            }

            if ($level !== 'All') {
                $scperschool = ScEducation::selectRaw('scSchoolName, COUNT(*) as sccount')
                    ->where('scSchoolLevel', $level)
                    ->whereHas('scholarshipinfo', function ($query) use ($startdate, $enddate) {
                        $query->whereBetween('startdate', [$startdate, $enddate]);
                    })
                    ->groupBy('scSchoolName')
                    ->get();

                $scholars = $this->getUsersBySchoolLevel($year, $level, $type, $date);
            }

            $data = [
                'totalscholars' => $totalscholars,
                'scpertypestatus' => $scpertypestatus,
                'scperarea' => $scperarea,
                'scperlevelschool' => $scperlevelschool ?? NULL,
                'scperschool' => $scperschool ?? NULL,
                'scpercoursestrand' => $scpercoursestrand ?? NULL,
                // 'renewalsperstatus' => $renewalsperstatus,
                'level' => $level,
                'scope' => $datescope,
                'college' => $college,
                'shs' => $shs,
                'jhs' => $jhs,
                'elem' => $elem,
                'scholars' => $scholars,
            ];

            if ($type == 'Annually') {
                $reportname = "Scholarship-Summary-Report-{$year}";
            } else {
                $reportname = "Scholarship-Summary-Report-{$year}-{$date}";
            }

            // Generate the PDF from the view
            $pdf = Pdf::loadView('staff.reports.summaryreport-pdf', $data);

            // Store the PDF file in the 'public/reports' directory
            $filepath = 'reports/' . "{$reportname}.pdf";
            Storage::disk('public')->put($filepath, $pdf->output()); // save the PDF file
            DB::beginTransaction();
            // Log the report creation in the database
            Reports::create([
                'reportname' => $reportname,
                'level' => $level,
                'datescope' => $datescope,
                'dategenerated' => now()->toDateString(),
                'generatedby' => $worker->name,
                'filepath' => $filepath,
            ]);
            DB::commit();

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Log the successful report creation
            Log::info('Summary Report generated successfully', [
                'reportname' => $reportname,
                'level' => $level,
                'filepath' => $filepath,
                'created_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return Redirect()->back()->with('success', "{$reportname} has been generated successfully.");
        } catch (\Exception $e) {
            Log::error("Error: {$e->getMessage()}");
            DB::rollBack();
            return Redirect()->back()->with('failure', 'An error has occurred. ');
        }
    }

    private function getUsersBySchoolLevel($year, $schoolLevel, $periodtype, $period)
    {
        // Month names mapping
        $monthNames = [
            'January' => 1,
            'February' => 2,
            'March' => 3,
            'April' => 4,
            'May' => 5,
            'June' => 6,
            'July' => 7,
            'August' => 8,
            'September' => 9,
            'October' => 10,
            'November' => 11,
            'December' => 12,
        ];

        // Quarters mapping
        $quarters = [
            'First Quarter' => [$year . '-01-01', $year . '-03-31'],
            'Second Quarter' => [$year . '-04-01', $year . '-06-30'],
            'Third Quarter' => [$year . '-07-01', $year . '-09-30'],
            'Fourth Quarter' => [$year . '-10-01', $year . '-12-31'],
        ];

        // Define start and end date based on period type
        if ($periodtype == 'Monthly') {
            $month = $monthNames[$period];  // Get the month number from the name
            $startDate = Carbon::createFromDate($year, $month, 1);  // Start of the month
            $endDate = $startDate->copy()->endOfMonth();  // End of the month
            $startdate = $startDate->toDateString();
            $enddate = $endDate->toDateString();
        } elseif ($periodtype == 'Quarterly') {
            [$startdate, $enddate] = $quarters[$period];  // Get start and end dates of the quarter
        } elseif ($periodtype == 'Annually') {
            $startdate = "$year-01-01";
            $enddate = "$year-12-31";
        }

        // Query to get users based on school level and date range
        return User::with('education', 'scholarshipinfo', 'basicInfo')
            ->whereHas('scholarshipinfo', function ($query) use ($year, $startdate, $enddate) {
                $query->whereYear('startdate', $year)
                    ->whereBetween('startdate', [$startdate, $enddate]);
            })
            ->whereHas('education', function ($query) use ($schoolLevel) {
                $query->where('scSchoolLevel', $schoolLevel);
            })
            ->join('scholarshipinfo', 'users.caseCode', '=', 'scholarshipinfo.caseCode')
            ->join('sc_education', 'users.caseCode', '=', 'sc_education.caseCode')
            ->orderByRaw("
            CASE
                WHEN scholarshipinfo.scholarshipstatus = 'Continuing' THEN 1
                WHEN scholarshipinfo.scholarshipstatus = 'On-Hold' THEN 2
                WHEN scholarshipinfo.scholarshipstatus = 'Terminated' THEN 3
                ELSE 4
            END,
            CASE
                WHEN sc_education.scYearGrade IN ('Grade 1', 'Grade 7', 'Grade 11', 'First Year') THEN 1
                WHEN sc_education.scYearGrade IN ('Grade 2', 'Grade 8', 'Grade 12', 'Second Year') THEN 2
                WHEN sc_education.scYearGrade IN ('Grade 3', 'Grade 9', 'Third Year') THEN 3
                WHEN sc_education.scYearGrade IN ('Grade 4', 'Grade 10', 'Fourth Year') THEN 4
                WHEN sc_education.scYearGrade IN ('Grade 5', 'Fifth Year') THEN 5
                WHEN sc_education.scYearGrade = 'Grade 6' THEN 6
                ELSE 7
            END
        ")
            ->select('users.*')
            ->get();
    }

    public function deleteSummaryReport($id)
    {
        DB::beginTransaction();
        try {
            $report = Reports::findOrFail($id);
            $filepath = $report->filepath;

            if (!$report) {
                return Redirect()->back()->with('failure', 'Record not found.');
            }

            if (Storage::exists($filepath)) {
                Storage::delete($filepath);
            }

            // Delete the record from the database
            $report->delete();

            DB::commit();

            $updatedBy = auth()->guard('staff')->user(); // Adjust if you're using a different guard

            // Log the successful deletion
            Log::info('Report deleted successfully', [
                'reportname' => $report->reportname,
                'filepath' => $filepath,
                'deleted_by' => [
                    'user_id' => $updatedBy->id ?? 'Guest',
                    'name' => $updatedBy->name ?? 'Guest',
                    'email' => $updatedBy->email ?? 'Guest',
                ],
                'request_details' => $this->getRequestDetails(),
                'timestamp' => now(),
            ]);

            return Redirect()->back()->with('success', "Successfully deleted the report: {$report->reportname}.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error: {$e->getMessage()}");
            return Redirect()->back()->with('failure', "An error has occurred. If this issue persists, please contact us at inquiries@scholartrack.com");
        }
    }
}
