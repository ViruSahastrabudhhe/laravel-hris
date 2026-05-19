<?php

namespace App\Models;

use App\Models\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\Models\PayrollItem;
use App\Enums\CompensationCategory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PayrollRecord extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'pay_period_id',
        'monthly_rate_of_pay',
        'amount_accrued_for_period',
        'total_earnings',
        'total_deductions',
        'amount_paid',
        'status',
        'month',
        'year',
        'pay_date',
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
        return $this->items()->where('payroll_record_id', $this->id)->where('category', CompensationCategory::Earning->value);
    }

    public function deductions()
    {
        return $this->items()->where('payroll_record_id', $this->id)->where('category', CompensationCategory::Deduction->value);
    }

    public function isProcessed() {
        return $this->status === 'Processed';
    }
}
