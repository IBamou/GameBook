<?php

use App\Console\Commands\CheckTableStatus;
use App\Console\Commands\SendReminders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(CheckTableStatus::class)->everyMinute()->runInBackground()->withoutOverlapping();
Schedule::command(SendReminders::class)->dailyAt('18:00')->runInBackground();
