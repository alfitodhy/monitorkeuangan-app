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
        @if ($data->isEmpty())
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
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="proyekTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-2 py-1 text-center font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                                    No</th>
                                <th
                                    class="px-2 py-1 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                                    Kode</th>
                                <th
                                    class="px-2 py-1 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                                    Proyek</th>
                                <th
                                    class="px-2 py-1 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                                    Klien</th>
                                <th
                                    class="px-2 py-1 text-right font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                                    Nilai</th>
                                <th
                                    class="px-2 py-1 text-right font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                                    Estimasi HPP</th>
                                <th
                                    class="px-2 py-1 text-center font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                                    Tipe</th>
                                <th
                                    class="px-2 py-1 text-center font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                                    Status</th>
                                <th
                                    class="px-2 py-1 text-center font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($data as $index => $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-75">
                                    <td class="px-2 py-1 text-center text-gray-700 dark:text-gray-200 font-medium">
                                        {{ $index + 1 }}</td>
                                    <td class="px-2 py-1 text-gray-900 dark:text-gray-100 font-medium">
                                        {{ $item->kode_proyek }}</td>
                                    <td class="px-2 py-1 text-gray-700 dark:text-gray-200">{{ $item->nama_proyek }}</td>
                                    <td class="px-2 py-1 text-gray-700 dark:text-gray-200">{{ $item->nama_klien }}</td>
                                    <td class="px-2 py-1 text-right text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($item->nilai_proyek, 0, ',', '.') }}
                                    </td>
                                    <td class="px-2 py-1 text-right text-gray-900 dark:text-gray-100">
                                        {{ $item->estimasi_hpp !== null ? number_format($item->estimasi_hpp, 0, ',', '.') . ' %' : '-' }}
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
                                            // Tambah status lain kalau perlu
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
                                            {{-- Tombol Detail --}}
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

                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('projects.destroy', $item->id_proyek) }}" method="POST"
                                                class="delete-form">
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
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    {{-- CDN Datatables Standar --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.dataTables.js"></script>

    <style>
        div.dt-container .dt-length,
        div.dt-container .dt-search,
        div.dt-container .dt-info,
        div.dt-container .dt-processing,
        div.dt-container .dt-paging {
            color: inherit;
            font-size: 13px;
        }

        .dt-container .dt-search input,
        .dt-container .dt-length select {
            @apply text-xs border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm w-full sm:w-auto h-7 px-2 py-1 !important;
        }

        .dt-container .dt-paging .dt-paging-button {
            @apply bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 font-medium py-1 px-2 rounded-lg mx-0.5 text-xs shadow-sm !important;
        }

        .dt-container .dt-paging .dt-paging-button.current {
            @apply bg-indigo-600 text-white hover:bg-indigo-600 ring-2 ring-indigo-300 dark:ring-indigo-500 !important;
        }

        .dt-container .dt-info {
            @apply text-xs !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Inisialisasi Datatables
            $('#proyekTable').DataTable({
                responsive: true,
                "pageLength": 10,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Semua"]
                ],
                // "language": {
                //     "url": "{{ asset('js/id.json') }}"
                // }
            });

            // Logika SweetAlert
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    let form = this.closest('form');
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
        });
    </script>
@endsection
