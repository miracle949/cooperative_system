<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('savings:process')->quarterlyOn(1, '00:00');

// Schedule::call(function () {
//     app(\App\Services\LoanPenaltyService::class)->applyPenaltiesForAllOverdueLoans();
// })->dailyAt('01:00');

// Schedule::command('loans:process-overdue')->dailyAt('01:30');
