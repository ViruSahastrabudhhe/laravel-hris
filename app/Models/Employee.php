<?php

namespace App\Models;

use App\Models\Department;
use App\Models\Position;
use App\Models\Scopes\EmployeeScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;

#[ScopedBy([EmployeeScope::class])]
class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'email',
        'date_of_birth',
        'address',
        'phone_number',
        'employment_type',
        'is_active',
        'position_id',  
        'department_id',
        'user_id',
    ];

    public function department() {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    public function position() {
        return $this->hasOne(Position::class, 'id', 'position_id');
    }
     
    public function hoursWorked(int $employee_id) {
        $hoursWorked = DB::table('attendances')
            ->where('user_id', '=', auth()->user()->id)
            ->where('employee_id', '=', $employee_id)
            ->sum('total_time');

        return $hoursWorked;
    }

    public function overtimeWorked(int $employee_id) {
        $overtimeWorked = DB::table('attendances')
            ->where('user_id', '=', auth()->user()->id)
            ->where('employee_id', '=', $employee_id)
            ->sum('overtime');

        return $overtimeWorked;
    }

    public function grossPay(int $employee_id) {
        $overtime = $this->overtimePay($employee_id);

        return $this->position->salary_amount + $overtime;
    }

    public function overtimePay(int $employee_id) {
        $overtimeHours = $this->overtimeWorked($employee_id);

        $hourlyRate = ($this->position->salary_amount * 12) / (261 * 8);
        $overtimeHourlyRate = $hourlyRate * 1.25;
        $overtimePay = $overtimeHourlyRate * $overtimeHours;

        return $overtimePay;
    }

    public function gsisContribution() {
        $gsis = DB::table('deductions')
            ->where('user_id', '=', auth()->user()->id)
            ->where('id', '=', '1')
            ->pluck('rate');

        $calc = $this->position->salary_amount * $gsis[0];

        return $calc;
    }

    public function philHealthContribution() {
        $philhealth = DB::table('deductions')
            ->where('user_id', '=', auth()->user()->id)
            ->where('id', '=', '2')
            ->pluck('rate');

        $calc = $this->position->salary_amount * $philhealth[0];

        if ($calc >= 2500) {
            $calc = 2500;
        }

        return $calc;
    }

    public function pagIbigContribution() {
        $pagibig = DB::table('deductions')
            ->where('user_id', '=', auth()->user()->id)
            ->where('id', '=', '3')
            ->pluck('rate');

        if ($this->position->salary_amount > 1500) {
            return 200;
        } else {
            return 100;
        }

        return $calc;
    }

    public function netTaxableIncome(int $employee_id) {
        $grossPay = $this->grossPay($employee_id);
        $contributions = $this->gsisContribution() + $this->philHealthContribution() + $this->pagIbigContribution();

        return $grossPay - $contributions;
    }

    public function withholdingTax(int $employee_id) {
        $netTaxableIncome = $this->netTaxableIncome($employee_id);
        $calc = 0;

        if ($netTaxableIncome <= 20833) {
            return 0;
        } elseif ($netTaxableIncome > 20833 && $netTaxableIncome <= 33332) {
            $calc = $netTaxableIncome - 20833;
            return $calc * 0.15;
        } elseif ($netTaxableIncome >= 33333 && $netTaxableIncome <= 66666) {
            $calc = $netTaxableIncome - 33333;
            $calc *= 0.20;
            $calc += 1875;
            return $calc;
        } elseif ($netTaxableIncome >= 66667 && $netTaxableIncome <= 166666) {
            $calc = $netTaxableIncome - 66667;
            $calc *= 0.25;
            $calc += 8541.80;
            return $calc;
        } elseif ($netTaxableIncome >= 166667 && $netTaxableIncome <= 666666) {
            $calc = $netTaxableIncome - 166667;
            $calc *= 0.30;
            $calc += 33541.80;
            return $calc;
        } elseif ($netTaxableIncome >= 666667) {
            $calc = $netTaxableIncome - 666667;
            $calc *= 0.35;
            $calc += 183541.80;
            return $calc;
        }
    }

    public function totalDeductions(int $employee_id) {
        $gsis = $this->gsisContribution();
        $philHealth = $this->philHealthContribution();
        $pagibig = $this->pagIbigContribution();
        $withholdingTax = $this->withholdingTax($employee_id);

        $total = $gsis + $philHealth + $pagibig + $withholdingTax;

        return $total;
    }

    public function netPay(int $employee_id) {
        $grossPay = $this->grossPay($employee_id);
        $totalDeductions = $this->totalDeductions($employee_id);

        $sum = $grossPay - $totalDeductions;

        return $sum;
    }

    #[Scope]
    protected function findAllWithUserID(Builder $query): void {
        $query->where('user_id', '=', auth()->user()->id);
    }
}
