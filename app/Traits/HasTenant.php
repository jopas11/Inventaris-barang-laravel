<?php

namespace App\Traits;

use App\Scopes\TenantScope;
use Illuminate\Support\Facades\Auth;

trait HasTenant
{
    protected static function bootHasTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            $user = Auth::user();
            if ($user && !$model->id_tenant) {
                // Ambil tenant dari user, contoh dari relasi role tenant kamu
                $role = \App\Models\Role::where('id_user', $user->id)->first();
                if ($role) {
                    $tenantRoleUser = \App\Models\TenantRoleUser::where('id_role', $role->id)->first();
                    if ($tenantRoleUser) {
                        $model->id_tenant = $tenantRoleUser->id_tenant;
                    }
                }
            }
        });

        static::updating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $tenantId = null;
                $role = \App\Models\Role::where('id_user', $user->id)->first();
                if ($role) {
                    $tenantRoleUser = \App\Models\TenantRoleUser::where('id_role', $role->id)->first();
                    if ($tenantRoleUser) {
                        $tenantId = $tenantRoleUser->id_tenant;
                    }
                }
                if ($tenantId !== $model->id_tenant) {
                    abort(403, 'Akses ditolak (tenant tidak cocok saat update).');
                }
            }
        });

        static::deleting(function ($model) {
            $user = Auth::user();
            if ($user) {
                $tenantId = null;
                $role = \App\Models\Role::where('id_user', $user->id)->first();
                if ($role) {
                    $tenantRoleUser = \App\Models\TenantRoleUser::where('id_role', $role->id)->first();
                    if ($tenantRoleUser) {
                        $tenantId = $tenantRoleUser->id_tenant;
                    }
                }
                if ($tenantId !== $model->id_tenant) {
                    abort(403, 'Akses ditolak (tenant tidak cocok saat delete).');
                }
            }
        });
    }
}
