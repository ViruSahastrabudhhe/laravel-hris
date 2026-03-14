<?php

namespace App\Models;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class Attendance extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'date',
        'time_in',
        'time_out',
        'pm_in',
        'pm_out',
        'overtime_in',
        'overtime_out',
        'employee_id',
        'user_id',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    #[Scope]
    protected function findAllWithUserID(Builder $query): void {
        $query->where('user_id', '=', auth()->user()->id);
    }

    #[Scope]
    protected function currentMonth(Builder $query): void {
        $query->whereYear('created_at', '=', Carbon::now()->year)
              ->whereMonth('created_at', '=', Carbon::now()->month);
    }
}
