<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IPCRDevelopmentNeed extends Model
{
    protected $table = 'ipcr_development_needs';

    protected $fillable = [
        'ipcr_form_id',
        'development_needs',
        'recommended_training',
    ];

    public function form()
    {
        return $this->belongsTo(IPCRForm::class, 'ipcr_form_id');
    }
}
