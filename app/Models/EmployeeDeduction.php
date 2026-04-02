<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\Deduction;
use App\Enumbs\DeductionType;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;

class EmployeeDeduction extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeDeductionFactory> */
    use HasFactory;

    protected $table = 'employee_deductions';

    protected $fillable = [
        'employee_id',
        'deduction_id',
        'amount',
        'user_id',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function deduction() {
        return $this->belongsTo(Deduction::class);
    }
    
    #[Scope]
    protected function findAllWithUserID(Builder $query): void {
        $query->where('user_id', '=', auth()->user()->id);
    }
}
