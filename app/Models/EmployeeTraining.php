<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\EmployeeTrainingStatus;

class EmployeeTraining extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeTrainingFactory> */
    use HasFactory;

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
