<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\csregistration;
use App\Models\csattendance;
use App\Models\lte;
use App\Models\communityservice;
use App\Models\staccount;
use App\Models\User;
use App\Notifications\LteAnnouncementCreated;
use Illuminate\Support\Facades\Log;

class AutomateAttendanceAndLTE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automate:attendance-lte';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automate the process of marking absentees and creating LTE records for community service events';

    /**
     * Execute the console command.
     */
    // Execute the console command
    public function handle()
    {
        $today = Carbon::now('Asia/Manila')->toDateString();

        // Fetch all registrations with "GOING" status where the event date has passed and no attendance is recorded
        $registrations = csregistration::where('registatus', 'GOING')
            ->join('communityservice', 'csregistration.csid', '=', 'communityservice.csid')
            ->where('communityservice.eventdate', '<', $today)
            ->select('csregistration.*', 'communityservice.staffID', 'communityservice.eventdate', 'communityservice.title')
            ->get();

        foreach ($registrations as $registration) {
            // Check if there is no attendance record for this activity
            $attendanceExists = csattendance::where('caseCode', $registration->caseCode)
                ->where('csid', $registration->csid)
                ->exists();


            if (!$attendanceExists) {
                // Mark the registration as "ABSENT"
                csregistration::where('caseCode', $registration->caseCode)
                    ->where('csid', $registration->csid)
                    ->update(['registatus' => 'ABSENT']);

                // Create an "ABSENT" record in csattendance
                csattendance::create([
                    'caseCode' => $registration->caseCode,
                    'csid' => $registration->csid,
                    'timein' => null,
                    'timeout' => null,
                    'csastatus' => 'Absent',
                    'hoursspent' => 0,
                    'attendanceproof' => null,
                    'status' => 'Valid',
                    'created_at' => Carbon::now('Asia/Manila'),
                    'updated_at' => Carbon::now('Asia/Manila'),
                ]);

                // Check if an LTE already exists for this registration and condition
                $existingLTE = lte::where('caseCode', $registration->caseCode)
                    ->where('conditionid', $registration->csrid)
                    ->where('eventtype', 'Community Service')
                    ->exists();



                // If no LTE exists, create a new one
                if (!$existingLTE) {
                    // Retrieve the staff responsible for this event
                    $staff = staccount::find($registration->staffID);

                    // Create the LTE entry

                    $lte = lte::create([
                        'caseCode' => $registration->caseCode,
                        'violation' => 'Absent',
                        'conditionid' => $registration->csrid,
                        'eventtype' => "Community Service",
                        'dateissued' => $today,
                        'deadline' => Carbon::now('Asia/Manila')->addDays(3)->toDateString(),
                        'datesubmitted' => NULL,
                        'reason' => NULL,
                        'explanation' => NULL,
                        'proof' => NULL,
                        'ltestatus' => 'No Response',
                        'workername' => strtoupper($staff->name) . ", RSW",
                    ]);

                    $api_key = config('services.movider.api_key');
                    $api_secret = config('services.movider.api_secret');

                    Log::info('Movider API Key', ['api_key' => $api_key, 'api_secret' => $api_secret]);


                    $user = User::where('caseCode', $registration->caseCode)->first();

                    // Initialize the Guzzle client
                    $client = new \GuzzleHttp\Client();

                    // Track failed SMS and failed email notifications
                    $failedSMS = [];
                    $failedEmail = [];
                    $message = 'You absent in a cs activity';


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

                            Log::info('Movider API Key', ['api_key' => $api_key, 'api_secret' => $api_secret]);


                            $responseBody = $response->getBody()->getContents();
                            $decodedResponse = json_decode($responseBody, true);

                            // Log the full response body to see the exact structure returned by Movider
                            Log::info('Full Movider SMS Response', ['response_body' => $decodedResponse]);

                            // Check if phone_number_list is an array and not empty
                            if (!isset($decodedResponse['phone_number_list']) || !is_array($decodedResponse['phone_number_list']) || count($decodedResponse['phone_number_list']) == 0) {
                                $failedSMS[] = $user->scPhoneNum; // Track failed SMS
                            }
                        } catch (\Exception $e) {
                            // Catch and handle any exception
                            $failedSMS[] = $user->scPhoneNum;
                            Log::error('Movider SMS Exception', ['error' => $e->getMessage()]);
                        }
                    } else {
                        // Send an email notification
                        try {
                            $user->notify(new LteAnnouncementCreated($lte));
                        } catch (\Exception $e) {
                            // If email notification failed, add to failed list
                            $failedEmail[] = $user->email;
                            Log::info('Movider SMS Response', ['response' => $failedEmail]);
                        }
                    }
                }
            }
        }

        $this->info('Attendance and LTE automation for absentees completed successfully.');
    }
}
