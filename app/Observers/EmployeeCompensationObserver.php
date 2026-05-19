<?php

namespace App\Observers;

use App\Enums\CompensationCategory;
use App\Models\EmployeeCompensation;
use App\Models\PayrollItem;
use App\Models\PayrollRecord;
use Illuminate\Support\Facades\Log;

class EmployeeCompensationObserver
{
    /**
     * Handle the EmployeeCompensation "created" event.
     */
    public function created(EmployeeCompensation $employeeCompensation): void
    {
        $this->syncPayrollItem($employeeCompensation);
    }

    /**
     * Handle the EmployeeCompensation "updated" event.
     */
    public function updated(EmployeeCompensation $employeeCompensation): void
    {
        $this->syncPayrollItem($employeeCompensation);
    }

    /**
     * Handle the EmployeeCompensation "deleted" event.
     */
    public function deleted(EmployeeCompensation $employeeCompensation): void
    {
        $this->deletePayrollItem($employeeCompensation);
    }

    /**
     * Handle the EmployeeCompensation "restored" event.
     */
    public function restored(EmployeeCompensation $employeeCompensation): void
    {
        //
    }

    /**
     * Handle the EmployeeCompensation "force deleted" event.
     */
    public function forceDeleted(EmployeeCompensation $employeeCompensation): void
    {
        //
    }

    private function syncPayrollItem(EmployeeCompensation $employeeCompensation)
    {
        $payrollRecord = PayrollRecord::where('employee_id', $employeeCompensation->employee_id)
            ->where('pay_period_id', $employeeCompensation->pay_period_id)
            ->first();

        if (!$payrollRecord) return;

        $compensation = $employeeCompensation->compensation;

        PayrollItem::updateOrCreate(
            [
                'payroll_record_id' => $payrollRecord->id,
                'name'              => $compensation->name,
            ],
            [
                'employee_compensation_id' => $employeeCompensation->id,
                'category'   => $compensation->category,
                'amount' => $employeeCompensation->amount,
            ]
        );

        $this->syncPayrollRecord($employeeCompensation);
    }

    private function deletePayrollItem(EmployeeCompensation $employeeCompensation): void {
        $payrollRecord = PayrollRecord::where('employee_id', $employeeCompensation->employee_id)
            ->where('pay_period_id', $employeeCompensation->pay_period_id)
            ->first();

        if (!$payrollRecord) return;

        $payrollItem = PayrollItem::where('payroll_record_id', $payrollRecord->id)
            ->where('employee_compensation_id', $employeeCompensation->id)
            ->first();

        if (!$payrollItem) return;

        $payrollItem->delete();
    }

    public function syncPayrollRecord(EmployeeCompensation $employeeCompensation) {
        $payrollRecord = PayrollRecord::where('employee_id', $employeeCompensation->employee_id)
            ->where('pay_period_id', $employeeCompensation->pay_period_id)
            ->first();

        if (!$payrollRecord) return;

        $totals = $payrollRecord->items()
            ->get()
            ->groupBy('category')
            ->map(fn($group) => $group->sum('amount'));

        $payrollRecord->update([
            'total_deductions' => $totals[CompensationCategory::Deduction->value] ?? 0,
            'total_earnings'   => $totals[CompensationCategory::Earning->value] ?? 0,
            'amount_paid'      => ($totals[CompensationCategory::Earning->value] ?? 0) - ($totals[CompensationCategory::Deduction->value] ?? 0),
        ]);
    }
}
