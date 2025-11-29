<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Databarang; // Pastikan ini ditambahkan di atas jika belum
use Barryvdh\DomPDF\Facade\Pdf;




class PengelolaController extends Controller
{

    public function indexLaporanStok(Request $request)
{
    $user = Auth::user();

    // Ambil semua tenant yang terkait dengan user
    $tenantIds = Tenant::where('email', $user->email)->pluck('id')
        ->merge(
            Tenant::whereHas('tenantRoleUsers.role', fn($q) => $q->where('id_user', $user->id))
                ->pluck('id')
        )->unique();

    // Ambil semua tenant untuk dropdown filter (jika dibutuhkan)
    $tenants = Tenant::whereIn('id', $tenantIds)->get();

    // Mulai query untuk stok barang dari tabel databarangs
    $query = Databarang::with('satuan')
        ->whereIn('id_tenant', $tenantIds);

    // Filter berdasarkan tenant jika dipilih
    if ($request->filled('id_tenant')) {
        $query->where('id_tenant', $request->id_tenant);
    }

    // Filter pencarian
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('id_barang', 'like', "%{$search}%")
              ->orWhere('nama_barang', 'like', "%{$search}%")
              ->orWhereHas('satuan', function ($q2) use ($search) {
                  $q2->where('nama', 'like', "%{$search}%");
              });
        });
    }

    // Eksekusi query
    $stokBarangs = $query->get();

    return view('laporanstok', compact('stokBarangs', 'tenants'));
}

    public function cetakLaporanBarangKeluar(Request $request)
    {
            \Carbon\Carbon::setLocale('id');

        $user = Auth::user();

        $tenantIds = Tenant::where('email', $user->email)->pluck('id')
            ->merge(
                Tenant::whereHas('tenantRoleUsers.role', fn($q) => $q->where('id_user', $user->id))
                    ->pluck('id')
            )->unique();

        if (!$request->filled(['tanggal_awal', 'tanggal_akhir'])) {
            return back()->with('crud_error', 'Harap isi tanggal awal & akhir.');
        }

        $awal  = $request->tanggal_awal;
        $akhir = $request->tanggal_akhir;

        if ($akhir < $awal) {
            return back()->with('crud_error', 'Tanggal akhir tidak boleh sebelum tanggal awal.');
        }

        $query = BarangKeluar::with(['dataBarang.satuan'])
            ->whereIn('id_tenant', $tenantIds)
            ->whereBetween('tanggal', [$awal, $akhir]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_barangkeluar', 'like', "%{$search}%")
                    ->orWhere('tanggal',        'like', "%{$search}%")
                    ->orWhere('jumlah_keluar',  'like', "%{$search}%")
                    ->orWhereHas('dataBarang', function ($q2) use ($search) {
                        $q2->where('nama_barang', 'like', "%{$search}%")
                            ->orWhereHas(
                                'satuan',
                                fn($q3) =>
                                $q3->where('nama', 'like', "%{$search}%")
                            );
                    });
            });
        }

        $barangKeluars = $query->get();

        $pdf = Pdf::loadView('laporankeluar_pdf', compact('barangKeluars', 'awal', 'akhir'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan_barang_keluar.pdf');
    }


    public function cetakLaporanBarangMasuk(Request $request)
    {
            \Carbon\Carbon::setLocale('id');

        $user = Auth::user();

        $directTenants = Tenant::where('email', $user->email)->pluck('id');
        $relatedTenants = Tenant::whereHas('tenantRoleUsers', function ($query) use ($user) {
            $query->whereHas('role', function ($subQuery) use ($user) {
                $subQuery->where('id_user', $user->id);
            });
        })->pluck('id');

        $tenantIds = $directTenants->merge($relatedTenants)->unique();

        if (!$request->filled('tanggal_awal') || !$request->filled('tanggal_akhir')) {
            return redirect()->back()->with('crud_error', 'Harap isi tanggal awal dan akhir.');
        }

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        if ($tanggalAkhir < $tanggalAwal) {
            return redirect()->back()->with('crud_error', 'Tanggal akhir tidak boleh sebelum tanggal awal.');
        }

        $query = BarangMasuk::with(['dataBarang.satuan'])
            ->whereIn('id_tenant', $tenantIds)
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id_barangmasuk', 'like', "%{$search}%")
                    ->orWhere('jumlah_masuk', 'like', "%{$search}%")
                    ->orWhereHas('dataBarang', function ($q2) use ($search) {
                        $q2->where('nama_barang', 'like', "%{$search}%")
                            ->orWhereHas('satuan', function ($q3) use ($search) {
                                $q3->where('nama', 'like', "%{$search}%");
                            });
                    });
            });
        }

        $barangMasuks = $query->get();

        $pdf = Pdf::loadView('laporanmasuk_pdf', compact('barangMasuks', 'tanggalAwal', 'tanggalAkhir'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan_barang_masuk.pdf');
    }


    public function indexLaporanBarangMasuk(Request $request)
    {
        $user = Auth::user();

        // Ambil semua tenant yg terkait user
        $tenantIds = Tenant::where('email', $user->email)->pluck('id')
            ->merge(
                Tenant::whereHas('tenantRoleUsers.role', fn($q) => $q->where('id_user', $user->id))
                    ->pluck('id')
            )->unique();

        /* ---------- 1. Mulai query builder ---------- */
        $query = BarangMasuk::with(['dataBarang', 'dataBarang.satuan'])
            ->whereIn('id_tenant', $tenantIds);

        /* ---------- 2. Filter tanggal (jika ada) ----- */
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $awal  = $request->tanggal_awal;
            $akhir = $request->tanggal_akhir;

            if ($akhir < $awal) {
                return back()->with('crud_error', 'Tanggal akhir tidak boleh sebelum tanggal awal.');
            }

            $query->whereBetween('tanggal', [$awal, $akhir]);
        }

        /* ---------- 3. Filter search (jika ada) ------ */
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('id_barangmasuk', 'like', "%{$search}%")
                    ->orWhere('jumlah_masuk', 'like', "%{$search}%")
                    ->orWhereHas('dataBarang', function ($q2) use ($search) {
                        $q2->where('nama_barang', 'like', "%{$search}%")
                            ->orWhereHas('satuan', function ($q3) use ($search) {
                                $q3->where('nama', 'like', "%{$search}%");
                            });
                    });
            });
        }


        /* ---------- 4. Eksekusi query --------------- */
        // Jika tidak ada filter sama sekali, kembalikan koleksi kosong
        $barangMasuks = ($request->filled('tanggal_awal') || $request->filled('search'))
            ? $query->get()
            : collect();

        $tenants = Tenant::whereIn('id', $tenantIds)->get();

        return view('laporanmasuk', compact('barangMasuks', 'tenants'));
    }




    public function indexLaporanBarangKeluar(Request $request)
    {
        $user = Auth::user();

        // ─── Tenant milik user ───────────────────────────────────────────────
        $tenantIds = Tenant::where('email', $user->email)->pluck('id')
            ->merge(
                Tenant::whereHas('tenantRoleUsers.role', fn($q) => $q->where('id_user', $user->id))
                    ->pluck('id')
            )->unique();

        // ─── Mulai Query ─────────────────────────────────────────────────────
        $query = BarangKeluar::with(['dataBarang.satuan'])
            ->whereIn('id_tenant', $tenantIds);

        // ─── Filter tanggal (opsional) ───────────────────────────────────────
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $awal  = $request->tanggal_awal;
            $akhir = $request->tanggal_akhir;

            if ($akhir < $awal) {
                return back()->with('crud_error', 'Tanggal akhir tidak boleh sebelum tanggal awal.');
            }

            $query->whereBetween('tanggal', [$awal, $akhir]);
        }

        // ─── Filter search (opsional) ────────────────────────────────────────
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('no_barangkeluar', 'like', "%{$search}%")
                    ->orWhere('tanggal',         'like', "%{$search}%")
                    ->orWhere('jumlah_keluar',   'like', "%{$search}%")
                    ->orWhereHas('dataBarang', function ($q2) use ($search) {
                        $q2->where('nama_barang', 'like', "%{$search}%")
                            ->orWhereHas(
                                'satuan',
                                fn($q3) =>
                                $q3->where('nama', 'like', "%{$search}%")
                            );
                    });
            });
        }

        // ─── Eksekusi / kosongkan jika tak ada filter ────────────────────────
        $barangKeluars = ($request->filled('tanggal_awal') || $request->filled('search'))
            ? $query->get()
            : collect();

        $tenants = Tenant::whereIn('id', $tenantIds)->get();

        return view('laporankeluar', compact('barangKeluars', 'tenants'));
    }






    // Menampilkan daftar tenant di halaman namatenant
    public function showNamatenant()
    {
        $user = Auth::user();

        // Ambil tenant berdasarkan email langsung
        $directTenants = Tenant::where('email', $user->email)->get();

        // Ambil tenant berdasarkan relasi melalui TenantRoleUser
        $relatedTenants = Tenant::whereHas('tenantRoleUsers', function ($query) use ($user) {
            $query->whereHas('role', function ($subQuery) use ($user) {
                $subQuery->where('id_user', $user->id);
            });
        })->get();

        // Gabungkan hasil query
        $tenants = $directTenants->merge($relatedTenants)->unique('id');

        return view('namatenant', compact('tenants'));
    }


    // Menampilkan daftar tenant
    public function index()
    {
        $tenants = Tenant::where('email', Auth::user()->email)->get();
        return view('pengelola', compact('tenants'));
    }


    // Menyimpan tenant baru ke database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:tenants,kode',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string'
        ]);

        // Cek apakah tenant dengan email user sudah ada
        $existingTenant = Tenant::where('email', Auth::user()->email)->first();

        if ($existingTenant) {
            return redirect()->back()->with('crud_error', 'Anda sudah mendaftar sebagai tenant!');
        }

        // Jika belum ada, lanjutkan insert
        Tenant::create([
            'nama' => Auth::user()->nama,
            'email' => Auth::user()->email,
            'nama_perusahaan' => $request->nama_perusahaan,
            'kode' => $request->kode,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'status' => 'pending'
        ]);

        return redirect()->route('pengelola')->with('crud_success', 'Tenant berhasil ditambahkan.');
    }


    // Menampilkan data tenant tertentu
    public function edit(Tenant $tenant)
    {
        return response()->json($tenant);
    }

    // Memperbarui data tenant di database
    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:tenants,kode,' . $tenant->id,
            'email' => 'required|email|unique:tenants,email,' . $tenant->id,
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        // Simpan semua data kecuali status agar tetap pending
        $updateData = $request->except(['status']);
        $tenant->update($updateData);

        return redirect()->route('pengelola')->with('crud_success', 'Tenant berhasil diperbarui.');
    }


    // Menghapus tenant dari database
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return redirect()->route('pengelola')->with('crud_success', 'Tenant berhasil dihapus.');
    }
}
