@extends('layouts.app')

@section('title', 'Rekap Laporan Proyek')

@section('head')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">Rekap Laporan Proyek</h1>
        </div>

        <form method="GET" action="{{ route('laporan.index') }}"
            class="mb-8 flex flex-col md:flex-row items-stretch md:items-center gap-4 p-5 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
            <div class="flex-1 relative">
                <label for="proyek-select" class="sr-only">Pilih Proyek</label>
                <select name="id_proyek" id="proyek-select"
                    class="w-full border border-gray-300 dark:border-gray-700 rounded-md shadow-sm 
               bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 
               focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">-- Pilih Proyek --</option>
                    @foreach ($proyekList as $proyek)
                        <option value="{{ $proyek->id_proyek }}"
                            {{ request('id_proyek') == $proyek->id_proyek ? 'selected' : '' }}>
                            {{ $proyek->nama_proyek }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors">
                Tampilkan Laporan
            </button>
        </form>

        @if (isset($laporan))
            <div class="bg-white dark:bg-gray-900 shadow-2xl rounded-2xl p-8">
                <div
                    class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-2 md:mb-0">
                        {{ $laporan['nama_proyek'] }}
                    </h2>
                    <span
                        class="px-4 py-1.5 text-sm font-bold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 shadow-md">
                        Laporan Aktif
                    </span>
                </div>

                <div class="flex flex-col gap-10">
                    <div class="flex-1">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6 text-sm">

                            {{-- Nilai Proyek --}}
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800 shadow-sm">
                                <div
                                    class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full text-indigo-600 dark:text-indigo-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 4v2m0 2v4m-2.599-2C8.92 14.195 8 16.101 8 18c0 2.899 4.884 4.148 7 3.5" />
                                    </svg>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400 font-medium text-xs">Nilai Proyek</dt>
                                    <dd class="text-gray-900 dark:text-gray-100 text-base font-bold">
                                        Rp {{ number_format($laporan['nilai_proyek'], 0, ',', '.') }}
                                    </dd>
                                </div>
                            </div>

                            {{-- Adendum --}}
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800 shadow-sm">
                                <div
                                    class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full text-indigo-600 dark:text-indigo-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-3-3v6" />
                                    </svg>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400 font-medium text-xs">Adendum</dt>
                                    <dd class="text-gray-900 dark:text-gray-100 text-base font-bold">
                                        Rp {{ number_format($laporan['adendum'], 0, ',', '.') }}
                                    </dd>
                                </div>
                            </div>

                            {{-- Estimasi HPP (nominal + persen) --}}
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800 shadow-sm">
                                <div
                                    class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full text-indigo-600 dark:text-indigo-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 12l-2-2-2 2m4 0l-2 2 2-2m-2-2l-2 2 2 2m4-2h8" />
                                    </svg>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400 font-medium text-xs">Estimasi HPP</dt>
                                    <dd class="text-gray-900 dark:text-gray-100 text-base font-bold">
                                        Rp {{ number_format($laporan['estimasi_hpp'], 0, ',', '.') }}
                                        ({{ number_format($proyek->estimasi_hpp ?? 0, 0) }}%)
                                    </dd>
                                </div>
                            </div>

                            {{-- Real HPP --}}
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800 shadow-sm">
                                <div
                                    class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full text-indigo-600 dark:text-indigo-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14v10a2 2 0 01-2 2H7a2 2 0 01-2-2V9z" />
                                    </svg>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400 font-medium text-xs">Real HPP</dt>
                                    <dd class="text-gray-900 dark:text-gray-100 text-base font-bold">
                                        Rp {{ number_format($laporan['real_hpp'], 0, ',', '.') }}
                                    </dd>
                                </div>
                            </div>

                            {{-- Estimasi Profit --}}
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800 shadow-sm">
                                <div
                                    class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full text-indigo-600 dark:text-indigo-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400 font-medium text-xs">Estimasi Profit</dt>
                                    <dd class="text-gray-900 dark:text-gray-100 text-base font-bold">
                                        Rp {{ number_format($laporan['estimasi_profit'], 0, ',', '.') }}
                                    </dd>
                                </div>
                            </div>

                            {{-- Real Profit --}}
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800 shadow-sm">
                                <div
                                    class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full text-indigo-600 dark:text-indigo-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2-3 .895-3 2 1.343 2 3 2" />
                                    </svg>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400 font-medium text-xs">Real Profit</dt>
                                    <dd class="text-gray-900 dark:text-gray-100 text-base font-bold">
                                        Rp {{ number_format($laporan['real_profit'], 0, ',', '.') }}
                                    </dd>
                                </div>
                            </div>

                            {{-- Margin vs HPP --}}
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800 shadow-sm">
                                <div
                                    class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full text-indigo-600 dark:text-indigo-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 12l2 2 2-2m-2-2l-2 2-2-2m4-2h8" />
                                    </svg>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400 font-medium text-xs">Persentase Margin -
                                        HPP</dt>
                                    <dd class="text-gray-900 dark:text-gray-100 text-base font-bold">
                                        {{ $laporan['margin_hpp'] }} %
                                    </dd>
                                </div>
                            </div>

                            {{-- Margin vs Nilai Proyek --}}
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800 shadow-sm">
                                <div
                                    class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full text-indigo-600 dark:text-indigo-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13l-3-3m0 0l-3 3m3-3v8" />
                                    </svg>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400 font-medium text-xs">Persentase Margin -
                                        Nilai Proyek
                                    </dt>
                                    <dd class="text-gray-900 dark:text-gray-100 text-base font-bold">
                                        {{ $laporan['margin_nilai'] }} %
                                    </dd>
                                </div>
                            </div>

                            {{-- Pembayaran Client --}}
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800 shadow-sm">
                                <div
                                    class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full text-indigo-600 dark:text-indigo-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8zM7 16h10" />
                                    </svg>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400 font-medium text-xs">Pembayaran Client</dt>
                                    <dd class="text-gray-900 dark:text-gray-100 text-base font-bold">
                                        Rp {{ number_format($laporan['total_pembayaran_client'], 0, ',', '.') }}
                                    </dd>
                                </div>
                            </div>

                            {{-- Sisa Kewajiban Client --}}
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800 shadow-sm">
                                <div
                                    class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full text-indigo-600 dark:text-indigo-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400 font-medium text-xs">Sisa Kewajiban Client
                                    </dt>
                                    <dd class="text-gray-900 dark:text-gray-100 text-base font-bold">
                                        Rp {{ number_format($laporan['sisa_kewajiban_client'], 0, ',', '.') }}
                                    </dd>
                                </div>
                            </div>

                            {{-- Sisa Kas Proyek --}}
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800 shadow-sm">
                                <div
                                    class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full text-indigo-600 dark:text-indigo-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 11c0 3.313 2.687 6 6 6s6-2.687 6-6-2.687-6-6-6h-6V5c0-1.104-.896-2-2-2H4c-1.104 0-2 .896-2 2v14c0 1.104.896 2 2 2h8c1.104 0 2-.896 2-2v-4z" />
                                    </svg>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400 font-medium text-xs">Sisa Kas Proyek</dt>
                                    <dd class="text-gray-900 dark:text-gray-100 text-base font-bold">
                                        Rp {{ number_format($laporan['sisa_kas'], 0, ',', '.') }}
                                    </dd>
                                </div>
                            </div>

                        </dl>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <!-- CSS yang ditambahkan sebelum </head> -->
    {{-- <style>
        /* CSS dengan specificity tertinggi untuk override semua style Select2 */
        .select2-container--default .select2-selection--single {
            height: 48px !important;
            min-height: 48px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            background-color: #ffffff !important;
            background-image: none !important;
            display: flex !important;
            align-items: center !important;
            font-size: 0.875rem !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            transition: border-color 0.15s ease-in-out !important;
            padding: 0 !important;
            margin: 0 !important;
            line-height: normal !important;
            height: 48px;
            padding: 0 1rem;
        }

        .select2-container--default .select2-selection__rendered {
            /* Pastikan teks berada di tengah secara vertikal */
            line-height: 48px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151 !important;
            padding-left: 1rem !important;
            padding-right: 3rem !important;
            line-height: 46px !important;
            font-size: 0.875rem !important;
            display: block !important;
            width: 100% !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            margin: 0 !important;
            font-weight: 400 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
            position: absolute !important;
            top: 1px !important;
            right: 1rem !important;
            width: 2rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #6b7280 transparent transparent transparent !important;
            border-style: solid !important;
            border-width: 5px 4px 0 4px !important;
            height: 0 !important;
            left: 50% !important;
            margin-left: -4px !important;
            margin-top: -2px !important;
            position: absolute !important;
            top: 50% !important;
            width: 0 !important;
        }

        /* Focus state */
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #4f46e5 !important;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
        }

        /* Dropdown styles */
        .select2-container--default .select2-results>.select2-results__options {
            max-height: 200px !important;
            overflow-y: auto !important;
        }

        .select2-container--default .select2-results__option {
            padding: 0.5rem 1rem !important;
            font-size: 0.875rem !important;
            color: #374151 !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #4f46e5 !important;
            color: white !important;
        }

        /* Pastikan dropdown tidak terlihat saat closed */
        .select2-container--default:not(.select2-container--open) .select2-dropdown {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }

        .select2-container--default.select2-container--open .select2-dropdown {
            /* Perbaikan pada posisi dropdown */
            position: absolute;
            left: 0;
            top: 100%;
            /* Agar dropdown berada di bawah select */
            margin-top: 0.25rem;
            /* Tambahkan sedikit jarak */

            /* Aturan tampilan lain */
            width: 100%;
            z-index: 100;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Force hide any floating dropdown */
        .select2-dropdown {
            display: none !important;
        }

        .select2-container--open .select2-dropdown {
            display: block !important;
        }

        /* Hide default select */
        .select2-hidden-accessible {
            border: 0 !important;
            clip: rect(0 0 0 0) !important;
            height: 1px !important;
            margin: -1px !important;
            overflow: hidden !important;
            padding: 0 !important;
            position: absolute !important;
            width: 1px !important;
        }

        /* Form styling untuk parent container */
        form .flex-1 {
            position: relative !important;
        }

        form .flex-1 .select2-container {
            width: 100% !important;
        }
    </style>

    <!-- JavaScript yang diperbaiki -->
    <script>
        $(document).ready(function() {
            // Pastikan element ada sebelum inisialisasi
            if ($('#proyek-select').length === 0) {
                console.error('Element #proyek-select tidak ditemukan!');
                return;
            }

            console.log('Menginisialisasi Select2...');

            // Hapus Select2 yang mungkin sudah ada
            if ($('#proyek-select').hasClass('select2-hidden-accessible')) {
                $('#proyek-select').select2('destroy');
            }

            // Inisialisasi Select2 dengan konfigurasi lengkap
            $('#proyek-select').select2({
                placeholder: "-- Pilih Proyek --",
                allowClear: true,
                width: '100%',
                theme: 'default',
                minimumResultsForSearch: -1, // Hilangkan search box jika tidak perlu
                dropdownAutoWidth: false,
                closeOnSelect: true
            });

            console.log('Select2 berhasil diinisialisasi!');

            // Force styling dan close dropdown setelah inisialisasi
            setTimeout(() => {
                // Tutup dropdown yang mungkin terbuka
                $('#proyek-select').select2('close');

                // Pastikan container tidak memiliki class open
                $('.select2-container').removeClass('select2-container--open');

                // Hide semua dropdown yang mungkin masih terlihat
                $('.select2-dropdown').hide();

                console.log('✅ Select2 styling applied and dropdown closed');
            }, 100);

            // Auto-close dropdown setelah selection
            $('#proyek-select').on('select2:select', function(e) {
                console.log('Option selected:', e.params.data);

                // Force close dropdown
                setTimeout(() => {
                    $(this).select2('close');
                    $('.select2-dropdown').hide();
                }, 50);
            });

            // Event listener untuk memastikan dropdown behavior
            $('#proyek-select').on('select2:open', function() {
                console.log('Dropdown opened');
            });

            $('#proyek-select').on('select2:close', function() {
                console.log('Dropdown closed');
                // Force hide dropdown elements
                setTimeout(() => {
                    $('.select2-dropdown').hide();
                }, 10);
            });

            $('#proyek-select').on('select2:select', function(e) {
                console.log('Option selected:', e.params.data);
            });

            console.log('🎯 Select2 system ready');
        });
    </script> --}}



@endsection
