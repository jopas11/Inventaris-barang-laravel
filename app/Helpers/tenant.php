<?php

use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;

if (!function_exists('getCurrentTenant')) {
    function getCurrentTenant()
{
    $role = Auth::user()->role;
    if (!$role) return null;

    $tenantRoleUser = \App\Models\TenantRoleUser::where('id_role', $role->id)->first();
    if (!$tenantRoleUser) return null;

    return Tenant::find($tenantRoleUser->id_tenant);
}

}
