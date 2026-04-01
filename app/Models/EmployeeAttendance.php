<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\WorkSchedule;
use App\Enums\AttendanceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class EmployeeAttendance extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeAttendanceFactory> */
    use HasFactory;

    protected $table = 'employee_attendances';

    protected $fillable = [
        'user_id',
        'employee_id',
        'attendance_id',
        'work_schedule_id',
        'date',
        'late_minutes',
        'undertime_minutes',
        'overtime_minutes',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function attendance() {
        return $this->belongsTo(Attendance::class, 'attendance_id', 'id');
    }

    public function workSchedule() {
        return $this->belongsTo(WorkSchedule::class, 'work_schedule_id', 'id');
    }

    #[Scope]
    protected function findAllWithUserID(Builder $query): void {
        $query->where('user_id', '=', auth()->user()->id);
    }
}
