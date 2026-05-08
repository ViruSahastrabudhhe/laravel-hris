<?php

namespace App\Observers;

use App\Models\Attendance;
use App\Enums\AttendanceStatus;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use Carbon\Carbon;

class AttendanceObserver
{
    /**
     * Handle the Attendance "created" event.
     */
    public function created(Attendance $attendance): void
    {
        $this->calculateWorkMinutes($attendance);
        $this->calculateOvertimeMinutes($attendance);
        $this->determineAttendanceStatus($attendance);
        $this->processLeaveDeduction($attendance);
        $this->addDayToEmployeeAttendance($attendance);
    }

    /**
     * Handle the Attendance "updated" event.
     */
    public function updated(Attendance $attendance): void
    {
    }

    /**
     * Handle the Attendance "deleted" event.
     */
    public function deleted(Attendance $attendance): void
    {
        //
    }

    /**
     * Handle the Attendance "restored" event.
     */
    public function restored(Attendance $attendance): void
    {
        //
    }

    /**
     * Handle the Attendance "force deleted" event.
     */
    public function forceDeleted(Attendance $attendance): void
    {
        //
    }

    private function addDayToEmployeeAttendance(Attendance $attendance): void {
        $attendanceDate = Carbon::parse($attendance->date);

        $employeeAttendance = EmployeeAttendance::firstOrCreate(
            [
                'employee_id' => $attendance->employee_id,
                'month' => $attendanceDate->month,
                'year' => $attendanceDate->year
            ],
            [
                'total_present' => 0,
                'total_late' => 0,
                'total_absent' => 0,
                'is_complete' => false
            ]
        );

        if ($attendance->attendance_status === AttendanceStatus::Present->value) {
            $employeeAttendance->increment('total_present');
        } elseif ($attendance->attendance_status === AttendanceStatus::Late->value) {
            $employeeAttendance->increment('total_late');
        } else {
            $employeeAttendance->increment('total_absent');
        }

        $employeeAttendance->refresh();
        if ($employeeAttendance->total_present >= 22) {
            $employeeAttendance->update(['is_complete' => true]);
        }
    }

    private function calculateWorkMinutes(Attendance $attendance) {
        $timeIn = Carbon::parse($attendance->time_in);
        $timeOut = Carbon::parse($attendance->time_out);

        $breakStart = Carbon::parse($attendance->break_start);
        $breakEnd = Carbon::parse($attendance->break_end);

        $sumTimeInAndTimeOut = $timeIn->diffInMinutes($timeOut);
        $sumBreakStartAndBreakEnd = $breakStart->diffInMinutes($breakEnd);

        $actualTotalMinutes = $sumTimeInAndTimeOut - $sumBreakStartAndBreakEnd;

        $attendance->total_minutes = $actualTotalMinutes;
        $attendance->save();
    }

    private function calculateOvertimeMinutes(Attendance $attendance) {
        if (!$attendance->overtime_in || !$attendance->overtime_out) {
            return;
        }

        $overtimeIn = Carbon::parse($attendance->overtime_in);
        $overtimeOut = Carbon::parse($attendance->overtime_out);

        $overtimeMinutes = $overtimeIn->diffInMinutes($overtimeOut);

        $attendance->overtime_minutes = $overtimeMinutes;
        $attendance->save();
    }

    private function determineAttendanceStatus(Attendance $attendance) {
        if (!$attendance->time_in || !$attendance->time_out) {
            return;
        }

        $timeIn = Carbon::parse($attendance->time_in);
        $timeOut = Carbon::parse($attendance->time_out);

        if ($timeIn->eq($timeIn->copy()->startOfDay()) || $timeOut->eq($timeOut->copy()->startOfDay())) {
            $attendance->attendance_status = AttendanceStatus::Absent->value;
            $attendance->save();
            return;
        }

        $workStartTime = Carbon::parse($attendance->employee->employeeWorkSchedule->workSchedule->start_time);
        $gracePeriodMinutes = $attendance->employee->employeeWorkSchedule->workSchedule->grace_period_minutes;

        $diffTimeInAndWorkStartTime = $workStartTime->diffInMinutes($timeIn);

        if ($diffTimeInAndWorkStartTime > $gracePeriodMinutes) {
            $attendance->attendance_status = AttendanceStatus::Late->value;
        } else {
            $attendance->attendance_status = AttendanceStatus::Present->value;
        }

        $attendance->save();
    }

    private function processLeaveDeduction(Attendance $attendance) {
        $leaveBalance = $attendance->employee->leaveBalance;

        if (!$leaveBalance) {
            return;
        }

        if ($attendance->attendance_status==AttendanceStatus::Late->value) {
            $leaveBalance->leave_balance -= 0.5;
            $leaveBalance->save();
        } elseif ($attendance->attendance_status==AttendanceStatus::Absent->value) {
            $leaveBalance->leave_balance -= 1;
            $leaveBalance->save();
        }

        if ($leaveBalance->leave_balance <= 0) {
            $leaveBalance->leave_balance = 0;
            $leaveBalance->save();
        }
    }
}
