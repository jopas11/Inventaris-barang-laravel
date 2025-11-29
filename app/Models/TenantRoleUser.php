<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantRoleUser extends Model
{
    use HasFactory;

    protected $table = 'tenant_role_users'; // Nama tabel

    protected $fillable = [
        'id_tenant',
        'id_role',
    ];

    /**
     * Relasi ke Tenant.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'id_tenant');
    }

    /**
     * Relasi ke Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    /**
     * Relasi ke User melalui Role.
     */
    public function user()
    {
        return $this->hasOneThrough(User::class, Role::class, 'id', 'id', 'id_role', 'id_user');
    }
}
