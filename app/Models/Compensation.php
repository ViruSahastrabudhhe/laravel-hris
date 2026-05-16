<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\CompensationCategory;
use OwenIt\Auditing\Contracts\Auditable;

class Compensation extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\CompensationFactory> */
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "compensations";

    protected $fillable = [
        'category',
        'rate',
        'name',
        'description',
    ];

    #[Scope]
    protected function otherCompensations(Builder $query): void {
        $query->where('is_mandatory', false);
    }
}
