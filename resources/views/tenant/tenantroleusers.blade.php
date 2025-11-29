@extends('layouts.app')

@section('content')
    <!-- DataTables & jQuery CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Select2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-semibold text-gray-800">
                <span>Pendaftaran User Tenant</span>
            </h1>
            <button onclick="openModalTambah()" class="mt-4 px-6 py-3 bg-green-500 text-white rounded hover:bg-green-700">
                <i class="fas fa-plus"></i> Tambah User
            </button>
        </div>
        <hr class="mb-4 border-gray-300">

        <div class="overflow-x-auto bg-white shadow-lg rounded-lg p-4">
            <table id="tabelUserTenant" class="display w-full text-sm text-gray-700 border border-gray-300 rounded-lg">
                <thead class="bg-purple-500 text-white">
                    <tr>
                        <th class="px-4 py-2">No.</th>
                        <th class="px-4 py-2">Nama Pengelola (Tenant)</th>
                        <th class="px-4 py-2">User</th>
                        <th class="px-4 py-2">Role</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tenantRoleUsers as $index => $tenantRoleUser)
                        <tr class="text-center hover:bg-gray-100 transition">
                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">{{ $tenantRoleUser->tenant->nama }}</td>
                            <td class="px-4 py-2">{{ $tenantRoleUser->role->user->nama }}</td>
                            <td class="px-4 py-2 capitalize">{{ $tenantRoleUser->role->role }}</td>
                            <td class="px-4 py-2">
                                <button
                                    onclick="openModalEdit({{ $tenantRoleUser->id }}, '{{ $tenantRoleUser->id_tenant }}', '{{ $tenantRoleUser->id_role }}')"
                                    class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-700">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('tenantroleusers.destroy', $tenantRoleUser->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-700"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal Tambah -->
        <div id="modal-tambah" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h3 class="text-xl font-semibold mb-4">Tambah Data</h3>
                <form id="form-tambah" method="POST" action="{{ route('tenantroleusers.store') }}">
                    @csrf
                    <label class="block mb-2">Tenant</label>
                    <select name="id_tenant" required class="w-full p-2 border border-gray-300 rounded">
                        @foreach ($tenants as $tenant)
                            <option value="{{ $tenant->id }}">{{ $tenant->nama }}</option>
                        @endforeach
                    </select>

                    <label class="block mt-3 mb-2">User</label>
                    <select name="id_role" required class="select-user w-full p-2 border border-gray-300 rounded">
                        <option value="">Pilih User</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->user->nama }} - {{ ucfirst($role->role) }}</option>
                        @endforeach
                    </select>

                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="button" onclick="closeModalTambah()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit -->
        <div id="modal-edit" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h3 class="text-xl font-semibold mb-4">Edit Data</h3>
                <form id="form-edit" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit-id">

                    <label class="block mb-2">Tenant</label>
                    <select name="id_tenant" id="edit-id-tenant" class="w-full p-2 border border-gray-300 rounded">
                        @foreach ($tenants as $tenant)
                            <option value="{{ $tenant->id }}">{{ $tenant->nama }}</option>
                        @endforeach
                    </select>

                    <label class="block mt-3 mb-2">User</label>
                    <select name="id_role" id="edit-id-role" class="select-user w-full p-2 border border-gray-300 rounded">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->user->nama }} - {{ ucfirst($role->role) }}</option>
                        @endforeach
                    </select>

                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="button" onclick="closeModalEdit()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModalTambah() {
            let modal = document.getElementById('modal-tambah');
            modal.classList.remove('opacity-0', 'pointer-events-none');

            // Inisialisasi Select2 di modal Tambah
            $('#modal-tambah .select-user').select2({
                dropdownParent: $('#modal-tambah'),
                width: '100%',
                placeholder: "Pilih User",
                allowClear: true
            });
        }

        function closeModalTambah() {
            let modal = document.getElementById('modal-tambah');
            modal.classList.add('opacity-0', 'pointer-events-none');
        }

        function openModalEdit(id, idTenant, idRole) {
            let modal = document.getElementById('modal-edit');
            modal.classList.remove('opacity-0', 'pointer-events-none');

            document.getElementById('form-edit').action = '/tenantroleusers/update/' + id;
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-id-tenant').value = idTenant;
            document.getElementById('edit-id-role').value = idRole;

            // Inisialisasi Select2 di modal Edit
            $('#modal-edit .select-user').select2({
                dropdownParent: $('#modal-edit'),
                width: '100%',
                placeholder: "Pilih User",
                allowClear: true
            });
        }

        function closeModalEdit() {
            let modal = document.getElementById('modal-edit');
            modal.classList.add('opacity-0', 'pointer-events-none');
        }

        // DataTables init
        $(document).ready(function() {
            $('#tabelUserTenant').DataTable({
                responsive: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari total _MAX_ entri)",
                    zeroRecords: "Tidak ditemukan data yang cocok",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });
    </script>
@endsection
