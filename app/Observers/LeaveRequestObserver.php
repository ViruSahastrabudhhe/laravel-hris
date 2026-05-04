<?php

namespace App\Observers;

use App\Models\LeaveRequest;
use Carbon\Carbon;

class LeaveRequestObserver
{
    /**
     * Handle the LeaveRequest "created" event.
     */
    public function created(LeaveRequest $employeeLeave): void
    {
        $this->processLeaveDuration($employeeLeave);
    }

    /**
     * Handle the LeaveRequest "updated" event.
     */
    public function updated(LeaveRequest $employeeLeave): void
    {
        $this->updateLeaveDuration($employeeLeave);
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

    private function processLeaveDuration(LeaveRequest $employeeLeave) {
        $startDate = Carbon::parse($employeeLeave->start_date);
        $endDate = Carbon::parse($employeeLeave->end_date);

        $totalDays = ($startDate->diffInDays($endDate)) + 1;

        $employeeLeave->leave_duration = $totalDays;
        $employeeLeave->save();
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
}
