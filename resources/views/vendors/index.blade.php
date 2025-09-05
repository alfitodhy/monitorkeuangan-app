@extends('layouts.app')

@section('title', 'Manajemen Vendor')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">
    {{-- Header Halaman --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-0">Data Vendor</h1>
        <a href="{{ route('vendors.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-1.5 px-4 rounded-md shadow-sm text-sm transition duration-300 ease-in-out">
            Tambah Vendor
        </a>
    </div>

    {{-- Pesan Sukses --}}
    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-950 border border-green-400 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg relative mb-6">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Tabel Data Vendor (AJAX DataTables) --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table id="vendorTable" class="min-w-full text-xs">
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
            </table>
        </div>
    </div>
</div>

{{-- Inisialisasi DataTables --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    $('#vendorTable').DataTable({
        processing: false,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('vendors.datatable') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
            { data: 'kode_vendor', name: 'kode_vendor' },
            { data: 'nama_vendor', name: 'nama_vendor' },
            { data: 'alamat', name: 'alamat' },
            { data: 'no_telp', name: 'no_telp' },
            { data: 'email', name: 'email' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' }
        ],
        language: window.dtLangId, // pakai bahasa Indonesia
    });
});
</script>
@endsection
