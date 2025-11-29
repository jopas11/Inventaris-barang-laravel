@extends('layouts.app')

@section('content')
    <div x-data="{ isOpen: false }" class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 flex items-center space-x-2 text-gray-800">
            <i class="fa-solid fa-house text-purple-600"></i>
            <span>Dashboard</span>
        </h1>

        {{-- Pengecekan Tenant --}}
        @if ($tenants->isEmpty())
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
                <p class="font-bold">Belum Terdaftar di Tenant</p>
                <p>Anda belum terdaftar dalam tenant mana pun. Silakan hubungi pengelola perusahaan anda untuk didaftarkan.
                </p>
            </div>
        @endif

        {{-- Jika Ada Tenant --}}
        @if (!$tenants->isEmpty())
            {{-- Statistik --}}
            <div class="overflow-x-auto">
                <div class="flex space-x-4 p-2">
                    @foreach ($tenants as $tenant)
                        <div
                            class="bg-white p-6 rounded-lg shadow-md flex items-center border-l-8 border-blue-500 min-w-[295px]">
                            <div class="text-blue-500 text-4xl mr-4">
                                <i class="fa-solid fa-building"></i>
                            </div>
                            <div>
                                <p class="text-gray-700 font-bold">{{ $tenant->nama_perusahaan }}</p>
                                <p class="text-sm text-gray-500"><i class="fa-solid fa-user"></i> {{ $tenant->nama }}</p>
                                <p class="text-sm text-gray-500"><i class="fa-solid fa-envelope"></i> {{ $tenant->email }}
                                </p>
                            </div>
                        </div>
                    @endforeach

                    <div
                        class="bg-white p-6 rounded-lg shadow-md flex items-center border-l-8 border-blue-500 min-w-[250px]">
                        <div class="text-blue-500 text-4xl mr-4">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <div>
                            <p class="text-gray-600">Data Barang</p>
                            <p class="text-2xl font-bold">{{ $jumlahBarang }}</p>
                        </div>
                    </div>

                    <div
                        class="bg-white p-6 rounded-lg shadow-md flex items-center border-l-8 border-green-500 min-w-[250px]">
                        <div class="text-green-500 text-4xl mr-4">
                            <i class="fa-solid fa-arrow-down"></i>
                        </div>
                        <div>
                            <p class="text-gray-600">Data Barang Masuk</p>
                            <p class="text-2xl font-bold">{{ $jumlahBarangMasuk }}</p>
                        </div>
                    </div>

                    <div
                        class="bg-white p-6 rounded-lg shadow-md flex items-center border-l-8 border-red-500 min-w-[250px]">
                        <div class="text-red-500 text-4xl mr-4">
                            <i class="fa-solid fa-arrow-up"></i>
                        </div>
                        <div>
                            <p class="text-gray-600">Data Barang Keluar</p>
                            <p class="text-2xl font-bold">{{ $jumlahBarangKeluar }}</p>
                        </div>
                    </div>

                    <div
                        class="bg-white p-6 rounded-lg shadow-md flex items-center border-l-8 border-purple-500 min-w-[250px]">
                        <div class="text-purple-500 text-4xl mr-4">
                            <i class="fa-solid fa-ruler"></i>
                        </div>
                        <div>
                            <p class="text-gray-600">Data Satuan</p>
                            <p class="text-2xl font-bold">{{ $jumlahSatuan }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Data Barang --}}
            <div class="bg-gray-100 py-6">
                <div class="bg-white shadow-md rounded-lg p-6 mx-auto">
                    <div class="flex items-center space-x-2 mb-4">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-lg font-semibold text-gray-700">Data Barang</span>
                    </div>

                    <table id="tabelStok" class="display w-full text-sm text-gray-700">
                        <thead class="bg-gray-100">
                            <tr>
                                <th>No.</th>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Jenis Barang</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($databarangs as $index => $barang)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $barang->id_barang }}</td>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->jenisbarang->jenis_barang }}</td>
                                    <td data-search="{{ $barang->stok }}" data-order="{{ $barang->stok }}">
                                        <span class="bg-orange-300 text-white px-3 py-1 rounded-full text-xs">
                                            {{ $barang->stok }}
                                        </span>
                                    </td>
                                    <td>{{ $barang->satuan->nama }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

            @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
                <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
                <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

                <script>
                    $(document).ready(function() {
                        $('#tabelStok').DataTable({
                            language: {
                                search: "Cari:",
                                lengthMenu: "Tampilkan _MENU_ entri",
                                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                                paginate: {
                                    first: "Awal",
                                    last: "Akhir",
                                    next: "Berikutnya",
                                    previous: "Sebelumnya"
                                },
                                zeroRecords: "Tidak ada data ditemukan",
                                emptyTable: "Tidak ada data tersedia"
                            }
                        });
                    });
                </script>
            @endpush
        @endif
    </div>
@endsection
