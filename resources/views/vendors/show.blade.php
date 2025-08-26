@extends('layouts.app')

@section('title', 'Detail Vendor')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">

    {{-- Header --}}
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-0">
        Data Vendor
    </h1>
    <div class="flex space-x-2">
        <a href="{{ route('vendors.edit', $vendor->id_vendor) }}"
            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out">
            <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.794.793-2.828-2.828.794-.793zM11.379 5.793L3 14.172V17h2.828l8.389-8.389-2.828-2.828z" />
            </svg>
            Edit
        </a>
        <a href="{{ route('vendors.index') }}"
            class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
            <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </a>
    </div>
</div>
    {{-- Detail Vendor --}}
 <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $vendor->nama_vendor }}</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 text-sm">

        {{-- Grup Informasi Kontak --}}
        <div>
            <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-2">Informasi Kontak</h4>
            <div class="space-y-4">
                {{-- Email --}}
                <div class="flex items-start space-x-3">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-500 dark:text-gray-400">Email</p>
                        <p class="text-gray-900 dark:text-white break-all">{{ $vendor->email ?? '-' }}</p>
                    </div>
                </div>

                {{-- No. Telepon --}}
                <div class="flex items-start space-x-3">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-500 dark:text-gray-400">No. Telepon</p>
                        <p class="text-gray-900 dark:text-white">{{ $vendor->no_telp ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Grup Informasi Vendor --}}
        <div>
            <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-2">Jenis & Spesialisasi</h4>
            <div class="space-y-4">
                {{-- Jenis Vendor --}}
                <div class="flex items-start space-x-3">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-500 dark:text-gray-400">Jenis Vendor</p>
                        <p class="text-gray-900 dark:text-white">{{ $vendor->jenis_vendor ?? '-' }}</p>
                    </div>
                </div>

                {{-- Spesialisasi --}}
                <div class="flex items-start space-x-3">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-1-3m0-13V9a2 2 0 01-2 2H7.25a2 2 0 01-2-2V4M7.25 4h9.5A2.25 2.25 0 0119 6.25v13.5A2.25 2.25 0 0116.75 22H7.25A2.25 2.25 0 015 19.75V6.25A2.25 2.25 0 017.25 4z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-500 dark:text-gray-400">Spesialisasi</p>
                        <p class="text-gray-900 dark:text-white">{{ $vendor->spesialisasi ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alamat --}}
        <div class="md:col-span-2">
            <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-2">Alamat</h4>
            <div class="flex items-start space-x-3">
                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <div>
                    <p class="font-semibold text-gray-500 dark:text-gray-400">Lokasi</p>
                    <p class="text-gray-900 dark:text-white">{{ $vendor->alamat ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- Detail Rekening --}}
 <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Informasi Rekening Bank</h2>
    
    <div class="space-y-6">
        @php
            $rekening_list = json_decode($vendor->rekening, true) ?? [];
        @endphp

        @forelse ($rekening_list as $key => $rekening)
            <div class="rekening-card p-6 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="bg-indigo-100 text-indigo-600 dark:bg-indigo-700 dark:text-indigo-100 rounded-full h-8 w-8 flex items-center justify-center text-sm font-bold mr-3">
                        {{ $key + 1 }}
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Rekening {{ $key + 1 }}</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-y-4 md:gap-x-8 text-sm">
                    <div class="flex items-center space-x-3">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <div>
                            <p class="font-medium text-gray-500 dark:text-gray-400">Atas Nama</p>
                            <p class="text-gray-900 dark:text-white font-semibold">{{ $rekening['atas_nama'] ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10v11h18V10M12 3v7m-7 0h14" />
                        </svg>
                        <div>
                            <p class="font-medium text-gray-500 dark:text-gray-400">Nama Bank</p>
                            <p class="text-gray-900 dark:text-white font-semibold">{{ $rekening['nama_bank'] ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M6 10v11h12V10M6 10h12M6 10a3 3 0 010-6h12a3 3 0 010 6" />
                        </svg>
                        <div>
                            <p class="font-medium text-gray-500 dark:text-gray-400">No. Rekening</p>
                            <p class="text-gray-900 dark:text-white font-semibold">{{ $rekening['no_rekening'] ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center p-8 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada data rekening</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Rekening bank belum ditambahkan.</p>
            </div>
        @endforelse
    </div>
</div>
</div>
@endsection