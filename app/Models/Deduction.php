<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\DeductionType;

class Deduction extends Model
{
    /** @use HasFactory<\Database\Factories\DeductionFactory> */
    use HasFactory;

    protected $table = "deductions";

    protected $fillable = [
        'name',
        'rate',
        'type',
        'description',
    ];

    #[Scope]
    protected function otherDeductions(Builder $query): void {
        $query->where('type', '!=', DeductionType::Mandatory);
    }
}
