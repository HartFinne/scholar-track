<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule the automate:attendance-lte command
Schedule::command('automate:attendance-lte')
    ->dailyAt('00:00')
    ->timezone('Asia/Manila');

// Schedule para sa open and close in community service
Schedule::command('app:automate-commnunity-service-status')
    ->dailyAt('00:00')
    ->timezone('Asia/Manila');

Schedule::command('app:update-l-t-e-status')
    ->dailyAt('00:00')
    ->timezone('Asia/Manila');

Schedule::command('app:close-humanities-class')
    ->hourly()
    ->timezone('Asia/Manila');
