<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\EmployeeWorkSchedule;
use App\Models\EmployeeLeaveBalance;
use App\Models\EmployeeCompensation;
use App\Models\Compensation;
use App\Models\Position;
use App\Enums\EmploymentType;
use App\Enums\CompensationType;
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
            foreach ($employee->employeeCompensation as $compensation) {
                if ($compensation->compensation->is_mandatory) {
                    $compensation->amount = 0;
                    $compensation->save();
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
            $employeeLeaveBalance->save();
            return;
        }

        $employeeLeaveBalance = new EmployeeLeaveBalance;
        $employeeLeaveBalance->leave_balance = 15;
        $employeeLeaveBalance->employee_id = $employee->id;
        $employeeLeaveBalance->save();
    }

    private function createEmployeeMandatoryDeductions(Employee $employee) {
        $gsis = Compensation::where('name', 'GSIS Contribution')->first();
        $philhealth = Compensation::where('name', 'PhilHealth Personal Share Contribution')->first();

        $amount = $employee->salary->amount ?? 0;

        $employeeDeduction = new EmployeeCompensation;
        $employeeDeduction->employee_id = $employee->id;
        $employeeDeduction->compensation_id = 1;
        $employeeDeduction->amount = $amount * $gsis->rate;

        $employeePhilhealth = new EmployeeCompensation;
        $employeePhilhealth->employee_id = $employee->id;
        $employeePhilhealth->compensation_id = 2;
        $employeePhilhealth->amount = $amount * $philhealth->rate;
        if ($employeePhilhealth->amount >= 2500) {
            $employeePhilhealth->amount = 2500;
        }

        $employeePagibig = new EmployeeCompensation;
        $employeePagibig->employee_id = $employee->id;
        $employeePagibig->compensation_id = 3;
        if ($amount > 1500) {
            $employeePagibig->amount = 200;
        } else {
            $employeePagibig->amount = 100;
        }

        $employeeDeduction->save();
        $employeePhilhealth->save();
        $employeePagibig->save();
    }

    private function updateEmployeeDeduction(Employee $employee) {
        if ($employee->employment_type == EmploymentType::JobOrder->value) {
            return;
        }

        $gsis = Compensation::where('name', 'GSIS Contribution')->first();
        $philhealth = Compensation::where('name', 'PhilHealth Personal Share Contribution')->first();

        $amount = $employee->salary->amount ?? 0;

        $employeeDeduction = EmployeeCompensation::where('employee_id', $employee->id)->where('compensation_id', 1)->first();
        if ($employeeDeduction) {
            $employeeDeduction->amount = $amount * $gsis->rate;
            $employeeDeduction->save();
        }

        $employeePhilhealth = EmployeeCompensation::where('employee_id', $employee->id)->where('compensation_id', 2)->first();
        if ($employeePhilhealth) {
            $employeePhilhealth->amount = $amount * $philhealth->rate;
            if ($employeePhilhealth->amount >= 2500) {
                $employeePhilhealth->amount = 2500;
            }
            $employeePhilhealth->save();
        }

        $employeePagibig = EmployeeCompensation::where('employee_id', $employee->id)->where('compensation_id', 3)->first();
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
    //     EmployeeCompensation::where('employee_id', $employee->id)->where('deduction_type', CompensationType::Optional->value)->delete();
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
