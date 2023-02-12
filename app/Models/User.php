<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    protected $with = [
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'role_id',
        'password',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Default values for attributes
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'role_id' => 2,
    ];

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string $role): bool {
        return $this->role->name == $role;
    }

    public function bills() {
        return $this->hasMany(Bill::class);
    }

    public function reports() {
        return $this->hasMany(Report::class);
    }
}
