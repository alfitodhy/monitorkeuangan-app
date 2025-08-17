@extends('layouts.app')

@section('title', 'Pengeluaran Proyek')

@section('content')


    <div class="container mx-auto p-4 sm:p-6 lg:p-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-0">Data Pengeluaran Proyek</h1>
            <div class="flex gap-2">
                <a href="{{ route('pengeluaran.create') }}"
                    class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg text-sm font-medium">
                    + Tambah
                </a>

            </div>
        </div>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        {{-- Table --}}
        @if ($pengeluaran->isEmpty())
            <div class="flex items-center justify-center h-64 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                <div class="text-center text-gray-500 dark:text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="font-semibold text-lg">Belum ada data pengeluaran proyek.</p>
                </div>
            </div>
        @else
            <div class="overflow-x-auto rounded-lg shadow border border-gray-200 dark:border-gray-700">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Proyek</th>
                            <th class="px-4 py-3">Vendor</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Jumlah</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengeluaran as $index => $item)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">{{ $item->nama_proyek }}</td>
                                <td class="px-4 py-2">{{ $item->nama_vendor ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($item->tanggal_pengeluaran)->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">
                                    @php
                                        $statusClass = match ($item->status) {
                                            'Pengajuan'
                                                => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200',
                                            'Diterima'
                                                => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200',
                                            'Ditolak' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded {{ $statusClass }}">
                                        {{ ucfirst($item->status ?: 'Pengajuan') }}
                                    </span>
                                </td>

                                <td class="px-3 py-2 text-center space-x-1">

                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('pengeluaran.show', $item->id_pengeluaran) }}" title="Lihat Detail"
                                        class="inline-flex items-center justify-center w-7 h-7 bg-blue-500 hover:bg-blue-600 text-white rounded shadow">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    {{-- Tombol Edit --}}
                                    {{-- @if (
                                        $item->status === 'Pengajuan' &&
                                            (auth()->user()->role === 'super admin' || auth()->user()->id_user === $item->user_created))
                                        <a href="{{ route('pengeluaran.edit', $item->id_pengeluaran) }}"
                                            title="Edit Pengeluaran"
                                            class="inline-flex items-center justify-center w-7 h-7 bg-yellow-500 hover:bg-yellow-600 text-white rounded shadow">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11 16H7v-4l2-2z" />
                                            </svg>
                                        </a>
                                    @endif --}}

                                    {{-- Tombol Hapus --}}
                                    @if (
                                        $item->status === 'Pengajuan' &&
                                            (auth()->user()->role === 'super admin' || auth()->user()->id_user === $item->user_created))
                                        <form action="{{ route('pengeluaran.destroy', $item->id_pengeluaran) }}"
                                            method="POST" class="inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" title="Hapus Pengeluaran"
                                                class="delete-btn inline-flex items-center justify-center w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded shadow">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Approve & Tolak untuk BOD/Admin Keuangan --}}
                                    @if (in_array(auth()->user()->role, ['bod', 'admin keuangan', 'super admin']) && $item->status === 'Pengajuan')
                                        {{-- Modal Approve & Tolak --}}
                                        <div x-data="{ openApprove: null, openReject: null }">
                                            {{-- Approve --}}
                                            <button type="button" title="Approve" @click="openApprove = true"
                                                class="inline-flex items-center justify-center w-7 h-7 bg-green-500 hover:bg-green-600 text-white rounded shadow">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>

                                            {{-- Tolak --}}
                                            <button type="button" title="Tolak" @click="openReject = true"
                                                class="inline-flex items-center justify-center w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded shadow">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>



                                            {{-- Modal Approve --}}
                                            <div x-show="openApprove"
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 w-full max-w-sm transform transition-all duration-300 scale-95 opacity-0"
                                                    :class="{ 'scale-100 opacity-100': openApprove }">
                                                    <div
                                                        class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mx-auto mb-4">
                                                        <svg class="h-6 w-6 text-green-600" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                    <h3
                                                        class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">
                                                        Approve Pengeluaran</h3>
                                                    <p class="text-sm text-center text-gray-500 dark:text-gray-400 mb-6">
                                                        Anda yakin ingin menyetujui pengeluaran ini?</p>
                                                    <form
                                                        action="{{ route('pengeluaran.approve', $item->id_pengeluaran) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <label for="file_buktitf"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unggah
                                                            Bukti Transfer</label>
                                                        <input type="file" name="file_buktitf[]" multiple required
                                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 mb-4">
                                                        <div class="flex justify-end space-x-2">
                                                            <button type="button" @click="openApprove=false"
                                                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Batal</button>
                                                            <button type="submit"
                                                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">Approve</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <div x-show="openReject"
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 w-full max-w-sm transform transition-all duration-300 scale-95 opacity-0"
                                                    :class="{ 'scale-100 opacity-100': openReject }">
                                                    <div
                                                        class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mx-auto mb-4">
                                                        <svg class="h-6 w-6 text-red-600" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </div>
                                                    <h3
                                                        class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">
                                                        Tolak Pengeluaran</h3>
                                                    <p class="text-sm text-center text-gray-500 dark:text-gray-400 mb-6">
                                                        Mohon berikan alasan penolakan.</p>
                                                    <form
                                                        action="{{ route('pengeluaran.reject', $item->id_pengeluaran) }}"
                                                        method="POST">
                                                        @csrf
                                                        <textarea name="catatan_bod" rows="3" required
                                                            class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 mb-4 bg-white dark:bg-gray-700 dark:text-white placeholder-gray-400"></textarea>
                                                        <div class="flex justify-end space-x-2">
                                                            <button type="button" @click="openReject=false"
                                                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Batal</button>
                                                            <button type="submit"
                                                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">Tolak</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                    @endif
                                </td>

            </div>
    </div>


    <script>
        function openModal(id) {
            document.querySelectorAll('[id^="approve-"], [id^="reject-"]').forEach(el => el.style.display = 'none');
            document.getElementById(id).style.display = 'flex';
        }
    </script>


    </tr>
    @endforeach
    </tbody>
    </table>
    </div>
    @endif



    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function(e) {
                    e.preventDefault();
                    let form = this.closest("form");

                    Swal.fire({
                        title: 'Konfirmasi Penghapusan Data',
                        text: "Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal'
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
