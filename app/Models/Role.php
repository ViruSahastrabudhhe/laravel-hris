<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserRole;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'type',
    ];

    public function userRole() {
        return $this->hasMany(UserRole::class, 'role_id', 'id');
    }
}
