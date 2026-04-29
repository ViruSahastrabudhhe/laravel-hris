<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\EmployeeWorkSchedule;
use App\Models\EmployeeLeaveBalance;
use App\Models\EmployeeDeduction;
use App\Models\Deduction;
use App\Models\Position;
use App\Enums\EmploymentType;
use App\Enums\DeductionType;
use App\Enums\PositionStatus;
use Illuminate\Support\Facades\Log;

class EmployeeObserver
{
    /**
     * Handle the Employee "created" event.
     */
    public function created(Employee $employee): void
    {
        $this->createEmployeeLeaveBalance($employee);
        if ($employee->employment_type == EmploymentType::Regular->value) {
            $this->createEmployeeMandatoryDeductions($employee);
        }
    }

    /**
     * Handle the Employee "updated" event.
     */
    public function updated(Employee $employee): void
    {
        $this->updatePositionStatus($employee);

        if ($employee->isJobOrder()) {
            foreach ($employee->employeeDeduction as $deduction) {
                if ($deduction->deduction->type == DeductionType::Mandatory->value) {
                    $deduction->amount = 0;
                    $deduction->save();
                }
            }
        }

        if (!$employee->isJobOrder()) {
            $this->updateEmployeeDeduction($employee);
        }
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
        if ($employee->isJobOrder()) {
            $employeeLeaveBalance = new EmployeeLeaveBalance;
            $employeeLeaveBalance->leave_balance = 0.0;
            $employeeLeaveBalance->employee_id = $employee->id;
            $employeeLeaveBalance->user_id = auth()->user()->id;
            $employeeLeaveBalance->save();
            return;
        }

        $employeeLeaveBalance = new EmployeeLeaveBalance;
        $employeeLeaveBalance->leave_balance = 15;
        $employeeLeaveBalance->employee_id = $employee->id;
        $employeeLeaveBalance->user_id = auth()->user()->id;
        $employeeLeaveBalance->save();
    }

    private function createEmployeeMandatoryDeductions(Employee $employee) {
        $gsis = Deduction::where('name', 'GSIS Contribution')->first();
        $philhealth = Deduction::where('name', 'PhilHealth Personal Share Contribution')->first();

        $amount = $employee->salary->amount ?? 0;

        $employeeDeduction = new EmployeeDeduction;
        $employeeDeduction->employee_id = $employee->id;
        $employeeDeduction->deduction_id = 1;
        $employeeDeduction->amount = $amount * $gsis->rate;
        $employeeDeduction->user_id = auth()->user()->id;
        
        $employeePhilhealth = new EmployeeDeduction;
        $employeePhilhealth->employee_id = $employee->id;
        $employeePhilhealth->deduction_id = 2;
        $employeePhilhealth->amount = $amount * $philhealth->rate;
        if ($employeePhilhealth->amount >= 2500) {
            $employeePhilhealth->amount = 2500;
        }
        $employeePhilhealth->user_id = auth()->user()->id;
        
        $employeePagibig = new EmployeeDeduction;
        $employeePagibig->employee_id = $employee->id;
        $employeePagibig->deduction_id = 3;
        if ($amount > 1500) {
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

        $employeePagibig = EmployeeDeduction::where('employee_id', $employee->id)->where('deduction_id', 3)->first();
        if ($employeePagibig) {
            if ($amount > 1500) {
                $employeePagibig->amount = 200;
            } else {
                $employeePagibig->amount = 100;
            }
            $employeePagibig->save();
        }
    }

    // private function deleteEmployeeDeductions(Employee $employee) {
    //     EmployeeDeduction::where('employee_id', $employee->id)->where('deduction_type', DeductionType::Optional->value)->delete();
    // }

    private function updatePositionStatus(Employee $employee) {
        $employeeCountInPos = Employee::where('position_id', $employee->position->id)->count();
        $position = Position::find($employee->position_id);

        if ($employeeCountInPos >= $position->total_employees) {
            $position->status = PositionStatus::Closed->value;
            $position->saveQuietly();
        } else {
            $position->status = PositionStatus::Hiring->value;
            $position->saveQuietly();
        }
    }
}
