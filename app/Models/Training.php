<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\TrainingStatus;
use App\Models\EmployeeTraining;
use OwenIt\Auditing\Contracts\Auditable;

class Training extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\TrainingFactory> */
    use HasFactory, \OwenIt\Auditing\Auditable;

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

    #[Scope]
    protected function isAvailable(Builder $query): void
    {
        $query->where('status', '!=', TrainingStatus::Completed->value);
    }
}
