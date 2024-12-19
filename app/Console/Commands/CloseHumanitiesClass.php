<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\humanitiesclass;
use App\Http\Controllers\StaffController;

class CloseHumanitiesClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:close-humanities-class';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically close on-going humanities classes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all on-going events where current time is an hour past the end time
        $events = humanitiesclass::where('status', 'On Going')
            ->where(function ($query) {
                $query->where('hcdate', '<', today())
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereDate('hcdate', '=', today())
                            ->where('hcendtime', '<=', now());
                    });
            })
            ->get();

        if ($events->isEmpty()) {
            $this->info('No humanities classes to close at this time.');
            return;
        }

        foreach ($events as $event) {
            try {
                $controller = new StaffController();
                $controller->savehc($event->hcid);

                $this->info("Closed humanities class ID: {$event->hcid}");
            } catch (\Exception $e) {
                // Log the error or output an error message if something goes wrong
                $this->error("Failed to close class ID {$event->hcid}: " . $e->getMessage());
            }
        }
    }
}
