<?php

namespace App\Models;

use App\Models\Department;
use App\Models\Position;
use App\Models\Attendance;
use App\Models\EmployeeDeduction;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeLeave;
use App\Models\EmployeeLeaveBalance;
use App\Models\EmployeeWorkSchedule;
use Carbon\Carbon;
use App\Enums\EmploymentType;
use App\Models\Scopes\EmployeeScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;

#[ScopedBy([EmployeeScope::class])]
class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'employees';

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'email',
        'date_of_birth',
        'phone_number',
        'employment_type',
        'is_active',
        'address_id',
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

    public function attendance() {
        return $this->hasMany(Attendance::class);
    }

    public function employeeAttendance() {
        return $this->hasOne(EmployeeAttendance::class);
    }

    public function deductions() {
        return $this->hasMany(EmployeeDeduction::class);
    }

    public function address() {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }

    public function leaves() {
        return $this->hasMany(EmployeeLeave::class);
    }

    public function leaveBalance() {
        return $this->hasOne(EmployeeLeaveBalance::class);
    }

    public function employeeWorkSchedule() {
        return $this->hasOne(EmployeeWorkSchedule::class, 'employee_id', 'id');
    }

    public function isRegular() {
        return $this->employment_type == EmploymentType::Regular->name;
    }

    public function hoursWorked() {
        $total_minutes = DB::table('attendances')
            ->join('employees', 'attendances.employee_id', '=', 'employees.id')
            ->where('attendances.user_id', '=', auth()->user()->id)
            ->where('attendances.employee_id', '=', $this->id)
            ->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('attendances.total_minutes');

        return round($total_minutes / 60, 2);
    }

    public function daysWorked() {
        $entries = DB::table('attendances')
            ->join('employees', 'attendances.employee_id', '=', 'employees.id')
            ->where('attendances.user_id', '=', auth()->user()->id)
            ->where('attendances.employee_id', '=', $this->id)
            ->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();

        return $entries;
    }

    public function overtimeWorked() {
        $overtime_minutes = DB::table('attendances')
            ->join('employees', 'attendances.employee_id', '=', 'employees.id')
            ->where('attendances.user_id', '=', auth()->user()->id)
            ->where('attendances.employee_id', '=', $this->id)
            ->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('overtime_minutes');

        return round($overtime_minutes / 60, 2);
    }

    public function totalHoursWorked() {
        return $this->hoursWorked() + $this->overtimeWorked();
    }

    public function hourlyRate() {
        $hourlyRate = ($this->position->salary_amount * 12) / (261 * 8);
        return $hourlyRate;
    }

    public function grossPay() {
        $overtime = $this->overtimePay();
        $monthlySalary = $this->hoursWorked() * $this->hourlyRate();

        if (!$this->isRegular()) {
            return $monthlySalary + $overtime;
        }
        
        return $this->position->salary_amount + $overtime;
    }

    public function overtimePay() {
        $overtimeHours = $this->overtimeWorked();
        $hourlyRate = $this->hourlyRate();

        $overtimeHourlyRate = $hourlyRate * 1.25;
        $overtimePay = $overtimeHourlyRate * $overtimeHours;

        return $overtimePay;
    }

    public function gsisContribution(): float {
        if (!$this->isRegular()) {
            return 0;
        }

        $gsis = DB::table('deductions')
            ->where('user_id', '=', auth()->user()->id)
            ->where('name', '=', 'GSIS Contribution')
            ->pluck('rate');

        $calc = $this->position->salary_amount * $gsis[0];

        return $calc;
    }

    public function philHealthContribution(): float {
        if (!$this->isRegular()) {
            return 0;
        }
        
        $philhealth = DB::table('deductions')
            ->where('user_id', '=', auth()->user()->id)
            ->where('name', '=', 'PhilHealth Personal Share Contribution')
            ->pluck('rate');

        $calc = $this->position->salary_amount * $philhealth[0];

        if ($calc >= 2500) {
            $calc = 2500;
        }

        return $calc;
    }

    public function pagIbigContribution(): float {
        if (!$this->isRegular()) {
            return 0;
        }

        $pagibig = DB::table('deductions')
            ->where('user_id', '=', auth()->user()->id)
            ->where('name', '=', 'Pag-Ibig Personal Share Contribution')
            ->pluck('rate');

        if ($this->position->salary_amount > 1500) {
            return 200;
        } else {
            return 100;
        }
    }

    public function otherDeductions(): float {
        if (!$this->isRegular()) {
            return 0;
        }

        $otherDeductions = $this->deductions->sum('amount');

        return $otherDeductions;
    }

    public function netTaxableIncome() {
        $grossPay = $this->grossPay();
        $contributions = $this->gsisContribution() + $this->philHealthContribution() + $this->pagIbigContribution();

        return $grossPay - $contributions;
    }

    public function withholdingTax() {
        $netTaxableIncome = $this->netTaxableIncome();
        $calc = 0;

        if ($netTaxableIncome < 20833) {
            return 0;
        } elseif ($netTaxableIncome >= 20833 && $netTaxableIncome <= 33332) {
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

    public function totalDeductions() {
        $gsis = $this->gsisContribution();
        $philHealth = $this->philHealthContribution();
        $pagibig = $this->pagIbigContribution();
        $withholdingTax = $this->withholdingTax();
        $otherDeductions = $this->otherDeductions();

        $total = $gsis + $philHealth + $pagibig + $withholdingTax + $otherDeductions;

        return $total;
    }

    public function netPay() {
        $grossPay = $this->grossPay();
        $totalDeductions = $this->totalDeductions();

        $sum = $grossPay - $totalDeductions;

        return $sum;
    }

    #[Scope]
    protected function findAllWithUserID(Builder $query): void {
        $query->where('user_id', '=', auth()->user()->id);
    }
}
