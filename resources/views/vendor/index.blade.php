@extends('layouts.app')

@section('title', 'Manajemen Vendor')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">
    {{-- Header Halaman --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-0">Data Vendor</h1>
        <a href="{{ route('vendor.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-1.5 px-4 rounded-md shadow-sm text-sm transition duration-300 ease-in-out">
            Tambah Vendor
        </a>
    </div>

    {{-- Pesan Sukses --}}
    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-950 border border-green-400 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg relative mb-6">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Tampilan saat tidak ada data --}}
    @if ($vendors->isEmpty())
        <div class="flex items-center justify-center h-64 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
            <div class="text-center text-gray-500 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="font-semibold text-lg">Belum ada data vendor yang tersedia.</p>
            </div>
        </div>
    @else
        {{-- Tabel Data Vendor --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-3 py-2 text-center font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">No</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Kode</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Nama Vendor</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Alamat</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Kontak</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Email</th>
                            <th class="px-3 py-2 text-center font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($vendors as $index => $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-75">
                            <td class="px-3 py-2 text-center text-gray-700 dark:text-gray-200 font-medium">{{ $index + 1 }}</td>
                            <td class="px-3 py-2 text-gray-900 dark:text-gray-100 font-medium">{{ $item->kode_vendor }}</td>
                            <td class="px-3 py-2 text-gray-700 dark:text-gray-200">{{ $item->nama_vendor }}</td>
                            <td class="px-3 py-2 text-gray-700 dark:text-gray-200">{{ $item->alamat }}</td>
                            <td class="px-3 py-2 text-gray-700 dark:text-gray-200">{{ $item->no_telp }}</td>
                            <td class="px-3 py-2 text-gray-700 dark:text-gray-200">{{ $item->email }}</td>
                            <td class="px-3 py-2 text-center space-x-1">
                                {{-- Tombol Detail --}}
                                <a href="{{ route('vendor.show', $item->id_vendor) }}"
                                    title="Lihat Detail"
                                    class="inline-flex items-center justify-center w-7 h-7 bg-blue-500 hover:bg-blue-600 text-white rounded shadow">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                {{-- Tombol Edit --}}
                                <a href="{{ route('vendor.edit', $item->id_vendor) }}"
                                    title="Edit Vendor"
                                    class="inline-flex items-center justify-center w-7 h-7 bg-yellow-500 hover:bg-yellow-600 text-white rounded shadow">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11 16H7v-4l2-2z"/>
                                    </svg>
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('vendor.destroy', $item->id_vendor) }}" method="POST" class="inline-block delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                        title="Hapus Vendor"
                                        class="delete-btn inline-flex items-center justify-center w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded shadow">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            let form = this.closest('form');

            Swal.fire({
                title: 'Anda Yakin ingin menghapus Data?',
                text: "Data vendor ini akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                width: '440px',
                padding: '1em',
                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#111827',
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
