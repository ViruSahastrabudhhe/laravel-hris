<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('qr:regenerate')->monthlyOn(1, '00:00');
Schedule::command('attendances:mark-absent')->dailyAt('23:59');
Schedule::command('attendances:create-monthly-employee-attendance')->monthlyOn(1, '00:00');
Schedule::command('leave-credit:earn-monthly-leave-credit')->monthlyOn(1, '00:00');
Schedule::command('pay-period:create-monthly-periods')->monthlyOn(1, '00:00');
Schedule::command('cache:warm --all')->dailyAt('03:00')->withoutOverlapping()->runInBackground();
