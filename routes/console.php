<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule weekly project reports to be sent every Monday at 8:00 AM
Schedule::command('reports:send-weekly')
    ->weeklyOn(1, '8:00')
    ->timezone('UTC')
    ->description('Send weekly project progress reports to all active clients');

Schedule::command('queue:work --stop-when-empty')->everyMinute();