<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeAttendance extends Model
{
    use HasFactory;

    protected $table = 'employee_attendances';

    protected $fillable = [
        'employee_id',
        'total_present',
        'total_late',
        'total_absent',
        'month',
        'year',
        'is_complete',
    ];

    public function employee() { return $this->belongsTo(Employee::class); }
    public function attendance() { return $this->hasMany(Attendance::class); }

    public function overtimeMinutes() {
        return Attendance::joinWithEmployeeAttendance()
            ->betweenCurrentMonth()
            ->sum('attendances.overtime_minutes');
    }

    public function completeAttendances(int $month = null, int $year = null) {
        return Attendance::joinWithEmployeeAttendance()
            ->where('employee_attendances.is_complete', true)
            ->count();
    }

    #[Scope]
    protected function currentMonth(Builder $query): void {
        $query->where('month', '=', Carbon::now()->month)
            ->where('year', '=', Carbon::now()->year);
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

}
