<?php

namespace App\Observers;

use App\Models\EmployeeLeave;
use Carbon\Carbon;

class EmployeeLeaveObserver
{
    /**
     * Handle the EmployeeLeave "created" event.
     */
    public function created(EmployeeLeave $employeeLeave): void
    {
        $this->processLeaveDuration($employeeLeave);
    }

    /**
     * Handle the EmployeeLeave "updated" event.
     */
    public function updated(EmployeeLeave $employeeLeave): void
    {
        $this->updateLeaveDuration($employeeLeave);
    }

    /**
     * Handle the EmployeeLeave "deleted" event.
     */
    public function deleted(EmployeeLeave $employeeLeave): void
    {
        //
    }

    /**
     * Handle the EmployeeLeave "restored" event.
     */
    public function restored(EmployeeLeave $employeeLeave): void
    {
        //
    }

    /**
     * Handle the EmployeeLeave "force deleted" event.
     */
    public function forceDeleted(EmployeeLeave $employeeLeave): void
    {
        //
    }

    private function processLeaveDuration(EmployeeLeave $employeeLeave) {
        $startDate = Carbon::parse($employeeLeave->start_date);
        $endDate = Carbon::parse($employeeLeave->end_date);

        $totalDays = ($startDate->diffInDays($endDate)) + 1;

        $employeeLeave->leave_duration = $totalDays;
        $employeeLeave->save();
    }

    private function updateLeaveDuration(EmployeeLeave $employeeLeave) {
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
