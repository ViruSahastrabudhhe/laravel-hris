<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IPCRForm extends Model
{
    protected $table = 'ipcr_forms';

    protected $fillable = [
        'employee_id',
        'performance_cycle_id',
        'department',
        'status',
        'final_rating',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function cycle()
    {
        return $this->belongsTo(PerformanceCycle::class, 'performance_cycle_id');
    }

    public function entries()
    {
        return $this->hasMany(IPCREntry::class, 'ipcr_form_id');
    }

    public function developmentNeeds()
    {
        return $this->hasMany(IPCRDevelopmentNeed::class, 'ipcr_form_id');
    }
    public function computeFinalRating()
    {
        return round($this->entries()->avg('average_rating'), 2);
    }

    public function isApproved() {
        return $this->status=='Approved';
    }
}
