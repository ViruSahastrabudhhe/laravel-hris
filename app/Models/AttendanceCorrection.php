<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AttendanceCorrection extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'attendance_corrections';

    protected $fillable = [
        'employee_id',
        'attendance_id',
        'remarks',
        'proof',
    ];
}
