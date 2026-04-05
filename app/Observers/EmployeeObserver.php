<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\EmployeeWorkSchedule;
use App\Models\EmployeeLeaveBalance;
use App\Models\EmployeeDeduction;
use Illuminate\Support\Facades\DB;

class EmployeeObserver
{
    /**
     * Handle the Employee "created" event.
     */
    public function created(Employee $employee): void
    {
        $this->createEmployeeLeaveBalance($employee);
        $this->createEmployeeDeduction($employee);
    }

    /**
     * Handle the Employee "updated" event.
     */
    public function updated(Employee $employee): void
    {
        $this->updateEmployeeDeduction($employee);
    }

    /**
     * Handle the Employee "deleted" event.
     */
    public function deleted(Employee $employee): void
    {
        //
    }

    /**
     * Handle the Employee "restored" event.
     */
    public function restored(Employee $employee): void
    {
        //
    }

    /**
     * Handle the Employee "force deleted" event.
     */
    public function forceDeleted(Employee $employee): void
    {
        //
    }

    private function createEmployeeLeaveBalance(Employee $employee) {
        $employeeLeaveBalance = new EmployeeLeaveBalance;
        $employeeLeaveBalance->leave_balance = 15;
        $employeeLeaveBalance->employee_id = $employee->id;
        $employeeLeaveBalance->user_id = auth()->user()->id;
        $employeeLeaveBalance->save();
    }

    private function createEmployeeDeduction(Employee $employee) {
        $gsis = DB::table('deductions')->where('name', 'GSIS Contribution')->first();
        $philhealth = DB::table('deductions')->where('name', 'PhilHealth Personal Share Contribution')->first();

        $employeeDeduction = new EmployeeDeduction;
        $employeeDeduction->employee_id = $employee->id;
        $employeeDeduction->deduction_id = 1;
        $employeeDeduction->amount = $employee->position->salary_amount * $gsis->rate;
        $employeeDeduction->user_id = auth()->user()->id;
        
        $employeePhilhealth = new EmployeeDeduction;
        $employeePhilhealth->employee_id = $employee->id;
        $employeePhilhealth->deduction_id = 2;
        $employeePhilhealth->amount = $employee->position->salary_amount * $philhealth->rate;
        if ($employeePhilhealth->amount >= 2500) {
            $employeePhilhealth->amount = 2500;
        }
        $employeePhilhealth->user_id = auth()->user()->id;
        
        $employeePagibig = new EmployeeDeduction;
        $employeePagibig->employee_id = $employee->id;
        $employeePagibig->deduction_id = 3;
        if ($employee->salary_amount > 1500) {
            $employeePagibig->amount = 200;
        } else {
            $employeePagibig->amount = 100;
        }
        $employeePagibig->user_id = auth()->user()->id;
        
        $employeeDeduction->save();
        $employeePhilhealth->save();
        $employeePagibig->save();
    }

    private function updateEmployeeDeduction(Employee $employee) {
        $gsis = DB::table('deductions')->where('name', 'GSIS Contribution')->first();
        $philhealth = DB::table('deductions')->where('name', 'PhilHealth Personal Share Contribution')->first();

        $employeeDeduction = EmployeeDeduction::where('employee_id', $employee->id)->where('deduction_id', 1)->first();
        $employeeDeduction->amount = $employee->position->salary_amount * $gsis->rate;
        $employeeDeduction->save();

        $employeePhilhealth = EmployeeDeduction::where('employee_id', $employee->id)->where('deduction_id', 2)->first();
        $employeePhilhealth->amount = $employee->position->salary_amount * $philhealth->rate;
        if ($employeePhilhealth->amount >= 2500) {
            $employeePhilhealth->amount = 2500;
        }
        $employeePhilhealth->save();
    }
}
