<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;

class EarnMonthlyLeaveCredit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave-credit:earn-monthly-leave-credit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gives employees their 1.25 vacation and sick leave credit';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            $credit = 1.25;

            $sickLeave = $employee->employeeLeaveBalance()->where('employee_id', $employee->id)->where('type', 'Sick')->firstOrFail();
            $vacationLeave = $employee->employeeLeaveBalance()->where('employee_id', $employee->id)->where('type', 'Vacation')->firstOrFail();

            $sickLeave->amount += $credit;
            $vacationLeave->amount += $credit;

            $sickLeave->save();
            $vacationLeave->save();
        }
    }
}
