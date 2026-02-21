<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\Administration\PositionFactory;

#[UseFactory(PositionFactory::class)]
class Position extends Model
{
    /** @use HasFactory<\Database\Factories\PositionFactory> */
    use HasFactory;

    protected $table = 'positions';

    protected $fillable = [
        'title',
    ];
}
