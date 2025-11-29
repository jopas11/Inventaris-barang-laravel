<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use App\Models\TenantRoleUser;
use App\Models\Role;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        $role = Role::where('id_user', $user->id)->first();
        if (!$role) {
            return;
        }

        $tenantRoleUser = TenantRoleUser::where('id_role', $role->id)->first();
        if (!$tenantRoleUser) {
            return;
        }

        $tenantId = $tenantRoleUser->id_tenant;

        $builder->where('id_tenant', $tenantId);
    }
}
