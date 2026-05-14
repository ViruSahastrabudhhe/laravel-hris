<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;

class CreateMonthlyEmployeeAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendances:create-monthly-employee-attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the monthly employee attendance summary record.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            $employee->employeeAttendance()->create([
                'employee_id' => $employee->id,
                'month' => now()->month,
                'year' => now()->year,
            ]);
        }
    }
}
