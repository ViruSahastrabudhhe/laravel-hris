<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\CompensationType;

class Compensation extends Model
{
    /** @use HasFactory<\Database\Factories\CompensationFactory> */
    use HasFactory;

    protected $table = "compensations";

    protected $fillable = [
        'name',
        'rate',
        'type',
        'description',
    ];

    #[Scope]
    protected function otherCompensations(Builder $query): void {
        $query->where('is_mandatory', false);
    }
}
