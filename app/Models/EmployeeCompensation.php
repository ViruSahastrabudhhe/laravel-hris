<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\Compensation;
use App\Enums\CompensationType;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;

class EmployeeCompensation extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeCompensationFactory> */
    use HasFactory;

    protected $table = 'employee_compensations';

    protected $fillable = [
        'employee_id',
        'compensation_id',
        'amount',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function compensation() {
        return $this->belongsTo(Compensation::class);
    }
}
