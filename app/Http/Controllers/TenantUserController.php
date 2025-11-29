<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;

class TenantUserController extends Controller
{
    // Menampilkan daftar tenant untuk admin
    public function index()
    {
        $tenants = Tenant::all();
        return view('tenant.tenantuser', compact('tenants'));
    }

    // Menyetujui tenant
    public function approve(Tenant $tenant)
    {
        $tenant->update(['status' => 'aktif']); // Sesuaikan dengan enum baru
        return redirect()->route('tenantuser.index')->with('crud_success', 'Tenant berhasil diaktifkan.');
    }
    
    public function reject(Tenant $tenant)
    {
        $tenant->update(['status' => 'nonaktif']); // Sesuaikan dengan enum baru
        return redirect()->route('tenantuser.index')->with('crud_success', 'Tenant berhasil dinonaktifkan.');
    }
    
}

