<?php

namespace App\Observers;

use App\Models\Attendance;
use App\Enums\AttendanceStatus;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeLeaveBalance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        $this->recalculateEmployeeMonthlySummary($attendance);
        $this->recalculateMonthlyOvertime($attendance);
    }

    /**
     * Handle the Attendance "updated" event.
     */
    public function updated(Attendance $attendance): void
    {
        $this->calculateWorkMinutes($attendance);
        $this->calculateOvertimeMinutes($attendance);
        $this->determineAttendanceStatus($attendance);
        $this->recalculateEmployeeMonthlySummary($attendance);
        $this->recalculateMonthlyOvertime($attendance);
    }

    /**
     * Handle the Attendance "deleted" event.
     */
    public function deleted(Attendance $attendance): void
    {
        $this->calculateWorkMinutes($attendance);
        $this->calculateOvertimeMinutes($attendance);
        $this->determineAttendanceStatus($attendance);
        $this->recalculateEmployeeMonthlySummary($attendance);
        $this->recalculateMonthlyOvertime($attendance);
    }

    /**
     * Handle the Attendance "restored" event.
     */
    public function restored(Attendance $attendance): void
    {
        $this->calculateWorkMinutes($attendance);
        $this->calculateOvertimeMinutes($attendance);
        $this->determineAttendanceStatus($attendance);
        $this->recalculateEmployeeMonthlySummary($attendance);
        $this->recalculateMonthlyOvertime($attendance);
    }

    /**
     * Handle the Attendance "force deleted" event.
     */
    public function forceDeleted(Attendance $attendance): void
    {
        //
    }

    private function recalculateEmployeeMonthlySummary(Attendance $attendance): void {
        $date = Carbon::parse($attendance->date);

        $employeeAttendance = EmployeeAttendance::firstOrCreate(
            [
                'employee_id' => $attendance->employee_id,
                'month' => $date->month,
                'year' => $date->year,
            ],
            [
                'total_present' => 0,
                'total_late' => 0,
                'total_absent' => 0,
                'total_overtime' => 0,
                'is_complete' => false,
            ]
        );

        $query = Attendance::where('employee_id', $attendance->employee_id)
            ->whereMonth('date', Carbon::parse($attendance->date)->month)
            ->whereYear('date', Carbon::parse($attendance->date)->year)
            ->whereNull('deleted_at');

        $employeeAttendance->update([
            'total_present' => (clone $query)->where('attendance_status', AttendanceStatus::Present->value)->count(),
            'total_late'    => (clone $query)->where('attendance_status', AttendanceStatus::Late->value)->count(),
            'total_absent'  => (clone $query)->where('attendance_status', AttendanceStatus::Absent->value)->count(),
            'total_overtime'=> (clone $query)->sum('overtime_minutes'),
        ]);

        $totalDays = $employeeAttendance->total_present + $employeeAttendance->total_late + $employeeAttendance->total_absent;

        $employeeAttendance->update([
            'is_complete' => $totalDays >= 22
        ]);
    }

    private function recalculateMonthlyOvertime(Attendance $attendance): void
    {
        $date = Carbon::parse($attendance->date);

        $employeeAttendance = EmployeeAttendance::firstOrCreate(
            [
                'employee_id' => $attendance->employee_id,
                'month' => $date->month,
                'year' => $date->year,
            ],
            [
                'total_present' => 0,
                'total_late' => 0,
                'total_absent' => 0,
                'total_overtime' => 0,
                'is_complete' => false,
            ]
        );

        $totalOvertime = Attendance::where('employee_id', $attendance->employee_id)
            ->whereMonth('date', $date->month)
            ->whereYear('date', $date->year)
            ->whereNull('deleted_at')
            ->sum('overtime_minutes');

        $employeeAttendance->update([
            'total_overtime' => $totalOvertime
        ]);
    }

    private function calculateWorkMinutes(Attendance $attendance) {
        if (!$attendance->time_in || !$attendance->time_out || !$attendance->break_start || !$attendance->break_end) {
            $attendance->total_minutes = 0;
            $attendance->saveQuietly();
            return;
        }

        $timeIn = Carbon::parse($attendance->time_in);
        $timeOut = Carbon::parse($attendance->time_out);

        $breakStart = Carbon::parse($attendance->break_start);
        $breakEnd = Carbon::parse($attendance->break_end);

        $sumTimeInAndTimeOut = $timeIn->diffInMinutes($timeOut);
        $sumBreakStartAndBreakEnd = $breakStart->diffInMinutes($breakEnd);

        $actualTotalMinutes = $sumTimeInAndTimeOut - $sumBreakStartAndBreakEnd;

        $attendance->total_minutes = $actualTotalMinutes;
        $attendance->saveQuietly();
    }

    private function calculateOvertimeMinutes(Attendance $attendance) {
        if (!$attendance->overtime_in || !$attendance->overtime_out) {
            return;
        }

        $overtimeIn = Carbon::parse($attendance->overtime_in);
        $overtimeOut = Carbon::parse($attendance->overtime_out);

        $overtimeMinutes = $overtimeIn->diffInMinutes($overtimeOut);

        $attendance->overtime_minutes = $overtimeMinutes;
        $attendance->saveQuietly();
    }

    private function determineAttendanceStatus(Attendance $attendance) {
        if (!$attendance->time_in || !$attendance->time_out || !$attendance->break_start || !$attendance->break_end) {
            $attendance->attendance_status = AttendanceStatus::Absent->value;
            $attendance->saveQuietly();
            return;
        }

        $timeIn = Carbon::parse($attendance->time_in);
        $timeOut = Carbon::parse($attendance->time_out);

        if ($timeIn->eq($timeIn->copy()->startOfDay()) || $timeOut->eq($timeOut->copy()->startOfDay())) {
            $attendance->attendance_status = AttendanceStatus::Absent->value;
            $attendance->saveQuietly();
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

        $attendance->saveQuietly();
    }

    private function processLeaveDeduction(Attendance $attendance) {
        $leaveBalance = EmployeeLeaveBalance::where('employee_id', $attendance->employee_id)
                ->where('type', 'Vacation')
                ->first();

        if (!$leaveBalance) {
            return;
        }

        if ($attendance->attendance_status==AttendanceStatus::Late->value) {
            $leaveBalance->amount -= 0.5;
            $leaveBalance->save();
        } elseif ($attendance->attendance_status==AttendanceStatus::Absent->value) {
            $leaveBalance->amount -= 1;
            $leaveBalance->save();
        }
    }
}
