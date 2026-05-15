<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class LeaveType extends Model
{
    /** @use HasFactory<\Database\Factories\LeaveTypesFactory> */
    use HasFactory;

    protected $table = 'leave_types';

    protected $fillable = [
        'leave_type',
        'days_of_leave',
        'is_active',
    ];

    protected static function booted()
    {
        static::saved(fn() => Cache::forget('leave_type_stats'));
        static::deleted(fn() => Cache::forget('leave_type_stats'));
    }

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    #[Scope]
    protected function findAllWithUserID(Builder $query): void {
        $query->where('user_id', '=', auth()->user()->id);
    }
}
