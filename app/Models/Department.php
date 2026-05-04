<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class Department extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'name',
        'department_code',
        'department_head',
        'is_active',
        'description',
    ];
}
