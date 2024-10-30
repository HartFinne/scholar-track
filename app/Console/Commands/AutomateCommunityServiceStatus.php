<?php

namespace App\Console\Commands;

use App\Models\communityservice;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AutomateCommunityServiceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:automate-commnunity-service-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update if the status is close or open';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get today's date in the 'Asia/Manila' timezone
        $today = Carbon::now('Asia/Manila')->toDateString();

        // Fetch all events where the event date is in the past and the status is still "Open"
        $events = communityservice::where('eventdate', '<', $today)
            ->where('eventstatus', 'Open')
            ->get();

        // Loop through the events and update their status to "Closed"
        foreach ($events as $event) {
            $event->eventstatus = 'Closed';
            $event->save();
        }

        $this->info('Community service event statuses updated successfully.');
    }
}
