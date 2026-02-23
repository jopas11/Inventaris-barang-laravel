<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'status',
        'google_id',
        'avatar',
        'otp',
        'otp_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function role()
    {
        return $this->hasOne(Role::class, 'id_user');
    }

    public function tenants()
    {
        return $this->hasManyThrough(
            Tenant::class,
            TenantRoleUser::class,
            'id_role', // Foreign key di tenant_role_users yang merujuk ke roles
            'id', // Foreign key di tenants
            'id', // Primary key di users
            'id_tenant' // Foreign key di tenant_role_users yang merujuk ke tenants
        );
    }

    public function getAvatarUrlAttribute()
{
    // Kalau ada avatar, tampilkan
    if (!empty($this->avatar)) {
        return $this->avatar;
    }

    // Kalau tidak ada, tampilkan default
    return asset('images/1.jpg');
}

}
