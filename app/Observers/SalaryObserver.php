<?php

namespace App\Observers;

use App\Models\Salary;
use Illuminate\Support\Facades\Log;
use App\Models\EmployeeWorkSchedule;
use App\Models\EmployeeLeaveBalance;
use App\Models\EmployeeDeduction;
use App\Models\Deduction;
use App\Enums\EmploymentType;

class SalaryObserver
{
    /**
     * Handle the Salary "created" event.
     */
    public function created(Salary $salary): void
    {
        //
    }

    /**
     * Handle the Salary "updated" event.
     */
    public function updated(Salary $salary): void
    {
        $this->updateEmployeeDeduction($salary);
    }

    /**
     * Handle the Salary "deleted" event.
     */
    public function deleted(Salary $salary): void
    {
        //
    }

    /**
     * Handle the Salary "restored" event.
     */
    public function restored(Salary $salary): void
    {
        //
    }

    /**
     * Handle the Salary "force deleted" event.
     */
    public function forceDeleted(Salary $salary): void
    {
        //
    }

    private function updateEmployeeDeduction(Salary $salary) {
        $employee = $salary->employee;

        if ($employee->employment_type == EmploymentType::JobOrder->value) {
            return;
        }

        $gsis = Deduction::where('name', 'GSIS Contribution')->first();
        $philhealth = Deduction::where('name', 'PhilHealth Personal Share Contribution')->first();

        $amount = $employee->salary->amount ?? 0;

        $employeeDeduction = EmployeeDeduction::where('employee_id', $employee->id)->where('deduction_id', 1)->first();
        if ($employeeDeduction) {
            $employeeDeduction->amount = $amount * $gsis->rate;
            $employeeDeduction->save();
        }

        $employeePhilhealth = EmployeeDeduction::where('employee_id', $employee->id)->where('deduction_id', 2)->first();
        if ($employeePhilhealth) {
            $employeePhilhealth->amount = $amount * $philhealth->rate;
            if ($employeePhilhealth->amount >= 2500) {
                $employeePhilhealth->amount = 2500;
            }
            $employeePhilhealth->save();
        }
    }
}
