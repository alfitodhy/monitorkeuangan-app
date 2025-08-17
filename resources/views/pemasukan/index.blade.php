@extends('layouts.app')

@section('title', 'Pemasukan Proyek')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-0">Data Pemasukan Proyek</h1>
        <a href="{{ route('pemasukan.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-1.5 px-4 rounded-md shadow-sm text-sm transition duration-300 ease-in-out">
            Tambah Pemasukan
        </a>
    </div>

    {{-- Pesan Sukses --}}
    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-950 border border-green-400 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg relative mb-6">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Tabel --}}
    @if ($pemasukan->isEmpty())
        <div class="flex items-center justify-center h-64 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
            <div class="text-center text-gray-500 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="font-semibold text-lg">Belum ada data pemasukan proyek.</p>
            </div>
        </div>
    @else
       <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">
    <thead class="bg-gray-50 dark:bg-gray-700">
    <tr>
        <th class="px-3 py-2 text-center font-semibold text-gray-600 dark:text-gray-300 uppercase">No</th>
        <th class="px-3 py-2 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase">Proyek</th>
        <th class="px-3 py-2 text-right font-semibold text-gray-600 dark:text-gray-300 uppercase">Total Termin</th>
        <th class="px-3 py-2 text-right font-semibold text-gray-600 dark:text-gray-300 uppercase">Total Dibayar</th>
        <th class="px-3 py-2 text-right font-semibold text-gray-600 dark:text-gray-300 uppercase">Sisa</th>
        <th class="px-3 py-2 text-center font-semibold text-gray-600 dark:text-gray-300 uppercase">Aksi</th>
    </tr>
</thead>
<tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
    @foreach ($pemasukan as $index => $item)
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-75">
        <td class="px-3 py-2 text-center text-gray-700 dark:text-gray-200 font-medium">{{ $index + 1 }}</td>
        <td class="px-3 py-2 text-gray-900 dark:text-gray-100 font-medium">{{ $item->nama_proyek }}</td>
        <td class="px-3 py-2 text-right text-gray-900 dark:text-gray-100">{{ number_format($item->total_termin, 0, ',', '.') }}</td>
        <td class="px-3 py-2 text-right text-green-600 dark:text-green-400 font-semibold">Rp {{ number_format($item->total_dibayar, 0, ',', '.') }}</td>
        <td class="px-3 py-2 text-right text-red-600 dark:text-red-400 font-semibold">Rp {{ number_format($item->sisa_bayar, 0, ',', '.') }}</td>
        <td class="px-3 py-2 text-center">
            <a href="{{ route('pemasukan.show', $item->id_proyek) }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs shadow">
               Detail
            </a>
        </td>
    </tr>
    @endforeach
</tbody>

    @endif
</div>
       </div>
</div>
@endsection
