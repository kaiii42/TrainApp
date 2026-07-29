<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Rule 5 — sole mechanism for expiring vouchers.
// Runs at 00:05 daily to catch all vouchers that expired at end-of-day.
Schedule::command('vouchers:expire')->dailyAt('00:05');
