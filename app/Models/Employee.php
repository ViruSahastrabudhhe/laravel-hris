<?php

namespace App\Models;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

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
        'salary',
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

    #[Scope]
    protected function findAllWithUserID(Builder $query): void {
        $query->where('user_id', '=', auth()->user()->id);
    } 
}
