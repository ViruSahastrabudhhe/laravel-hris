<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\AttendanceScanLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class Attendance extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'attendances';

    protected $fillable = [
        'employee_id',
        'date',
        'time_in',
        'time_out',
        'break_start',
        'break_end',
        'overtime_in',
        'overtime_out',
        'attendance_status',
        'total_minutes',
        'overtime_minutes',
        'number_of_scans',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function employeeAttendance() {
        return $this->belongsTo(EmployeeAttendance::class);
    }

    public function attendanceScanLogs() {
        return $this->hasMany(AttendanceScanLog::class);
    }

    #[Scope]
    protected function currentMonth(Builder $query): void {
        $query->whereYear('date', '=', Carbon::now()->year)
              ->whereMonth('date', '=', Carbon::now()->month);
    }

    #[Scope]
    protected function betweenCurrentMonth(Builder $query): void {
        $query->whereDate('date', '>=', Carbon::now()->startOfMonth())
            ->whereDate('date', '<=', Carbon::now()->endOfMonth());
    }

    #[Scope]
    protected function forPeriod(Builder $query, int $month, int $year): void {
        $date = Carbon::createFromDate($year, $month, 1);
        $query->whereDate('date', '>=', $date->copy()->startOfMonth())
            ->whereDate('date', '<=', $date->copy()->endOfMonth());
    }

    #[Scope]
    protected function presentToday(Builder $query): void {
        $query->where('attendance_status', AttendanceStatus::Present->value)
            ->whereDate('date', '>=', Carbon::now()->startOfDay())
            ->whereDate('date', '<=', Carbon::now()->endOfDay());
    }

    #[Scope]
    protected function joinWithEmployeeAttendance(Builder $query): void {
        $query->join('employee_attendances', 'employee_attendances.employee_id', '=', 'attendances.employee_id');
    }
}
