<?php

namespace App\Models;

use App\Models\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\Models\PayrollItem;
use App\Enums\CompensationType;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PayrollRecord extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'pay_period_id',
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

    public function payPeriod() {
        return $this->belongsTo(PayPeriod::class, 'pay_period_id');
    }

    public function earnings()
    {
        return $this->items()->where('type', CompensationType::Earning->value);
    }

    public function deductions()
    {
        return $this->items()->where('type', CompensationType::Deduction->value);
    }

    public function isProcessed() {
        return $this->status === 'Processed';
    }
}
