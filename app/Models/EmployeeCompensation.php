<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\Compensation;
use App\Enums\CompensationCategory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeCompensation extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\EmployeeCompensationFactory> */
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'employee_compensations';

    protected $fillable = [
        'employee_id',
        'compensation_id',
        'pay_period_id',
        'amount',
    ];

    protected static function booted()
    {
        static::saved(fn() => Cache::forget('compensation_stats'));
        static::deleted(fn() => Cache::forget('compensation_stats'));
    }

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function compensation() {
        return $this->belongsTo(Compensation::class);
    }

    public function payPeriod() {
        return $this->belongsTo(PayPeriod::class);
    }
}
