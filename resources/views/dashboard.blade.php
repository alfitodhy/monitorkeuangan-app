@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        {{-- Pesan Selamat Datang --}}
        @if (session('welcome'))
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-medium">{{ session('welcome') }}</h3>
                </div>
            </div>
        @endif

        {{-- Cards Rekap Pengeluaran khusus Kepala Operational --}}
        @if (Auth::user()->role === 'kepala operational')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h4 class="text-sm text-gray-500 dark:text-gray-400">Pengajuan Selesai (Bulan Ini)</h4>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $rekapBulanIni }}
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h4 class="text-sm text-gray-500 dark:text-gray-400">Total Pengajuan Aktif</h4>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $totalPengeluaranKepalaops }}
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h4 class="text-sm text-gray-500 dark:text-gray-400">Total Sedang Diproses</h4>
                    <p class="text-2xl font-bold text-yellow-500">
                        {{ $totalSedangDiproses }}
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h4 class="text-sm text-gray-500 dark:text-gray-400">Sudah Dibayar (Hari Ini)</h4>
                    <p class="text-2xl font-bold text-green-600">
                        {{ $totalSudahDibayarHariIni }}
                    </p>
                </div>
            </div>
        @endif
    </div>
@endsection
