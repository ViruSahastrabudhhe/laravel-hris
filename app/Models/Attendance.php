<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\EmployeeAttendance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Attendance extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'attendances';

    protected $fillable = [
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
        'employee_id',
        'user_id',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    #[Scope]
    protected function findAllWithUserID(Builder $query): void {
        $query->where('user_id', '=', auth()->user()->id);
    }

    #[Scope]
    protected function currentMonth(Builder $query): void {
        $query->whereYear('created_at', '=', Carbon::now()->year)
              ->whereMonth('created_at', '=', Carbon::now()->month);
    }
}
