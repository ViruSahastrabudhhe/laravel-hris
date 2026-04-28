<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\QrAttendanceScan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RegenerateMonthlyQrCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:regenerate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate QR codes for all employees at the start of each month';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $employees = Employee::findAllWithUserID()->all();

        foreach ($employees as $employee) {
            $hash = hash('sha256', $employee->id . now()->format('Y-m') . uniqid());
            QrAttendanceScan::create([
                'employee_id' => $employee->id,
                'qr_code_hash' => $hash,
                'expires_at' => Carbon::now()->endOfMonth(),
            ]);
        }

        $this->info('Monthly QR codes regenerated for ' . $employees->count() . ' employees.');
    }
}