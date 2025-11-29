<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'nama_perusahaan',
        'kode',
        'email',
        'telepon',
        'alamat',
        'status',
    ];

    public function tenantRoleUsers()
    {
        return $this->hasMany(TenantRoleUser::class, 'id_tenant');
    }

    /**
     * Relasi ke Roles melalui TenantRoleUser
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'tenant_role_users', 'id_tenant', 'id_role');
    }

    /**
     * Relasi ke Users melalui Roles dan TenantRoleUser
     */
    public function users()
    {
        return $this->hasManyThrough(
            User::class, Role::class,
            'id', // Primary key di tenants
            'id', // Primary key di roles
            'id', // Foreign key di tenant_role_users yang merujuk ke tenants
            'id_user' // Foreign key di roles yang merujuk ke users
        );
    }



}
