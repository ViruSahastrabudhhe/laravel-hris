<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\EmployeeTrainingStatus;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeTraining extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\EmployeeTrainingFactory> */
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'employee_trainings';

    protected $fillable = [
        'employee_id',
        'training_id',
        'status',
        'completion_date',
        'remarks',
        'user_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }
}
