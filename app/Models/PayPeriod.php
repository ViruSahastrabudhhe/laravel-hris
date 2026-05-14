<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayPeriod extends Model
{
    protected $table = 'pay_periods';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
    ];

    public function payrollRecords() {
        return $this->hasMany(PayrollRecord::class, 'pay_period_id');
    }
}
