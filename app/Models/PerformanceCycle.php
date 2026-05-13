<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PerformanceCycle extends Model
{
    protected $table = 'performance_cycles';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
    ];

    public function ipcrForms()
    {
        return $this->hasMany(IPCRForm::class, 'performance_cycle_id');
    }
}
