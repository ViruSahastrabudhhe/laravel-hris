<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    protected $fillable = [
        'payroll_record_id',
        'name',
        'type',
        'amount'
    ];

    public function payrollRecord()
    {
        return $this->belongsTo(PayrollRecord::class);
    }
}