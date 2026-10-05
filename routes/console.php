<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Hourly check-in and check-out tracking
Schedule::command('team:checkin-checkout-reminders')->hourly();

// Daily task alerts at 9:00 AM
Schedule::command('tasks:send-reminders')->dailyAt('09:00');

// Hourly trigger for daily/weekly reports
Schedule::command('reports:send-operational')->hourly();

// Schedule weekly project reports to be sent every Monday at 8:00 AM
Schedule::command('reports:send-weekly')
    ->weeklyOn(1, '8:00')
    ->timezone('UTC')
    ->description('Send weekly project progress reports to all active clients');

// Schedule weekly team performance review emails every Monday at 8:30 AM
Schedule::command('team:send-weekly-reviews')
    ->weeklyOn(1, '8:30')
    ->timezone('UTC')
    ->description('Send automated weekly performance review emails to active team members');

Schedule::command('queue:work --stop-when-empty')->everyMinute();