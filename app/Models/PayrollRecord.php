<?php

namespace App\Models;

use App\Models\Employees;
use Illuminate\Database\Eloquent\Model;

class PayrollRecord extends Model
{
    protected $fillable = [
        'employee_id',
        'total_earnings',
        'total_deductions',
        'net_pay',
        'status',
        'month',
        'year'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function items()
    {
        return $this->hasMany(PayrollItem::class);
    }

    public function earnings()
    {
        return $this->items()->where('type', 'Earning');
    }

    public function compensations()
    {
        return $this->items()->where('type', 'Compensation');
    }
}
