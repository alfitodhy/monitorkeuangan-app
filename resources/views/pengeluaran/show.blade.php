@extends('layouts.app')

@section('title', 'Detail Pengeluaran Proyek')

@section('content')
    <div class="container mx-auto p-4 sm:p-6 lg:p-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-0">
                Detail Pengeluaran
            </h1>
            <div class="flex space-x-2">
                {{-- <a href="{{ route('pengeluaran.edit', $pengeluaran->id_pengeluaran) }}"
                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out">
                    <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M13.586 3.586a2 2 0 112.828 2.828l-.794.793-2.828-2.828.794-.793zM11.379 5.793L3 14.172V17h2.828l8.389-8.389-2.828-2.828z" />
                    </svg>
                    Edit
                </a> --}}
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

                {{-- Status & Metadata --}}
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
                                $statusClass = [
                                    'pending' => 'bg-yellow-500/10 text-yellow-500 ring-yellow-500/30',
                                    'diterima' => 'bg-green-500/10 text-green-500 ring-green-500/30',
                                    'ditolak' => 'bg-red-500/10 text-red-500 ring-red-500/30',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $statusClass[$pengeluaran->status] ?? 'bg-gray-500/10 text-gray-500 ring-gray-500/30' }}">
                                {{ ucfirst($pengeluaran->status) }}
                            </span>
                        </div>
                    </div>

                    {{-- <div class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-500 dark:text-gray-400">Diajukan Oleh</p>
                            <p class="text-gray-900 dark:text-white">{{ $pengeluaran->user_created ?? '-' }}</p>
                        </div>
                    </div> --}}


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

                            // file_nota bisa string atau array
                            if ($pengeluaran->file_nota) {
                                if (is_array($pengeluaran->file_nota)) {
                                    foreach ($pengeluaran->file_nota as $nota) {
                                        $attachments[] = ['tipe' => 'Nota', 'file' => basename($nota), 'path' => $nota];
                                    }
                                } else {
                                    $attachments[] = [
                                        'tipe' => 'Nota',
                                        'file' => basename($pengeluaran->file_nota),
                                        'path' => $pengeluaran->file_nota,
                                    ];
                                }
                            }

                            // file_buktitf bisa string atau array
                            if ($pengeluaran->file_buktitf) {
                                if (is_array($pengeluaran->file_buktitf)) {
                                    foreach ($pengeluaran->file_buktitf as $bukti) {
                                        $attachments[] = [
                                            'tipe' => 'Bukti Transfer',
                                            'file' => basename($bukti),
                                            'path' => $bukti,
                                        ];
                                    }
                                } else {
                                    $attachments[] = [
                                        'tipe' => 'Bukti Transfer',
                                        'file' => basename($pengeluaran->file_buktitf),
                                        'path' => $pengeluaran->file_buktitf,
                                    ];
                                }
                            }
                        @endphp

                        <div class="space-y-3">
                            @foreach ($attachments as $att)
                                <div
                                    class="flex items-start space-x-3 p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-1"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-gray-500 dark:text-gray-400">{{ $att['tipe'] }}</p>
                                        <a href="{{ asset($att['path']) }}" target="_blank"
                                            class="text-blue-600 dark:text-blue-400 hover:underline">{{ $att['file'] }}</a>
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
