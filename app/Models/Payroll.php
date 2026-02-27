<?php

namespace App\Models;

use App\Models\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    /** @use HasFactory<\Database\Factories\PayrollFactory> */
    use HasFactory;
    
    protected $table = 'payrolls';

    protected $fillable = [
        'employee_id',
        'deductions_id',
        'gross_pay',
        'tax_deduction',
        'cash_advance',
        'adjustment',
        'total_deductions',
        'net_pay',
        'user_id',
    ];

    public function employee() {
        return $this->hasMany(Employee::class);
    }
}
