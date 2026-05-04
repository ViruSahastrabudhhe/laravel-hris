<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use App\Models\Employee;

class EmployeeWorkSchedule extends Model
{
    /** @use HasFactory<\Database\Factories\WorkScheduleFactory> */
    use HasFactory;

    protected $table = 'employee_work_schedules';

    protected $fillable = [
        'employee_id',
        'work_schedule_id',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function workSchedule() {
        return $this->belongsTo(WorkSchedule::class, 'work_schedule_id', 'id');
    }
}
