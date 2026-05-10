<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceScanLog extends Model
{
    protected $table = 'attendance_scan_logs';

    protected $fillable = [
        'attendance_id',
        'employee_id',
        'scan_type',
        'scanned_at',
        'device_name',
        'ip_address',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];
}
