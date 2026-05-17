<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PayPeriod;
use Carbon\Carbon;

class CreateMonthlyPayPeriods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pay-period:create-monthly-periods';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the monthly pay periods: 1-15 and 16-end.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = Carbon::createFromDate(now()->year, now()->month, 1);

        $firstHalfStartDate = now()->startOfMonth()->format('d');
        $firstHalfEndDate = now()->day(15)->format('d');
        
        $secondHalfStartDate = now()->day(16)->format('d');
        $secondHalfEndDate = now()->endOfMonth()->format('d');

        $firstHalfName = $firstHalfStartDate . '-' 
                . $firstHalfEndDate . ' '
                . Carbon::createFromDate(null, now()->month, 1)->format('F') . ' '
                . now()->year;
        $secondHalfName = $secondHalfStartDate . '-' 
                . $secondHalfEndDate . ' '
                . Carbon::createFromDate(null, now()->month, 1)->format('F') . ' '
                . now()->year;

        PayPeriod::updateOrCreate(
            [
                'month' => now()->month, 
                'year' => now()->year,             
                'start_date' => $targetDate->copy()->startOfMonth()->toDateString(), 
                'end_date'   => $targetDate->copy()->day(15)->toDateString(),
            ],
            [
                'name'       => $firstHalfName,
                'start_date' => $targetDate->copy()->startOfMonth()->toDateString(), 
                'end_date'   => $targetDate->copy()->day(15)->toDateString(),
                'month'      => now()->month,
                'year'       => now()->year,
                'is_active'  => true,
            ]
        );

        PayPeriod::updateOrCreate(
            [
                'month' => now()->month, 
                'year' => now()->year,             
                'start_date' => $targetDate->copy()->day(16)->toDateString(), 
                'end_date'   => $targetDate->copy()->endOfMonth()->toDateString(),
            ],
            [
                'name'       => $secondHalfName,
                'start_date' => $targetDate->copy()->day(16)->toDateString(), 
                'end_date'   => $targetDate->copy()->endOfMonth()->toDateString(),
                'month'      => now()->month,
                'year'       => now()->year,
            ]
        );
    }
}