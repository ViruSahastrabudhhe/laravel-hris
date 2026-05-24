<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class Department extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'departments';

    protected $fillable = [
        'name',
        'department_code',
        'department_head',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
