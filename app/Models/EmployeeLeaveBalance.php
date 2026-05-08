<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class EmployeeLeaveBalance extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeLeaveBalanceFactory> */
    use HasFactory;

    protected $table = 'employee_leave_balances';

    protected $fillable = [
        'employee_id',
        'amount',
        'type',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}
