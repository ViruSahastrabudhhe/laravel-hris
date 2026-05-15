<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Database\Factories\Administration\PositionFactory;
use OwenIt\Auditing\Contracts\Auditable;

#[UseFactory(PositionFactory::class)]
class Position extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\PositionFactory> */
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'positions';

    protected $fillable = [
        'title',
        'is_active',
        'status',
        'total_employees',
        'description',
    ];

    #[Scope]
    protected function findAllWithUserID(Builder $query): void {
        $query->where('user_id', '=', auth()->user()->id);
    }
}
