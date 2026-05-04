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
    ];

    public function employee() {
        return $this->hasMany(Employee::class);
    }
}
