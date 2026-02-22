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
        'deduction',
        'description',
    ];

    #[Scope]
    protected function findAllWithUserID(Builder $query): void {
        $query->where('user_id', '=', auth()->user()->id);
    }
}
