<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceCorrection extends Model
{
    protected $table = 'attendance_corrections';

    protected $fillable = [
        'employee_id',
        'attendance_id',
        'remarks',
        'proof',
    ];
}
