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
        'total_leaves',
        'total_overtime',
        'month',
        'year',
        'is_complete',
    ];

    public function employee() { return $this->belongsTo(Employee::class); }
    public function attendance() { return $this->hasMany(Attendance::class); }

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
