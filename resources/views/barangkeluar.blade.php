@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-semibold text-gray-800">Daftar Barang Keluar</h1>
            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-blue-700 transition duration-200"
                onclick="toggleModal('add-modal')">Tambah Barang Keluar</button>
        </div>
        <hr class="mb-4 border-gray-300">

        <!-- Tabel Barang Keluar -->
        <div class="bg-white shadow-md rounded-lg p-6 mx-auto">
            <div class="flex items-center space-x-2 mb-4">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-lg font-semibold text-gray-700">Daftar Barang Keluar</span>
            </div>
            <table id="tabelBarangKeluar" class="display w-full text-sm text-gray-700">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-3 px-6 text-left">No.</th>
                        <th class="py-3 px-6 text-left">No Barang Keluar</th>
                        <th class="py-3 px-6 text-left">Tanggal</th>
                        <th class="py-3 px-6 text-left">Nama Barang</th>
                        <th class="py-3 px-6 text-left">Satuan</th>
                        <th class="py-3 px-6 text-left">Jumlah Keluar</th>
                        <th class="py-3 px-6 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
    @foreach ($barangkeluars as $index => $barangKeluar)
        <tr class="hover:bg-gray-100 transition duration-200">
            <td class="py-3 px-6">{{ $index + 1 }}</td>
            <td class="py-3 px-6">{{ $barangKeluar->no_barangkeluar }}</td>
            <td class="py-3 px-6">{{ $barangKeluar->tanggal }}</td>
            <td class="py-3 px-6">{{ $barangKeluar->dataBarang->nama_barang }}</td>
            <td class="py-3 px-6">{{ $barangKeluar->dataBarang->satuan->nama }}</td>
            <td class="py-3 px-6">{{ $barangKeluar->jumlah_keluar }}</td>
            <td class="py-3 px-6 flex space-x-4">
                <button class="text-yellow-500 hover:text-yellow-600"
                    onclick="toggleModal('edit-modal-{{ $barangKeluar->id }}')">Edit</button>

                <form action="{{ route('barangkeluar.destroy', $barangKeluar->id) }}" method="POST"
                    onsubmit="return confirm('Yakin hapus?')" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-600">Hapus</button>
                </form>
            </td>
        </tr>

        <!-- Modal Edit Barang Keluar -->
        <div id="edit-modal-{{ $barangKeluar->id }}"
            class="modal hidden fixed inset-0 z-50 bg-gray-600 bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-4 rounded-lg w-1/3 max-w-md">
                <form action="{{ route('barangkeluar.update', $barangKeluar->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">No Barang Keluar</label>
                        <input type="text" name="no_barangkeluar"
                            class="w-full p-2 border rounded bg-gray-100"
                            value="{{ $barangKeluar->no_barangkeluar }}" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                        <select name="id_databarang" id="id_databarang-edit-{{ $barangKeluar->id }}"
                            class="w-full p-2 border rounded" required>
                            @foreach ($databarangs as $dataBarang)
                                <option value="{{ $dataBarang->id }}" data-stok="{{ $dataBarang->stok }}"
                                    data-satuan="{{ $dataBarang->satuan->nama }}"
                                    {{ $dataBarang->id == $barangKeluar->id_databarang ? 'selected' : '' }}>
                                    {{ $dataBarang->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Satuan</label>
                        <input type="text" id="satuan-edit-{{ $barangKeluar->id }}"
                            class="w-full p-2 border rounded bg-gray-100"
                            value="{{ $barangKeluar->dataBarang->satuan->nama }}" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Stok Tersedia</label>
                        <input type="number" id="stok-edit-{{ $barangKeluar->id }}"
                            class="w-full p-2 border rounded bg-gray-100"
                            value="{{ $barangKeluar->dataBarang->stok }}" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <input type="date" name="tanggal" class="w-full p-2 border rounded"
                            value="{{ $barangKeluar->tanggal }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Jumlah Keluar</label>
                        <input type="number" name="jumlah_keluar"
                            id="jumlah_keluar-edit-{{ $barangKeluar->id }}"
                            class="w-full p-2 border rounded" min="1"
                            value="{{ $barangKeluar->jumlah_keluar }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Total Stok</label>
                        <input type="number" id="total_stok-edit-{{ $barangKeluar->id }}"
                            class="w-full p-2 border rounded bg-gray-100"
                            value="{{ $barangKeluar->dataBarang->stok + $barangKeluar->jumlah_keluar - $barangKeluar->jumlah_keluar }}"
                            readonly>
                    </div>

                    <div class="flex justify-between">
                        <button type="submit"
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">Update</button>
                        <button type="button"
                            class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600"
                            onclick="toggleModal('edit-modal-{{ $barangKeluar->id }}')">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</tbody>

            </table>
        </div>

        <!-- Modal Tambah Barang Keluar -->
        <div id="add-modal"
            class="modal hidden fixed inset-0 z-50 bg-gray-600 bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-4 rounded-lg w-1/3 max-w-md">
                <form action="{{ route('barangkeluar.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">No Barang Keluar</label>
                        <input type="text" name="no_barangkeluar" class="w-full p-2 border rounded bg-gray-100"
                            value="{{ $newId }}" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <input type="date" name="tanggal" class="w-full p-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                        <select name="id_databarang" id="id_databarang" class="w-full p-2 border rounded" required>
                            <option value="">Pilih Barang</option>
                            @foreach ($databarangs as $dataBarang)
                                <option value="{{ $dataBarang->id }}" data-stok="{{ $dataBarang->stok }}"
                                    data-satuan="{{ $dataBarang->satuan->nama }}">
                                    {{ $dataBarang->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Satuan</label>
                        <input type="text" id="satuan" class="w-full p-2 border rounded bg-gray-100" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Stok Tersedia</label>
                        <input type="number" id="stok" class="w-full p-2 border rounded bg-gray-100" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Jumlah Keluar</label>
                        <input type="number" name="jumlah_keluar" id="jumlah_keluar" class="w-full p-2 border rounded"
                            min="1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Total Stok</label>
                        <input type="number" id="total_stok" class="w-full p-2 border rounded bg-gray-100" readonly>
                    </div>

                    <div class="flex justify-between">
                        <button type="submit"
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">Simpan</button>
                        <button type="button" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600"
                            onclick="toggleModal('add-modal')">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <!-- jQuery & DataTables -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <script>
        $(document).ready(function() {
            $('#tabelBarangKeluar').DataTable({
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

            // Modal toggle dengan jQuery (opsional, jika digunakan)
            $('[data-modal-target]').on('click', function() {
                let modalId = $(this).data('modal-target');
                $('#' + modalId).toggleClass('hidden');
            });
        });

        // Modal toggle vanilla
        function toggleModal(id) {
            const modal = document.getElementById(id);
            modal.classList.toggle('hidden');
        }

        function updateStok(selectElement, stokInputId, satuanInputId = null) {
            const stok = selectElement.options[selectElement.selectedIndex].getAttribute('data-stok');
            const satuan = selectElement.options[selectElement.selectedIndex].getAttribute('data-satuan');
            document.getElementById(stokInputId).value = stok ?? '';
            if (satuanInputId && document.getElementById(satuanInputId)) {
                document.getElementById(satuanInputId).value = satuan ?? '';
            }
        }

        function updateTotalStok(stokInputId, jumlahInputId, totalInputId) {
            const stok = parseInt(document.getElementById(stokInputId).value) || 0;
            const jumlah = parseInt(document.getElementById(jumlahInputId).value) || 0;
            document.getElementById(totalInputId).value = Math.max(stok - jumlah, 0);
        }

        function validateJumlahKeluar(jumlahInputId, stokInputId) {
            const jumlahInput = document.getElementById(jumlahInputId);
            const stokInput = document.getElementById(stokInputId);

            jumlahInput.addEventListener('input', function () {
                const stok = parseInt(stokInput.value) || 0;
                let jumlah = parseInt(jumlahInput.value) || 0;

                if (jumlah > stok) {
                    alert("Jumlah keluar tidak boleh melebihi stok tersedia!");
                    jumlahInput.value = stok;
                } else if (jumlah < 0) {
                    alert("Jumlah keluar tidak boleh kurang dari 0!");
                    jumlahInput.value = 0;
                }

                updateTotalStok(stokInputId, jumlahInputId, jumlahInputId.replace('jumlah_keluar', 'total_stok'));
            });
        }

        // Tambah Barang
        const tambahSelect = document.getElementById('id_databarang');
        const tambahJumlah = document.getElementById('jumlah_keluar');
        if (tambahSelect && tambahJumlah) {
            tambahSelect.addEventListener('change', function () {
                updateStok(this, 'stok', 'satuan');
                updateTotalStok('stok', 'jumlah_keluar', 'total_stok');
            });
            validateJumlahKeluar('jumlah_keluar', 'stok');
        }

        // Barang Keluar (Edit)
        @foreach ($barangkeluars as $barangKeluar)
            const selectEdit{{ $barangKeluar->id }} = document.getElementById('id_databarang-edit-{{ $barangKeluar->id }}');
            const jumlahEdit{{ $barangKeluar->id }} = document.getElementById('jumlah_keluar-edit-{{ $barangKeluar->id }}');

            if (selectEdit{{ $barangKeluar->id }} && jumlahEdit{{ $barangKeluar->id }}) {
                selectEdit{{ $barangKeluar->id }}.addEventListener('change', function () {
                    updateStok(this, 'stok-edit-{{ $barangKeluar->id }}', 'satuan-edit-{{ $barangKeluar->id }}');
                    updateTotalStok('stok-edit-{{ $barangKeluar->id }}', 'jumlah_keluar-edit-{{ $barangKeluar->id }}', 'total_stok-edit-{{ $barangKeluar->id }}');
                });

                validateJumlahKeluar('jumlah_keluar-edit-{{ $barangKeluar->id }}', 'stok-edit-{{ $barangKeluar->id }}');

                updateStok(selectEdit{{ $barangKeluar->id }}, 'stok-edit-{{ $barangKeluar->id }}', 'satuan-edit-{{ $barangKeluar->id }}');
            }
        @endforeach
    </script>
@endpush

