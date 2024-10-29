<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\csregistration;
use App\Models\csattendance;
use App\Models\lte;
use App\Models\communityservice;
use App\Models\staccount;


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
                    'timein' => null, // Use null instead of 'nullable'
                    'timeout' => null, // Use null instead of 'nullable'
                    'tardinessduration' => null, // Use null instead of 'nullable'
                    'csastatus' => 'ABSENT',
                    'hoursspent' => 0,
                    'attendanceproof' => null, // Use null instead of 'nullable'
                    'status' => 'DONE',
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
                    lte::create([
                        'caseCode' => $registration->caseCode,
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
                }
            }
        }

        $this->info('Attendance and LTE automation for absentees completed successfully.');
    }
}
