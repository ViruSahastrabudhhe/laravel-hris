<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class EmployeeTraining extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeTrainingFactory> */
    use HasFactory;

    protected $table = 'employee_trainings';

    protected $fillable = [
        'employee_id',
        'training_id',
        'user_id',
        'status',
        'completion_date',
        'remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    #[Scope]
    protected function findAllWithUserID(Builder $query): void
    {
        $query->where('user_id', '=', auth()->user()->id);
    }
}
