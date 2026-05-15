<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use OwenIt\Auditing\Contracts\Auditable;

class LeaveRequest extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\EmployeeLeaveFactory> */
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'leave_requests';

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'leave_reason',
        'leave_status',
        'decline_reason',
        'user_id',
    ];

    protected static function booted()
    {
        static::saved(fn() => Cache::forget('leave_stats'));
        static::deleted(fn() => Cache::forget('leave_stats'));
    }

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType() {
        return $this->hasOne(LeaveType::class, 'id', 'leave_type_id');
    }
}
