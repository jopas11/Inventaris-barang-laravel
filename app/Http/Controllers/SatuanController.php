<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = Satuan::all(); // sudah otomatis filter tenant lewat global scope
        return view('satuan', compact('satuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Satuan::create([
            'nama' => $request->nama,
            // id_tenant tidak perlu diset manual, akan otomatis oleh HasTenant
        ]);

        return redirect()->back()->with('crud_success', 'Data satuan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $satuan = Satuan::findOrFail($id); // akan otomatis error 404 jika bukan tenant-nya
        return view('satuan.edit', compact('satuan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $satuan = Satuan::findOrFail($id); // otomatis filter tenant
        $satuan->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('satuan.index')->with('crud_success', 'Data satuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $satuan = Satuan::findOrFail($id); // sudah filter tenant

        if ($satuan->databarangs()->exists()) {
            return redirect()->back()->with('crud_error', 'Tidak dapat menghapus satuan karena digunakan pada data barang.');
        }

        $satuan->delete();

        return redirect()->back()->with('crud_success', 'Data satuan berhasil dihapus.');
    }
}
