@extends('layouts.app')

@section('content')
    <!-- Tabel Tenant -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden mt-6">
        <div class="overflow-x-auto">
            <table class="w-full min-w-max border-collapse">
                <thead>
                    <tr class="bg-purple-700 text-white text-sm uppercase text-center">
                        <th class="p-4">No</th>
                        <th class="p-4 text-left">Nama (Pengelola)</th>
                        <th class="p-4 text-left">Perusahaan</th>
                        <th class="p-4">Kode</th>
                        <th class="p-4">Email (Pengelola)</th>
                        <th class="p-4">Telepon</th>
                        <th class="p-4 text-left">Alamat</th>

                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($tenants as $index => $tenant)
                        <tr class="hover:bg-gray-50 text-center">
                            <td class="p-4 font-semibold">{{ $index + 1 }}</td>
                            <td class="p-4 text-left whitespace-nowrap">
                                <i class="fa-solid fa-user text-purple-600 mr-2"></i> {{ $tenant->nama }}
                            </td>
                            <td class="p-4 text-left text-purple-600 font-medium">
                                <i class="fa-solid fa-building text-blue-500 mr-2"></i> {{ $tenant->nama_perusahaan }}
                            </td>
                            <td class="p-4 font-semibold">
                                <i class="fa-solid fa-key text-gray-600 mr-2"></i> {{ $tenant->kode }}
                            </td>
                            <td class="p-4 text-left">
                                <i class="fa-solid fa-envelope text-blue-600 mr-2"></i> {{ $tenant->email }}
                            </td>
                            <td class="p-4">
                                <i class="fa-solid fa-phone text-green-600"></i> {{ $tenant->telepon }}
                            </td>
                            <td class="p-4 text-left">
                                <i class="fa-solid fa-map-marker-alt text-red-600 mr-2"></i> {{ $tenant->alamat }}
                            </td>


                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-4 text-center text-gray-500">
                                <i class="fa-solid fa-folder-open mr-2"></i> Belum ada tenant yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
