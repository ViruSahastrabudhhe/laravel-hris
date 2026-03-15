<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class LeaveType extends Model
{
    /** @use HasFactory<\Database\Factories\LeaveTypesFactory> */
    use HasFactory;

    protected $table = 'leave_types';
    
    protected $fillable = [
        'leave_type',
        'days_of_leave',
        'is_active',
        'user_id',
    ];

    #[Scope]
    protected function findAllWithUserID(Builder $query): void {
        $query->where('user_id', '=', auth()->user()->id);
    }
}
