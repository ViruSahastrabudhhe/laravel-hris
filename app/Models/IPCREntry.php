<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IPCREntry extends Model
{
    protected $table = 'ipcr_entries';

    protected $fillable = [
        'ipcr_form_id',
        'kra',
        'objectives',
        'success_indicators',
        'actual_accomplishments',
        'quality_rating',
        'efficiency_rating',
        'timeliness_rating',
        'average_rating',
    ];

    public function form()
    {
        return $this->belongsTo(IPCRForm::class, 'ipcr_form_id');
    }

    public function calculateAverage()
    {
        if (
            $this->quality_rating &&
            $this->efficiency_rating &&
            $this->timeliness_rating
        ) {
            return round(
                ($this->quality_rating +
                    $this->efficiency_rating +
                    $this->timeliness_rating) / 3,
                2
            );
        }

        return null;
    }
}
