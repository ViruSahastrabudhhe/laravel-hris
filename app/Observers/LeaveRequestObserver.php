<?php

namespace App\Observers;

use App\Enums\AttendanceStatus;
use App\Enums\LeaveStatus;
use App\Models\Attendance;
use App\Models\EmployeeAttendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;

class LeaveRequestObserver
{
    /**
     * Handle the LeaveRequest "created" event.
     */
    public function created(LeaveRequest $employeeLeave): void
    {
        $this->updateLeaveDuration($employeeLeave);
        $this->syncLeave($employeeLeave);
    }

    /**
     * Handle the LeaveRequest "updated" event.
     */
    public function updated(LeaveRequest $employeeLeave): void
    {
        if ($employeeLeave->isDirty('leave_status')) {
            $this->syncLeave($employeeLeave);
        }
    }

    /**
     * Handle the LeaveRequest "deleted" event.
     */
    public function deleted(LeaveRequest $employeeLeave): void
    {
        //
    }

    /**
     * Handle the LeaveRequest "restored" event.
     */
    public function restored(LeaveRequest $employeeLeave): void
    {
        //
    }

    /**
     * Handle the LeaveRequest "force deleted" event.
     */
    public function forceDeleted(LeaveRequest $employeeLeave): void
    {
        //
    }

    private function updateLeaveDuration(LeaveRequest $employeeLeave) {
        $startDate = Carbon::parse($employeeLeave->start_date);
        $endDate = Carbon::parse($employeeLeave->end_date);

        $totalDays = ($startDate->diffInDays($endDate)) + 1;

        $employeeLeave->leave_duration = $totalDays;
        /*
            must be a saveQuietly() method,
            otherwise will cause infinite recursion by calling the created event
            every time the update event is called
        */
        $employeeLeave->saveQuietly();
    }

    private function syncLeave(LeaveRequest $leave): void{
        if ($leave->leave_status !== LeaveStatus::Approved->value) {
            return;
        }

        $start = Carbon::parse($leave->start_date);
        $end   = Carbon::parse($leave->end_date);

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            Attendance::firstOrCreate(
                [
                    'employee_id' => $leave->employee_id,
                    'date' => $date->toDateString(),
                ],
                [
                    'attendance_status' => AttendanceStatus::Leave->value,
                ]
            );
        }
    }
}
