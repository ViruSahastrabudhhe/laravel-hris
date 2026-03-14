<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class Deduction extends Model
{
    /** @use HasFactory<\Database\Factories\DeductionFactory> */
    use HasFactory;

    protected $table = "deductions";

    protected $fillable = [
        'name',
        'rate',
        'description',
        'user_id',
    ];

    #[Scope]
    protected function findAllWithUserID(Builder $query): void {
        $query->where('user_id', '=', auth()->user()->id);
    }

    #[Scope]
    protected function otherDeductions(Builder $query): void {
        $query->where('id', '>', 3);
    }
}
