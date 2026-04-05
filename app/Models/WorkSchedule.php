<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use App\Models\EmployeeWorkSchedule;

class WorkSchedule extends Model
{
    /** @use HasFactory<\Database\Factories\WorkScheduleFactory> */
    use HasFactory;

    protected $table = 'work_schedules';

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'pm_start_time',
        'break_minutes',
        'work_days',
        'grace_period_minutes',
        'user_id',
    ];

    protected $casts = [
        'work_days' => 'array',
    ];

    public function workSchedule() {
        return $this->hasMany(EmployeeWorkSchedule::class);
    }

    public function employeeAttendance() {
        return $this->hasMany(EmployeeAttendance::class, 'work_schedule_id', 'id');
    }

    #[Scope]
    protected function findAllWithUserID(Builder $query): void {
        $query->where('user_id', '=', auth()->user()->id);
    }
}
