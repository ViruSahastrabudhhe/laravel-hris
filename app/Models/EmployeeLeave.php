<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class EmployeeLeave extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeLeaveFactory> */
    use HasFactory;

    protected $table = 'employee_leaves';

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'leave_duration',
        'leave_reason',
        'leave_status',
        'decline_reason',
        'user_id',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType() {
        return $this->hasOne(LeaveType::class, 'id', 'leave_type_id');
    }

    #[Scope]
    protected function findAllWithUserID(Builder $query): void {
        $query->where('user_id', '=', auth()->user()->id);
    }
}
