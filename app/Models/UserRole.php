<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Role;

class UserRole extends Model
{
    /** @use HasFactory<\Database\Factories\UserRoleFactory> */
    use HasFactory;

    protected $table = 'user_roles';

    protected $fillable = [
        'user_id',
        'role_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function role() {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function hasRole() {
        
    }
}
