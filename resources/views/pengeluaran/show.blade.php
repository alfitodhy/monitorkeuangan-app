@extends('layouts.app')

@section('title', 'Detail Pengeluaran Proyek')

@section('content')
    <div class="container mx-auto p-4 sm:p-6 lg:p-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-0">

            </h1>
            <div class="flex space-x-2">
                <a href="{{ route('pengeluaran.index') }}"
                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                    <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        {{-- Detail Pengeluaran --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pengeluaran->nama_proyek ?? '-' }}</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 text-sm">

                {{-- Informasi Utama --}}
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9.75 21 12.25 21 12.25 17 9.75 17Z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-500 dark:text-gray-400">Vendor</p>
                            <p class="text-gray-900 dark:text-white">{{ $pengeluaran->nama_vendor ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14v-4m0 4a2 2 0 110-4 2 2 0 010 4z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z" />
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-500 dark:text-gray-400">Rekening</p>
                            <p class="text-gray-900 dark:text-white">{{ $pengeluaran->rekening ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 12m-8 0a8 8 0 1016 0A8 8 0 104 12z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-500 dark:text-gray-400">Jumlah</p>
                            <p class="text-blue-600 dark:text-blue-400 font-extrabold">Rp
                                {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Status & Keterangan --}}
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-500 dark:text-gray-400">Status</p>
                            @php
                                $statusClass = match ($pengeluaran->status) {
                                    'Pengajuan'
                                        => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200',
                                    'Sedang diproses'
                                        => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200',
                                    'Sudah dibayar'
                                        => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200',
                                    'Ditolak' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
                                };
                            @endphp

                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">
                                {{ ucfirst($pengeluaran->status) }}
                            </span>
                        </div>

                    </div>

                    {{-- Keterangan --}}
                    <div class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-500 dark:text-gray-400">Keterangan</p>
                            <p class="text-gray-900 dark:text-white">
                                {{ $pengeluaran->keterangan ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-500 dark:text-gray-400">Diajukan Oleh</p>
                            <p class="text-gray-900 dark:text-white">{{ $pengeluaran->user_created ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Catatan BOD --}}
                @if ($pengeluaran->catatan_bod)
                    <div class="md:col-span-2">
                        <div class="flex items-start space-x-3">
                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-500 dark:text-gray-400">Catatan BOD</p>
                                <p
                                    class="text-gray-900 dark:text-white italic whitespace-pre-line bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                    {{ $pengeluaran->catatan_bod }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Lampiran --}}
                @if ($pengeluaran->file_nota || $pengeluaran->file_buktitf)
                    <div class="md:col-span-2">
                        <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-2">Lampiran</h4>
                        @php
                            $attachments = [];

                            if ($pengeluaran->file_nota) {
                                $attachments[] = [
                                    'tipe' => 'Nota',
                                    'file' => basename($pengeluaran->file_nota),
                                    'path' => $pengeluaran->file_nota,
                                ];
                            }

                            if ($pengeluaran->file_buktitf) {
                                $attachments[] = [
                                    'tipe' => 'Bukti Transfer',
                                    'file' => basename($pengeluaran->file_buktitf),
                                    'path' => $pengeluaran->file_buktitf,
                                ];
                            }
                        @endphp

                        <div class="space-y-3">
                            @foreach ($attachments as $att)
                                <div
                                    class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        <div>
                                            <p class="font-semibold text-gray-500 dark:text-gray-400">{{ $att['tipe'] }}
                                            </p>
                                            <span class="text-gray-900 dark:text-white text-sm">{{ $att['file'] }}</span>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ asset('storage/' . $att['path']) }}" target="_blank"
                                            class="inline-flex items-center justify-center p-1 rounded-full text-gray-400 hover:text-blue-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out"
                                            title="Lihat">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ asset('storage/' . $att['path']) }}" download
                                            class="inline-flex items-center justify-center p-1 rounded-full text-gray-400 hover:text-green-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out"
                                            title="Unduh">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
