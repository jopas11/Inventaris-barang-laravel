<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Role;
use App\Models\TenantRoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantRoleUserController extends Controller
{
    /**
     * Menampilkan semua hubungan Tenant-Role-User.
     */
     public function index()
     {
         // Ambil semua tenant yang dimiliki oleh user berdasarkan email
         $tenants = Tenant::where('email', Auth::user()->email)->get();
         
         // Ambil ID dari tenant yang ditemukan
         $tenantIds = $tenants->pluck('id');
     
         // Ambil data TenantRoleUser yang hanya berhubungan dengan tenant milik user
         $tenantRoleUsers = TenantRoleUser::with(['tenant', 'role.user'])
             ->whereIn('id_tenant', $tenantIds)
             ->whereHas('role', function ($query) {
                 $query->where('role', 'user');
             })
             ->get();
     
         // Ambil semua ID user yang sudah terdaftar di **tenant lain**
         $registeredRoleIds = TenantRoleUser::whereNotIn('id_tenant', $tenantIds)
             ->pluck('id_role')
             ->toArray();
     
         // Ambil hanya role "user" yang **tidak terdaftar di tenant lain** atau yang termasuk dalam tenant milik user
         $roles = Role::whereHas('user', function ($query) {
            $query->where('status', 'aktif'); // Hanya user yang aktif
        })
        ->where('role', 'user')
        ->whereNotIn('id', $registeredRoleIds) // Hanya user yang belum masuk ke tenant lain
        ->get();
        
    
     
         return view('tenant.tenantroleusers', compact('tenantRoleUsers', 'tenants', 'roles'));
     }
     
    
    /**
     * Menyimpan hubungan baru antara Tenant, Role, dan User.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_tenant' => 'required|exists:tenants,id',
            'id_role' => 'required|exists:roles,id',
        ]);

        // Cek apakah kombinasi id_tenant dan id_role sudah ada
        $exists = TenantRoleUser::where('id_tenant', $request->id_tenant)
            ->where('id_role', $request->id_role)
            ->exists();

        if ($exists) {
            return redirect()->route('tenantroleusers.index')
                ->with('crud_error', 'User sudah terdaftar di tenant ini.');
        }

        // Simpan data baru jika belum ada
        TenantRoleUser::create([
            'id_tenant' => $request->id_tenant,
            'id_role' => $request->id_role,
        ]);

        return redirect()->route('tenantroleusers.index')->with('crud_success', 'Data berhasil ditambahkan!');
    }

    /**
     * Menampilkan data Tenant-Role-User untuk diedit.
     */
    public function edit($id)
    {
        $tenantRoleUser = TenantRoleUser::find($id);

        if (!$tenantRoleUser) {
            return redirect()->route('tenantroleusers.index')->with('error', 'Data tidak ditemukan');
        }

        return redirect()->route('tenantroleusers.index')->with('editData', $tenantRoleUser);
    }

    /**
     * Memperbarui hubungan Tenant-Role-User tertentu.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_tenant' => 'required|exists:tenants,id',
            'id_role' => 'required|exists:roles,id',
        ]);

        $tenantRoleUser = TenantRoleUser::find($id);
        if (!$tenantRoleUser) {
            return redirect()->route('tenantroleusers.index')->with('crud_error', 'Data tidak ditemukan.');
        }

        // Cek apakah data yang dikirim sama dengan yang ada di database
        if ($tenantRoleUser->id_tenant == $request->id_tenant && $tenantRoleUser->id_role == $request->id_role) {
            return redirect()->route('tenantroleusers.index')->with('crud_error', 'Tidak ada perubahan pada data.');
        }

        // Cek apakah kombinasi tenant dan role sudah ada di database (kecuali data yang sedang diupdate)
        $exists = TenantRoleUser::where('id_tenant', $request->id_tenant)
            ->where('id_role', $request->id_role)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->route('tenantroleusers.index')
                ->with('crud_error', 'User sudah terdaftar di tenant ini.');
        }

        // Update jika tidak ada duplikasi
        $tenantRoleUser->update([
            'id_tenant' => $request->id_tenant,
            'id_role' => $request->id_role,
        ]);

        return redirect()->route('tenantroleusers.index')->with('crud_success', 'Data berhasil diperbarui.');
    }

    /**
     * Menghapus hubungan Tenant-Role-User tertentu.
     */
    public function destroy($id)
    {
        $tenantRoleUser = TenantRoleUser::find($id);
        if (!$tenantRoleUser) {
            return redirect()->route('tenantroleusers.index')->with('error', 'Data tidak ditemukan');
        }

        $tenantRoleUser->delete();

        return redirect()->route('tenantroleusers.index')->with('crud_success', 'Data berhasil dihapus');
    }
}
