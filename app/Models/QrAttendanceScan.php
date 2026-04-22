<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrAttendanceScan extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'time_in',
        'time_out',
        'pm_in',
        'pm_out',
        'overtime_in',
        'overtime_out',
        'user_id',
        'qr_code_hash',
        'scanned_at',
        'scanner_ip',
    ];

    protected $casts = [
        'date' => 'date',
        'scanned_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
