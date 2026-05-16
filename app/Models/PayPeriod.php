<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class PayPeriod extends Model
{
    use SoftDeletes;

    protected $table = 'pay_periods';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'pay_date',
        'month',
        'year',
        'is_active',
    ];

    public function payrollRecords() {
        return $this->hasMany(PayrollRecord::class, 'pay_period_id');
    }

    public function employeeCompensations() {
        return $this->hasMany(EmployeeCompensation::class, 'pay_period_id');
    }
}
