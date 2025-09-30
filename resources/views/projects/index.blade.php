@extends('layouts.app')

@section('title', 'Manajemen Proyek')

@section('content')
    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-0">Data Proyek</h1>
            <a href="{{ route('projects.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-1.5 px-4 rounded-md shadow-sm text-sm transition duration-300 ease-in-out">
                Tambah Proyek
            </a>
        </div>

        {{-- Pesan Sukses --}}
        @if (session('success'))
            <div
                class="bg-green-100 dark:bg-green-950 border border-green-400 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg relative mb-6">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Tampilan saat tidak ada data --}}
        @if ($proyek->isEmpty())
            <div class="flex items-center justify-center h-64 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                <div class="text-center text-gray-500 dark:text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="font-semibold text-lg">Belum ada data proyek yang tersedia.</p>
                </div>
            </div>
        @else
            {{-- Tabel Data Proyek --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden p-4">
                <div class="flex justify-center gap-2 mb-4">
                    <button class="status-filter-btn btn btn-sm btn-outline" data-status="">Semua</button>
                    <button class="status-filter-btn btn btn-sm btn-gray" data-status="process">Process</button>
                    <button class="status-filter-btn btn btn-sm btn-yellow" data-status="progress">Progress</button>
                    <button class="status-filter-btn btn btn-sm btn-green" data-status="completed">Completed</button>
                </div>

                <div class="flex justify-between mb-2 items-center">
                    <input type="text" id="proyekSearch" placeholder="Cari proyek..."
                        class="input input-sm input-bordered w-64">
                    <div id="proyekInfo" class="text-sm"></div>
                </div>

                <div class="overflow-x-auto">
                    <table id="proyekTable" class="table table-zebra table-compact w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th>No</th>
                                <th>Proyek</th>
                                <th>Klien</th>
                                <th>Nilai</th>
                                <th>Estimasi HPP</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proyek as $index => $item)
                                <tr class="transition-all hover:scale-102 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_proyek }}</td>
                                    <td>{{ $item->nama_klien }}</td>
                                    <td>Rp {{ number_format($item->nilai_proyek, 0, ',', '.') }}</td>
                                    <td>{{ $item->estimasi_hpp !== null ? number_format($item->estimasi_hpp, 0, ',', '.') . ' %' : '-' }}
                                    </td>
                                    <td class="px-2 py-1 text-center">
                                        <span
                                            class="px-1.5 py-0.5 text-[10px] font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                            {{ $item->tipe_proyek }}
                                        </span>
                                    </td>
                                    @php
                                        $statusColors = [
                                            'process' =>
                                                'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100',
                                            'progress' =>
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                            'completed' =>
                                                'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                            'canceled' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                                        ];
                                        $colorClass =
                                            $statusColors[$item->status_proyek] ?? 'bg-gray-200 text-gray-700';
                                    @endphp
                                    <td class="px-2 py-1 text-center">
                                        <span
                                            class="px-1.5 py-0.5 text-[10px] font-medium rounded-full {{ $colorClass }}">
                                            {{ ucfirst($item->status_proyek ?: '-') }}
                                        </span>
                                    </td>

                                    <td class="px-2 py-1 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            {{-- Tombol Detail (selalu tampil) --}}
                                            <a href="{{ route('projects.show', $item->id_proyek) }}" title="Lihat Detail"
                                                class="inline-flex items-center justify-center w-7 h-7 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            {{-- Tombol lain kecuali untuk kepala operasional --}}
                                            @if (Auth::user()->role !== 'kepala operational')
                                                {{-- Tombol Edit --}}
                                                @if ($item->status_proyek === 'process')
                                                    <a href="{{ route('projects.edit', $item->id_proyek) }}"
                                                        title="Edit Proyek"
                                                        class="inline-flex items-center justify-center w-7 h-7 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg shadow">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11 16H7v-4l2-2z" />
                                                        </svg>
                                                    </a>
                                                @endif

                                                {{-- Tombol Addendum --}}
                                                @if ($item->status_proyek === 'progress')
                                                    <button type="button"
                                                        onclick="openAddendumModal({{ $item->id_proyek }}, '{{ $item->nama_proyek }}')"
                                                        title="Tambah Addendum"
                                                        class="inline-flex items-center justify-center w-7 h-7 bg-orange-500 hover:bg-orange-600 text-white rounded-lg shadow transition duration-200 ease-in-out">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M7 2h8l5 5v13a2 2 0 01-2 2H7a2 2 0 01-2-2V4a2 2 0 012-2z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M12 11v6m-3-3h6" />
                                                        </svg>
                                                    </button>
                                                @endif

                                                {{-- Tombol Hapus --}}
                                                <form action="{{ route('projects.destroy', $item->id_proyek) }}"
                                                    method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" title="Hapus Proyek"
                                                        class="delete-btn inline-flex items-center justify-center w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>




                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <ul id="proyekPagination" class="flex justify-center mt-2 gap-1"></ul>
            </div>



        @endif
    </div>

    <!-- Modal (updated) -->
    <div id="addendumModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div id="addendumOverlay" class="absolute inset-0 bg-black/50 transition-opacity duration-300 ease-out opacity-0"
            onclick="closeAddendumModal()"></div>

        <div id="addendumModalContent"
            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl max-h-[90vh] bg-white dark:bg-gray-800 shadow-xl rounded-lg transition-all duration-300 ease-out scale-95 opacity-0">
            <div class="flex flex-col h-full max-h-[90vh]">
                {{-- Header Modal --}}
                <div
                    class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        Addendum Proyek:
                        <span id="modalProyekName" class="text-indigo-600"></span>
                    </h2>
                    <button type="button" onclick="closeAddendumModal()"
                        class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Konten Modal --}}
                <div class="flex-grow overflow-y-auto p-6">
                    {{-- Loading State --}}
                    <div id="loadingAddendum" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Memuat data addendum...</p>
                    </div>

                    {{-- Container untuk Accordion atau Form --}}
                    <div id="addendumContent" class="hidden">
                        <div id="existingAddendums" class="space-y-3 mb-6"></div>

                        <div class="mb-4">
                            <button type="button" id="btnTambahAddendum"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition duration-300 ease-in-out">
                                <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Addendum Baru
                            </button>
                        </div>
                        <div id="formAddendum" class="hidden">
                            <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-md max-w-4xl mx-auto">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                    Form Addendum Baru
                                </h3>


                                <form id="addendumForm" method="POST" enctype="multipart/form-data" data-proyek-id="">
                                    @csrf
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">

                                        {{-- Nomor Addendum --}}
                                        <div>
                                            <label for="nomor_addendum"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nomor
                                                Addendum</label>
                                            <input type="text" id="nomor_addendum" name="nomor_addendum" required
                                                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm 
                               bg-white dark:bg-gray-900 text-gray-900 dark:text-white 
                               px-3 py-2 text-sm
                               placeholder-gray-500 dark:placeholder-gray-400 
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        </div>

                                        {{-- Tanggal Addendum --}}
                                        <div>
                                            <label for="tanggal_addendum"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal
                                                Addendum</label>
                                            <input type="date" id="tanggal_addendum" name="tanggal_addendum" required
                                                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm 
                               bg-white dark:bg-gray-900 text-gray-900 dark:text-white 
                               px-3 py-2 text-sm
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        </div>

                                        {{-- Nilai Tambahan --}}
                                        <div>
                                            <label for="nilai_proyek_addendum"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nilai
                                                Tambahan</label>
                                            <input type="text" id="nilai_proyek_addendum" placeholder="Rp "
                                                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm 
                               bg-white dark:bg-gray-900 text-gray-900 dark:text-white 
                               px-3 py-2 text-sm
                               placeholder-gray-500 dark:placeholder-gray-400 
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        </div>

                                        {{-- Estimasi HPP Tambahan --}}
                                        <div>
                                            <label for="estimasi_hpp_addendum"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Estimasi
                                                HPP Tambahan (%)</label>
                                            <input type="text" id="estimasi_hpp_addendum" placeholder="0"
                                                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm 
                               bg-white dark:bg-gray-900 text-gray-900 dark:text-white 
                               px-3 py-2 text-sm
                               placeholder-gray-500 dark:placeholder-gray-400 
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        </div>

                                        {{-- Tambahan Termin --}}
                                        <div>
                                            <label for="tambahan_termin_addendum"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tambahan
                                                Termin</label>
                                            <input type="number" id="tambahan_termin_addendum"
                                                name="tambahan_termin_addendum"
                                                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm 
                               bg-white dark:bg-gray-900 text-gray-900 dark:text-white 
                               px-3 py-2 text-sm
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        </div>

                                        {{-- Tambahan Durasi --}}
                                        <div>
                                            <label for="durasi_addendum"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tambahan
                                                Durasi (bulan)</label>
                                            <input type="number" id="durasi_addendum" name="durasi_addendum"
                                                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm 
                               bg-white dark:bg-gray-900 text-gray-900 dark:text-white 
                               px-3 py-2 text-sm
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        </div>

                                        {{-- Container Termin Dinamis --}}
                                        <div id="terminContainer" class="sm:col-span-2"></div>

                                        {{-- Deskripsi Perubahan --}}
                                        <div class="sm:col-span-2">
                                            <label for="deskripsi_perubahan"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Deskripsi
                                                Perubahan</label>
                                            <textarea id="deskripsi_perubahan" name="deskripsi_perubahan" rows="3"
                                                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm 
                               bg-white dark:bg-gray-900 text-gray-900 dark:text-white 
                               px-3 py-2 text-sm
                               placeholder-gray-500 dark:placeholder-gray-400 
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"></textarea>
                                        </div>

                                        {{-- Lampiran --}}
                                        <div class="sm:col-span-2">
                                            <label for="attachment_file"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Lampiran</label>
                                            <input type="file" id="attachment_file" name="attachment_file[]" multiple
                                                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm 
                               text-gray-700 dark:text-gray-200 px-3 py-2 text-sm
                               file:mr-3 file:py-1.5 file:px-3 
                               file:rounded-md file:border-0 
                               file:text-sm file:font-semibold 
                               file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100
                               dark:file:bg-indigo-900 dark:file:text-indigo-200 dark:hover:file:bg-indigo-800">
                                        </div>
                                    </div>

                                    {{-- Tombol Form --}}
                                    <div class="flex justify-end mt-6 space-x-2">
                                        <button type="button" onclick="cancelAddendumForm()"
                                            class="px-4 py-2 rounded-md bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium transition">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 text-sm font-medium transition">
                                            Simpan Addendum
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>

                {{-- Footer Modal --}}
                <div class="flex justify-end p-6 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
                    <button type="button" onclick="closeAddendumModal()"
                        class="px-6 py-2 rounded-lg bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 font-medium transition duration-150 ease-in-out">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentProyekId = null;

        function openAddendumModal(id, nama) {
            currentProyekId = id;
            const modal = document.getElementById('addendumModal');
            const overlay = document.getElementById('addendumOverlay');
            const content = document.getElementById('addendumModalContent');

            document.getElementById('modalProyekName').innerText = nama || '';
            document.getElementById('addendumForm').setAttribute('data-proyek-id', id);

            // reset / state
            document.getElementById('loadingAddendum').classList.remove('hidden');
            document.getElementById('addendumContent').classList.add('hidden');
            document.getElementById('formAddendum').classList.add('hidden');
            document.getElementById('existingAddendums').innerHTML = '';

            // show modal (with animation classes)
            modal.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);

            // load data
            loadAddendumData(id);
        }

        function closeAddendumModal() {
            const modal = document.getElementById('addendumModal');
            const overlay = document.getElementById('addendumOverlay');
            const content = document.getElementById('addendumModalContent');

            overlay.classList.add('opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                currentProyekId = null;
            }, 300);
        }

        function loadAddendumData(proyekId) {
            const container = document.getElementById('existingAddendums');
            const loadingDiv = document.getElementById('loadingAddendum');
            const contentDiv = document.getElementById('addendumContent');
            const btnTambah = document.getElementById('btnTambahAddendum');

            // Clear and reset
            container.innerHTML = '';
            loadingDiv.classList.remove('hidden');
            contentDiv.classList.add('hidden');
            btnTambah.classList.add('hidden');

            fetch(`/projects/${proyekId}/addendums`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Normalize data
                    const addendums = Array.isArray(data) ? data : (data.addendums || data.data || []);

                    loadingDiv.classList.add('hidden');
                    contentDiv.classList.remove('hidden');

                    if (addendums.length > 0) {
                        renderAddendumAccordion(addendums);
                        btnTambah.classList.remove('hidden');
                    } else {
                        btnTambah.classList.add('hidden');
                        showAddendumForm();
                    }

                    // Set form action
                    document.getElementById('addendumForm').action = `/projects/${proyekId}/addendums`;
                })
                .catch(err => {
                    console.error('Error loading addendum data:', err);

                    loadingDiv.classList.add('hidden');
                    contentDiv.classList.remove('hidden');
                    btnTambah.classList.add('hidden');
                    showAddendumForm();

                    document.getElementById('addendumForm').action = `/projects/${proyekId}/addendums`;
                });
        }

        function renderAddendumAccordion(addendums) {
            const container = document.getElementById('existingAddendums');
            container.innerHTML = '';

            addendums.forEach((addendum, index) => {
                // Handle attachments - support multiple formats
                let attachmentsHtml = '';
                try {
                    let attachments = [];

                    // Parse attachments dari berbagai format
                    if (addendum.attachments) {
                        if (Array.isArray(addendum.attachments)) {
                            attachments = addendum.attachments;
                        } else if (typeof addendum.attachments === 'string') {
                            attachments = JSON.parse(addendum.attachments);
                        }
                    }

                    // Juga cek field attachment_file jika ada
                    if (addendum.attachment_file) {
                        if (Array.isArray(addendum.attachment_file)) {
                            attachments = [...attachments, ...addendum.attachment_file];
                        } else if (typeof addendum.attachment_file === 'string') {
                            try {
                                const parsedFiles = JSON.parse(addendum.attachment_file);
                                if (Array.isArray(parsedFiles)) {
                                    attachments = [...attachments, ...parsedFiles];
                                } else {
                                    attachments.push(parsedFiles);
                                }
                            } catch {
                                // Jika bukan JSON, anggap sebagai single file path
                                attachments.push(addendum.attachment_file);
                            }
                        } else {
                            attachments.push(addendum.attachment_file);
                        }
                    }

                    // Generate HTML untuk attachments
                    if (attachments.length > 0) {
                        attachmentsHtml = `
                    <div class="mb-4">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a2 2 0 00-2.828-2.828z" />
                            </svg>
                            Lampiran File (${attachments.length})
                        </label>
                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                `;

                        attachments.forEach(att => {
                            const fileName = getFileName(att);
                            const filePath = getFilePath(att);
                            const fileSize = getFileSize(att);
                            const fileType = getFileType(fileName);

                            // Debug log untuk melihat path
                            console.log('Attachment data:', {
                                original: att,
                                fileName: fileName,
                                filePath: filePath,
                                fullUrl: `/storage/${filePath}`
                            });

                            attachmentsHtml += `
                        <a href="/storage/${filePath}" target="_blank" 
                           class="group flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200">
                            <div class="flex-shrink-0 mr-3">
                                ${getFileIcon(fileType)}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate group-hover:text-blue-600 dark:group-hover:text-blue-400">
                                    ${fileName}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    ${fileType.toUpperCase()}${fileSize ? ` • ${fileSize}` : ''}
                                </p>
                            </div>
                            <div class="flex-shrink-0 ml-2">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </div>
                        </a>
                    `;
                        });

                        attachmentsHtml += `
                        </div>
                    </div>
                `;
                    }
                } catch (e) {
                    console.warn('Error parsing attachments:', e);
                    attachmentsHtml = '';
                }

                const item = `
            <div class="border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm">
                <div class="accordion-header bg-gray-50 dark:bg-gray-700 p-4 cursor-pointer flex justify-between items-center hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200"
                     onclick="toggleAccordion(${index})">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                           
                            <div>
                                <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Addendum #${addendum.nomor_addendum || (index + 1)}</span>
                                <div class="text-xs text-gray-500 dark:text-gray-400">${formatDate(addendum.tanggal_addendum)}</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 px-2 py-1 rounded-full font-medium">
                                +Rp ${numberFormat(addendum.nilai_proyek_addendum || 0)}
                            </span>
                            ${getAttachmentBadge(addendum)}
                        </div>
                    </div>
                    <svg class="accordion-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <div class="accordion-content hidden border-t border-gray-200 dark:border-gray-600">
                    <div class="p-4 bg-white dark:bg-gray-800">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Nomor Addendum</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${addendum.nomor_addendum || '-'}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tanggal</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${formatDate(addendum.tanggal_addendum)}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Nilai Tambahan</label>
                                <p class="mt-1 text-sm font-semibold text-green-600 dark:text-green-400">Rp ${numberFormat(addendum.nilai_proyek_addendum || 0)}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">HPP Tambahan</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${addendum.estimasi_hpp_addendum ? addendum.estimasi_hpp_addendum + '%' : '-'}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Termin Tambahan</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${addendum.tambahan_termin_addendum || '-'}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Durasi Tambahan</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${addendum.durasi_addendum ? addendum.durasi_addendum + ' bulan' : '-'}</p>
                            </div>
                        </div>

                        ${addendum.deskripsi_perubahan ? `
                                               <div class="mb-4">
                                                   <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Deskripsi Perubahan</label>
                                                   <div class="mt-1 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                                                       ${addendum.deskripsi_perubahan}
                                                   </div>
                                               </div>
                                           ` : ''}

                        ${attachmentsHtml}

                        <div class="flex justify-end space-x-2 pt-4 border-t border-gray-200 dark:border-gray-600">
                          
                            <button type="button" onclick="deleteAddendum(${addendum.id_addendum})" 
                                    class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md font-medium transition-colors duration-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
                container.insertAdjacentHTML('beforeend', item);
            });
        }




        // Helper functions untuk attachment handling
        function getFileName(attachment) {
            if (typeof attachment === 'string') {
                return attachment.split('/').pop() || attachment;
            }
            return attachment.original_name || attachment.name || attachment.file_name || attachment.filename || 'File';
        }

        function getFilePath(attachment) {
            if (typeof attachment === 'string') {
                // Jika sudah full path, gunakan langsung
                if (attachment.includes('uploads/proyek/')) {
                    return attachment;
                }
                // Jika hanya nama file, tambahkan path lengkap
                return `uploads/proyek/pr_${currentProyekId}/addendum/${attachment}`;
            }

            let path = attachment.file_path || attachment.path || attachment.url || '';

            // Jika path tidak lengkap, tambahkan prefix
            if (path && !path.includes('uploads/proyek/')) {
                path = `uploads/proyek/pr_${currentProyekId}/addendum/${path}`;
            }

            return path;
        }

        function getFileSize(attachment) {
            if (typeof attachment === 'object' && attachment.size) {
                const bytes = parseInt(attachment.size);
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1048576) return Math.round(bytes / 1024) + ' KB';
                return Math.round(bytes / 1048576) + ' MB';
            }
            return '';
        }

        function getFileType(fileName) {
            const ext = fileName.split('.').pop().toLowerCase();
            const types = {
                pdf: 'pdf',
                doc: 'doc',
                docx: 'doc',
                xls: 'excel',
                xlsx: 'excel',
                ppt: 'ppt',
                pptx: 'ppt',
                jpg: 'image',
                jpeg: 'image',
                png: 'image',
                gif: 'image',
                txt: 'text',
                zip: 'archive',
                rar: 'archive'
            };
            return types[ext] || 'file';
        }

        function getFileIcon(fileType) {
            const icons = {
                pdf: `<div class="w-8 h-8 bg-red-100 dark:bg-red-800 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-red-600 dark:text-red-300" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 18h12V6l-4-4H4v16zm8-14l2 2h-2V4z"/>
                </svg>
              </div>`,
                doc: `<div class="w-8 h-8 bg-blue-100 dark:bg-blue-800 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 18h12V6l-4-4H4v16zm8-14l2 2h-2V4z"/>
                </svg>
              </div>`,
                excel: `<div class="w-8 h-8 bg-green-100 dark:bg-green-800 rounded-lg flex items-center justify-center">
                  <svg class="w-4 h-4 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M4 18h12V6l-4-4H4v16zm8-14l2 2h-2V4z"/>
                  </svg>
                </div>`,
                image: `<div class="w-8 h-8 bg-purple-100 dark:bg-purple-800 rounded-lg flex items-center justify-center">
                  <svg class="w-4 h-4 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>`,
                archive: `<div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-800 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                  </div>`,
                default: `<div class="w-8 h-8 bg-gray-100 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                  </div>`
            };
            return icons[fileType] || icons.default;
        }

        function getAttachmentBadge(addendum) {
            let totalAttachments = 0;

            try {
                // Count attachments
                if (addendum.attachments) {
                    if (Array.isArray(addendum.attachments)) {
                        totalAttachments += addendum.attachments.length;
                    } else if (typeof addendum.attachments === 'string') {
                        const parsed = JSON.parse(addendum.attachments);
                        totalAttachments += Array.isArray(parsed) ? parsed.length : 1;
                    }
                }

                // Count attachment_file
                if (addendum.attachment_file) {
                    if (Array.isArray(addendum.attachment_file)) {
                        totalAttachments += addendum.attachment_file.length;
                    } else if (typeof addendum.attachment_file === 'string') {
                        try {
                            const parsed = JSON.parse(addendum.attachment_file);
                            totalAttachments += Array.isArray(parsed) ? parsed.length : 1;
                        } catch {
                            totalAttachments += 1;
                        }
                    } else {
                        totalAttachments += 1;
                    }
                }
            } catch (e) {
                console.warn('Error counting attachments:', e);
            }

            if (totalAttachments > 0) {
                return `
            <span class="text-xs bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 px-2 py-1 rounded-full font-medium flex items-center">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a2 2 0 00-2.828-2.828z" />
                </svg>
                ${totalAttachments} file${totalAttachments > 1 ? 's' : ''}
            </span>
        `;
            }
            return '';
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
            } catch {
                return dateString;
            }
        }


        function toggleAccordion(index) {
            let accordionItems = document.querySelectorAll('.accordion-content');
            let accordionIcons = document.querySelectorAll('.accordion-icon');

            accordionItems.forEach((item, i) => {
                if (i === index) {
                    item.classList.toggle('hidden');
                    accordionIcons[i].classList.toggle('rotate-180');
                } else {
                    item.classList.add('hidden');
                    accordionIcons[i].classList.remove('rotate-180');
                }
            });
        }

        function showAddendumForm() {
            document.getElementById('formAddendum').classList.remove('hidden');
            document.getElementById('tanggal_addendum').value = new Date().toISOString().split('T')[0];
        }

        function cancelAddendumForm() {
            document.getElementById('formAddendum').classList.add('hidden');
            document.getElementById('addendumForm').reset();

            let existingAddendums = document.getElementById('existingAddendums');
            if (existingAddendums.children.length > 0) {
                document.getElementById('btnTambahAddendum').classList.remove('hidden');
            }
        }

        function editAddendum(addendumId) {
            // arahkan ke halaman edit (pastikan route ada)
            // contoh route: GET /projects/{idProyek}/addendums/{idAddendum}/edit
            if (!currentProyekId) return;
            window.location.href = `/projects/${currentProyekId}/addendums/${addendumId}/edit`;
        }

        function deleteAddendum(addendumId) {
            if (!currentProyekId) return;

            Swal.fire({
                title: 'Hapus Addendum?',
                text: "Addendum ini akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Menghapus...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(`/projects/${currentProyekId}/addendums/${addendumId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success || data.status === 'success') {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Addendum telah dihapus.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                // Force refresh accordion dengan delay singkat untuk memastikan DOM siap
                                setTimeout(() => {
                                    refreshAddendumAccordion();
                                }, 100);

                            } else {
                                Swal.fire('Error!', data.message || 'Gagal menghapus addendum.', 'error');
                            }
                        })
                        .catch(err => {
                            console.error('Delete error:', err);
                            Swal.fire('Error!', 'Terjadi kesalahan saat menghapus addendum.', 'error');
                        });
                }
            });
        }

        function refreshAddendumAccordion() {
            if (!currentProyekId) return;

            // Clear existing content first
            const container = document.getElementById('existingAddendums');
            const loadingDiv = document.getElementById('loadingAddendum');
            const contentDiv = document.getElementById('addendumContent');
            const btnTambah = document.getElementById('btnTambahAddendum');

            // Reset state
            container.innerHTML = '';
            loadingDiv.classList.remove('hidden');
            contentDiv.classList.add('hidden');
            btnTambah.classList.add('hidden');

            // Load fresh data
            fetch(`/projects/${currentProyekId}/addendums`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Normalize data structure
                    const addendums = Array.isArray(data) ? data : (data.addendums || data.data || []);

                    // Hide loading, show content
                    loadingDiv.classList.add('hidden');
                    contentDiv.classList.remove('hidden');

                    if (addendums.length > 0) {
                        // Render accordion dengan data terbaru
                        renderAddendumAccordion(addendums);
                        btnTambah.classList.remove('hidden');
                    } else {
                        // Jika tidak ada addendum, show form
                        btnTambah.classList.add('hidden');
                        showAddendumForm();
                    }
                })
                .catch(err => {
                    console.error('Error refreshing addendum data:', err);

                    // Hide loading, show content even on error
                    loadingDiv.classList.add('hidden');
                    contentDiv.classList.remove('hidden');

                    // Show form as fallback
                    btnTambah.classList.add('hidden');
                    showAddendumForm();

                    // Optional: show error notification
                    Swal.fire('Warning!', 'Data addendum tidak dapat dimuat ulang. Refresh halaman jika diperlukan.',
                        'warning');
                });
        }


        function numberFormat(number) {
            return new Intl.NumberFormat('id-ID').format(Number(number || 0));
        }

        // single DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            // tombol tambah
            document.getElementById('btnTambahAddendum').addEventListener('click', function() {
                this.classList.add('hidden');
                showAddendumForm();
            });

            // buat hidden inputs untuk dikirim ke server
            const form = document.getElementById('addendumForm');

            const hiddenNilai = document.createElement('input');
            hiddenNilai.type = 'hidden';
            hiddenNilai.name = 'nilai_proyek_addendum';
            form.appendChild(hiddenNilai);

            const hiddenHpp = document.createElement('input');
            hiddenHpp.type = 'hidden';
            hiddenHpp.name = 'estimasi_hpp_addendum';
            form.appendChild(hiddenHpp);

            // tampilan inputs
            const nilaiInput = document.getElementById('nilai_proyek_addendum');
            const hppInput = document.getElementById('estimasi_hpp_addendum');

            function formatRupiahRaw(raw) {
                if (!raw) return '';
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(raw));
            }

            nilaiInput.addEventListener('input', function() {
                const raw = this.value.replace(/[^0-9]/g, '');
                hiddenNilai.value = raw;
                this.value = raw ? formatRupiahRaw(raw) : '';
            });

            hppInput.addEventListener('input', function() {
                const raw = this.value.replace(/[^0-9]/g, '');
                hiddenHpp.value = raw;
                this.value = raw ? raw + '%' : '';
            });

            // submit form via fetch
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!currentProyekId) {
                    Swal.fire('Error!', 'ID Proyek tidak ditemukan. Coba buka ulang modal.', 'error');
                    return;
                }

                const action = this.action || `/projects/${currentProyekId}/addendums`;
                const fd = new FormData(this);

                fd.set('nilai_proyek_addendum', hiddenNilai.value || 0);
                fd.set('estimasi_hpp_addendum', hiddenHpp.value || 0);

                fetch(action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: fd
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success || data.status === 'success' || data.message?.includes(
                                'berhasil')) {
                            Swal.fire('Berhasil!', 'Addendum telah ditambahkan.', 'success');
                            form.reset();
                            cancelAddendumForm();
                            loadAddendumData(currentProyekId);
                        } else {
                            Swal.fire('Error!', data.message || 'Gagal menambahkan addendum.', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan addendum.', 'error');
                    });
            });
            // === Tambahan: handle field termin dinamis ===
            const terminInput = document.getElementById('tambahan_termin_addendum');
            const terminContainer = document.getElementById('terminContainer');
            terminInput.addEventListener('input', function() {
                const jumlahTermin = parseInt(this.value) || 0;
                terminContainer.innerHTML = ''; // reset dulu

                for (let i = 1; i <= jumlahTermin; i++) {
                    const group = document.createElement('div');
                    group.className = "p-4 border border-gray-200 dark:border-gray-600 rounded-lg";

                    group.innerHTML = `
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Termin ${i}</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    {{-- Tanggal Jatuh Tempo --}}
    <div>
        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400">
            Tanggal Jatuh Tempo
        </label>
        <input type="date" 
               name="termins[${i}][tanggal_jatuh_tempo]" 
               class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm 
                      bg-white dark:bg-gray-900 text-gray-900 dark:text-white 
                      px-3 py-2 text-sm
                      focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
    </div>

    {{-- Jumlah --}}
    <div>
        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400">
            Jumlah
        </label>
        <input type="text" 
               name="termins[${i}][jumlah_display]" 
               data-real-input="termin-${i}" 
               placeholder="Rp." 
               class="jumlahInput mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm 
                      bg-white dark:bg-gray-900 text-gray-900 dark:text-white 
                      px-3 py-2 text-sm
                      placeholder-gray-500 dark:placeholder-gray-400
                      focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
        <input type="hidden" name="termins[${i}][jumlah]" id="termin-${i}">
    </div>

    {{-- Keterangan --}}
    <div class="sm:col-span-2">
        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400">
            Keterangan
        </label>
        <input type="text" 
               name="termins[${i}][keterangan]" 
               class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm 
                      bg-white dark:bg-gray-900 text-gray-900 dark:text-white 
                      px-3 py-2 text-sm
                      focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
    </div>
</div>

        `;
                    terminContainer.appendChild(group);
                }

                // tambahin event listener buat format rupiah
                document.querySelectorAll('.jumlahInput').forEach(input => {
                    input.addEventListener('input', function(e) {
                        let raw = this.value.replace(/[^0-9]/g, ''); // ambil angka aja
                        let formatted = new Intl.NumberFormat('id-ID').format(raw);
                        this.value = raw ? `Rp. ${formatted}` : '';

                        // simpan angka asli ke hidden input
                        let targetHidden = document.getElementById(this.dataset.realInput);
                        if (targetHidden) targetHidden.value = raw;
                    });
                });
            });


        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            var table = $('#proyekTable').DataTable({
                processing: false,
                serverSide: false,
                responsive: true,
               
                stripeClasses: ['table-zebra', 'table-zebra'],
                pageLength: 10,
                lengthChange: false,
                dom: 't',
                drawCallback: function() {
                    const dt = this.api();
                    const info = dt.page.info();

                    const pagination = $('#proyekPagination');
                    pagination.empty();
                    const totalPages = info.pages;
                    const currentPage = info.page;
                    const maxVisible = 5; // jumlah halaman yang ditampilkan

                    const pageBtn = (label, page, disabled = false, active = false) =>
                        `<li><button class="btn btn-sm ${active ? 'btn-active' : 'btn-outline'} ${disabled ? 'btn-disabled' : ''}" data-page="${page}">${label}</button></li>`;

                    // Prev
                    pagination.append(pageBtn('Prev', currentPage - 1, currentPage === 0));

                    let start = Math.max(0, currentPage - Math.floor(maxVisible / 2));
                    let end = start + maxVisible - 1;

                    if (end >= totalPages) {
                        end = totalPages - 1;
                        start = Math.max(0, end - maxVisible + 1);
                    }

                    // Halaman awal + ...
                    if (start > 0) {
                        pagination.append(pageBtn(1, 0, false, currentPage === 0));
                        if (start > 1) pagination.append(
                            `<li><span class="btn btn-sm btn-disabled">...</span></li>`);
                    }

                    // Halaman tengah
                    for (let i = start; i <= end; i++) {
                        pagination.append(pageBtn(i + 1, i, false, i === currentPage));
                    }

                    // Halaman akhir + ...
                    if (end < totalPages - 1) {
                        if (end < totalPages - 2) pagination.append(
                            `<li><span class="btn btn-sm btn-disabled">...</span></li>`);
                        pagination.append(pageBtn(totalPages, totalPages - 1, false, currentPage ===
                            totalPages - 1));
                    }

                    // Next
                    pagination.append(pageBtn('Next', currentPage + 1, currentPage === totalPages - 1));

                    $('#proyekPagination button').off('click').on('click', function() {
                        const page = $(this).data('page');
                        if (page >= 0 && page < totalPages) dt.page(page).draw('page');
                    });
                }

            });

            // Debug: Log struktur data untuk memahami masalah
            console.log('=== DEBUG TABLE DATA ===');
            table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                const data = this.data();
                console.log(`Row ${rowIdx}:`, data);
                console.log(`Status column (index 7):`, data[7]);
            });

            // Filter status dengan custom search function
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                // Pastikan ini hanya untuk table proyekTable
                if (settings.nTable.id !== 'proyekTable') {
                    return true;
                }

                const activeFilter = $('.status-filter-btn.btn-active').data('status');
                console.log('Active filter:', activeFilter);

                // Jika filter "Semua" (tidak ada status) atau tidak ada filter aktif
                if (!activeFilter) {
                    return true;
                }

                // Ambil teks dari kolom status (kolom index 7)
                const statusText = data[7] || '';
                console.log('Status text from data[7]:', statusText);

                // Coba berbagai cara extract status
                let actualStatus = '';

                // Method 1: Extract dari HTML menggunakan jQuery
                const $temp = $('<div>').html(statusText);
                const spanText = $temp.find('span').text().trim();
                console.log('Extracted via jQuery:', spanText);

                // Method 2: Extract menggunakan regex
                const regexMatch = statusText.match(/>(.*?)</);
                const regexStatus = regexMatch ? regexMatch[1].trim() : '';
                console.log('Extracted via regex:', regexStatus);

                // Method 3: Strip all HTML tags
                const stripHtml = statusText.replace(/<[^>]*>/g, '').trim();
                console.log('Stripped HTML:', stripHtml);

                // Gunakan yang paling berhasil
                actualStatus = spanText || regexStatus || stripHtml;
                console.log('Final actual status:', actualStatus);
                console.log('Comparing:', actualStatus.toLowerCase(), 'vs', activeFilter.toLowerCase());

                const match = actualStatus.toLowerCase() === activeFilter.toLowerCase();
                console.log('Match result:', match);
                console.log('---');

                return match;
            });

            // Filter status via tombol
            $('.status-filter-btn').on('click', function() {
                const status = $(this).data('status');

                // Update tampilan tombol
                $('.status-filter-btn').removeClass('btn-active').addClass('btn-outline');
                $(this).removeClass('btn-outline').addClass('btn-active');

                // Clear existing search and trigger redraw
                table.search('').draw();
            });

            // Search input
            $('#proyekSearch').on('keyup', function() {
                // Clear status filter when searching
                $('.status-filter-btn').removeClass('btn-active').addClass('btn-outline');
                $('.status-filter-btn[data-status=""]').addClass('btn-active').removeClass('btn-outline');

                table.search(this.value).draw();
            });

            // Update info display
            table.on('draw', function() {
                const info = table.page.info();
                $('#proyekInfo').text(
                    `Menampilkan ${info.start + 1}-${info.end} dari ${info.recordsDisplay} data`
                );
            });

            // Set default filter ke "Semua"
            $('.status-filter-btn[data-status=""]').addClass('btn-active').removeClass('btn-outline');

            // Initial draw untuk menampilkan info
            table.draw();

        });
    </script>


    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');

                Swal.fire({
                    title: 'Anda Yakin ingin menghapus Data?',
                    text: "Data proyek ini akan dihapus secara permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    width: '440px',
                    padding: '1em',
                    background: document.documentElement.classList.contains('dark') ?
                        '#1f2937' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ?
                        '#f3f4f6' : '#111827',
                    customClass: {
                        popup: 'rounded-lg shadow-md',
                        title: 'text-lg font-semibold',
                        htmlContainer: 'text-base',
                        confirmButton: 'text-base px-5 py-2.5 rounded-md',
                        cancelButton: 'text-base px-5 py-2.5 rounded-md'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>



@endsection
