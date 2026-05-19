<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    protected $fillable = [
        'payroll_record_id',
        'employee_compensation_id',
        'name',
        'category',
        'amount'
    ];

    public function payrollRecord()
    {
        return $this->belongsTo(PayrollRecord::class);
    }

    public function employeeCompensation() {
        return $this->belongsTo(EmployeeCompensation::class);
    }
}
