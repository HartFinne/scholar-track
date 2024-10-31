<?php

namespace App\Console\Commands;

use App\Models\hcattendance;
use App\Models\humanitiesclass;
use App\Models\lte;
use App\Models\User;
use App\Notifications\LteAnnouncementCreated;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutomateHumanitiesClassAbsentAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:automate-humanities-class-absent-attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automate the process of marking absentees and creating LTE records for humanities events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDate = Carbon::now('Asia/Manila')->toDateString();

        // Retrieve all humanities classes that are supposed to happen today
        $classes = humanitiesclass::whereDate('hcdate', $currentDate)->get();

        // Retrieve all students (assuming all students are invited)
        $students = DB::table('users')->pluck('caseCode'); // Adjust table name if needed

        foreach ($classes as $class) {
            foreach ($students as $caseCode) {
                // Check if an attendance record exists for this student and class
                $attendance = hcattendance::where('caseCode', $caseCode)
                    ->where('hcid', $class->hcid)
                    ->first();

                // If no attendance record exists, mark as absent
                if (!$attendance) {
                    $hcattendance = hcattendance::create([
                        'hcid' => $class->hcid,
                        'caseCode' => $caseCode,
                        'tardinessduration' => 0,
                        'hcastatus' => 'Absent',
                        'hcdate' => $class->hcdate,
                    ]);

                    // Log the creation of the hcattendance record
                    Log::info("Attendance marked as Absent for caseCode: {$caseCode}, hcid: {$class->hcid}");

                    // Create the LTE record for each student absent
                    $lte = lte::create([
                        'caseCode' => $caseCode,
                        'violation' => 'Absent',
                        'conditionid' => $class->hcid,  // Use class hcid as condition id
                        'eventtype' => "Humanities Class",
                        'dateissued' => $currentDate,
                        'deadline' => Carbon::now('Asia/Manila')->addDays(3)->toDateString(),
                        'datesubmitted' => NULL,
                        'reason' => NULL,
                        'explanation' => NULL,
                        'proof' => NULL,
                        'ltestatus' => 'No Response',
                        'workername' => null,
                    ]);

                    // Log the creation of the LTE record
                    Log::info("LTE record created for caseCode: {$caseCode}, hcid: {$class->hcid}");


                    $api_key = config('services.movider.api_key');
                    $api_secret = config('services.movider.api_secret');

                    Log::info('Movider API Key', ['api_key' => $api_key, 'api_secret' => $api_secret]);


                    $user = User::where('caseCode', $caseCode)->first();

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

        $this->info('Absent attendees marked successfully.');
    }
}
