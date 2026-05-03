<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class Training extends Model
{
    /** @use HasFactory<\Database\Factories\TrainingFactory> */
    use HasFactory;

    protected $table = 'trainings';

    protected $fillable = [
        'program_title',
        'type',
        'capacity',
        'participants',
        'start_date',
        'end_date',
        'venue',
        'status',
        'user_id',
    ];

    public function employeeTrainings()
    {
        return $this->hasMany(EmployeeTraining::class);
    }

    #[Scope]
    protected function findAllWithUserID(Builder $query): void
    {
        $query->where('user_id', '=', auth()->user()->id);
    }
}
