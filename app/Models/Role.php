<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles'; // Nama tabel di database

    protected $fillable = [
        'id_user',
        'role',
    ]; // Kolom yang bisa diisi secara massal

    /**
     * Relasi dengan model User (Many to One)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function tenantRoleUsers()
    {
        return $this->hasMany(TenantRoleUser::class, 'id_role');
    }

    /**
     * Relasi ke Tenants melalui TenantRoleUser
     */
    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'tenant_role_users', 'id_role', 'id_tenant');
    }
}
