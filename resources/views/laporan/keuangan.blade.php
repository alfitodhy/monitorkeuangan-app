@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
    <div class="container mx-auto p-6 bg-gray-50 min-h-screen dark:bg-gray-900 transition-colors duration-200">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-800 dark:text-gray-100">Laporan Keuangan Proyek</h1>
                <p class="text-sm text-gray-500 dark:text-gray-300">Ringkasan pemasukan & pengeluaran proyek</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-8 transition-colors">
            <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-100 mb-4">Filter Laporan</h2>
            <form method="GET" action="{{ route('laporan.keuangan') }}" class="space-y-4">

                <!-- Row 1: Tahun & Periode -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tahun</label>
                        <select name="tahun"
                            class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                            @for ($t = date('Y') - 3; $t <= date('Y') + 1; $t++)
                                <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Periode</label>
                        <select name="periode" id="periode"
                            class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                            <option value="bulan" {{ $periode == 'bulan' ? 'selected' : '' }}>Per Bulan (Tahunan)</option>
                            <option value="minggu" {{ $periode == 'minggu' ? 'selected' : '' }}>Per Minggu (Bulanan)
                            </option>
                            <option value="hari" {{ $periode == 'hari' ? 'selected' : '' }}>Per Hari (Bulanan)</option>
                        </select>
                    </div>
                </div>

                <!-- Row 2: Bulan & Jenis (conditional) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div id="bulanField" style="display: {{ $periode === 'bulan' ? 'none' : 'block' }}">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bulan</label>
                        <select name="bulan"
                            class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                            @foreach (range(1, 12) as $b)
                                <option value="{{ $b }}" {{ ($bulan ?? date('n')) == $b ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis
                            Transaksi</label>
                        <select name="jenis"
                            class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                            <option value="semua" {{ $jenis == 'semua' ? 'selected' : '' }}>Semua</option>
                            <option value="pemasukan" {{ $jenis == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="pengeluaran" {{ $jenis == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow-lg transition duration-200">
                        <i class="fas fa-chart-bar mr-2"></i> Cari Laporan
                    </button>
                </div>
            </form>
        </div>

        @php
            $totalMasuk = $grouped->flatten()->where('tipe', 'pemasukan')->sum('jumlah');
            $totalKeluar = $grouped->flatten()->where('tipe', 'pengeluaran')->sum('jumlah');
            // $saldo = $totalMasuk - $totalKeluar;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div
                class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md flex items-center space-x-4 border-l-4 border-green-500 transition-colors">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V4m-7 4a5 5 0 0110 0m-8 4a5 5 0 0110 0" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-300">Total Pemasukan</div>
                    <div class="text-2xl font-bold text-green-700 dark:text-green-300">Rp
                        {{ number_format($totalMasuk, 0, ',', '.') }}</div>
                </div>
            </div>
            <div
                class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md flex items-center space-x-4 border-l-4 border-red-500 transition-colors">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-300">Total Pengeluaran</div>
                    <div class="text-2xl font-bold text-red-700 dark:text-red-300">Rp
                        {{ number_format($totalKeluar, 0, ',', '.') }}</div>
                </div>
            </div>
            {{-- <div
                class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md flex items-center space-x-4 border-l-4 border-blue-500 transition-colors">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-12 12l9-9m12 0l-9 9" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-300">Saldo Akhir</div>
                    <div
                        class="text-2xl font-bold {{ $saldo >= 0 ? 'text-blue-700 dark:text-blue-300' : 'text-red-700 dark:text-red-300' }}">
                        Rp
                        {{ number_format($saldo, 0, ',', '.') }}</div>
                </div>
            </div> --}}
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-8 transition-colors">
            <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-100 mb-4">Grafik Keuangan</h2>
            <div style="height: 400px;">
                <canvas id="financialChart"></canvas>
            </div>
        </div>

        @forelse($grouped as $key => $items)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6 transition-colors">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    @if ($periode === 'bulan')
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $key)->translatedFormat('F Y') }}
                    @elseif($periode === 'minggu')
                        @php
                            [$yearMonth, $week] = explode('-W', $key); // [2025-08, 2]
                            $date = \Carbon\Carbon::createFromFormat('Y-m', $yearMonth);
                        @endphp
                        Minggu ke-{{ $week }} ({{ $date->translatedFormat('F Y') }})
                    @else
                        {{ \Carbon\Carbon::createFromFormat('Y-m-d', $key)->translatedFormat('d F Y') }}
                    @endif
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr
                                class="bg-gray-100 dark:bg-gray-700 text-left text-gray-600 dark:text-gray-200 uppercase text-sm leading-normal">
                                <th class="w-12 py-3 px-4 text-left">No</th>
                                <th class="py-3 px-6 text-left">Tanggal</th>
                                <th class="py-3 px-6 text-left">Proyek</th>
                                <th class="py-3 px-6 text-left">Keterangan</th>
                                <th class="py-3 px-6 text-left">Tipe</th>
                                <th class="py-3 px-6 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 dark:text-gray-300 text-sm font-light">
                            @foreach ($items as $item)
                                <tr
                                    class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="w-12 py-3 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                    </td>
                                    <td class="py-3 px-6 text-left">{{ $item->nama_proyek }}</td>
                                    <td class="py-3 px-6 text-left">{{ $item->deskripsi }}</td>
                                    <td class="py-3 px-6 text-left">
                                        @if ($item->tipe == 'pemasukan')
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-200 text-green-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4" />
                                                </svg>
                                                Pemasukan
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-200 text-red-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M20 12H4" />
                                                </svg>
                                                Pengeluaran
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-right font-bold">
                                        Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach

                            @php
                                $totalMasuk = $items->where('tipe', 'pemasukan')->sum('jumlah');
                                $totalKeluar = $items->where('tipe', 'pengeluaran')->sum('jumlah');
                            @endphp

                            <tr class="bg-gray-50 dark:bg-gray-900 font-bold">
                                <td colspan="5" class="py-3 px-6 text-right">Total Pemasukan</td>
                                <td class="py-3 px-6 text-right text-green-600">
                                    Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr class="bg-gray-50 dark:bg-gray-900 font-bold">
                                <td colspan="5" class="py-3 px-6 text-right">Total Pengeluaran</td>
                                <td class="py-3 px-6 text-right text-red-600">
                                    Rp {{ number_format($totalKeluar, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>



            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-colors">
                <p class="text-gray-500 dark:text-gray-300 text-center text-lg">
                    Tidak ada transaksi pada periode yang dipilih
                </p>
            </div>
        @endforelse


        <div id="itemModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div
                class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 transition-colors">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modalTitle"></h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500 dark:text-gray-300" id="modalDate"></p>
                        <p class="text-sm text-gray-500 dark:text-gray-300" id="modalType"></p>
                        <p class="text-sm text-gray-500 dark:text-gray-300" id="modalAmount"></p>
                        <p class="text-sm text-gray-500 dark:text-gray-300" id="modalDescription"></p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button id="modalClose"
                            class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endsection


    <script>
        document.getElementById('periode').addEventListener('change', function() {
            const bulanField = document.getElementById('bulanField');
            if (this.value === 'bulan') {
                bulanField.style.display = 'none';
            } else {
                bulanField.style.display = 'block';
            }
        });
    </script>

    @push('styles')
        <style>
            /* CSS vars untuk warna chart & teks, disesuaikan lewat prefers-color-scheme */
            :root {
                --chart-income: rgba(52, 211, 153, 0.85);
                --chart-income-border: rgba(52, 211, 153, 1);
                --chart-expense: rgba(239, 68, 68, 0.85);
                --chart-expense-border: rgba(239, 68, 68, 1);
                --text-muted: #6b7280;
            }

            @media (prefers-color-scheme: dark) {
                :root {
                    --chart-income: rgba(34, 197, 94, 0.85);
                    --chart-income-border: rgba(34, 197, 94, 1);
                    --chart-expense: rgba(239, 68, 68, 0.85);
                    --chart-expense-border: rgba(239, 68, 68, 1);
                    --text-muted: #9ca3af;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js"></script>

        <script>
            function initChart() {
                if (typeof Chart === 'undefined') {
                    console.log('Chart.js belum ready, coba lagi dalam 100ms...');
                    setTimeout(initChart, 100);
                    return;
                }

                console.log('✅ Chart.js berhasil dimuat!');

                const rawGrouped = @json($grouped);
                const periode = @json($periode);
                const tahun = @json($tahun);
                const bulan = @json($bulan);

                console.log('Data dari server:', rawGrouped);

                const canvas = document.getElementById('financialChart');
                if (!canvas) {
                    console.error('❌ Canvas tidak ditemukan!');
                    return;
                }

                const labelsRaw = Object.keys(rawGrouped || {});

                if (labelsRaw.length === 0) {
                    console.log('⚠️ Tidak ada data untuk ditampilkan');
                    canvas.parentElement.innerHTML +=
                        '<p class="text-gray-500 text-center mt-4">Tidak ada data untuk periode ini</p>';
                    return;
                }

                // --- Label Builder ---
                const labels = labelsRaw.map(l => {
                    if (periode === 'hari') {
                        // l = "2025-08-17"
                        return new Date(l).toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                    } else if (periode === 'minggu') {
                        // l = "2025-08-W2"
                        const [yearMonth, week] = l.split('-W');
                        const date = new Date(yearMonth + "-01");
                        return `Minggu ke-${week} (${date.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' })})`;
                    } else {
                        // l = "2025-08"
                        const date = new Date(l + "-01");
                        return date.toLocaleDateString('id-ID', {
                            month: 'long',
                            year: 'numeric'
                        });
                    }
                });


                // --- Helper hitung jumlah ---
                function sumByType(key, tipe) {
                    const items = rawGrouped[key] || [];
                    const itemsArray = Array.isArray(items) ? items : Object.values(items);
                    return itemsArray
                        .filter(item => item.tipe === tipe)
                        .reduce((sum, item) => sum + Number(item.jumlah || 0), 0);
                }

                const incomeData = labelsRaw.map(key => sumByType(key, 'pemasukan'));
                const expenseData = labelsRaw.map(key => sumByType(key, 'pengeluaran'));

                console.log('Income:', incomeData);
                console.log('Expense:', expenseData);

                try {
                    const ctx = canvas.getContext('2d');

                    const gradient1 = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient1.addColorStop(0, 'rgba(34, 197, 94, 0.3)');
                    gradient1.addColorStop(1, 'rgba(34, 197, 94, 0.05)');

                    const gradient2 = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient2.addColorStop(0, 'rgba(239, 68, 68, 0.3)');
                    gradient2.addColorStop(1, 'rgba(239, 68, 68, 0.05)');

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Pemasukan',
                                data: incomeData,
                                backgroundColor: gradient1,
                                borderColor: 'rgba(34, 197, 94, 1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 6,
                                pointHoverRadius: 8,
                                pointBackgroundColor: 'rgba(34, 197, 94, 1)',
                                pointBorderColor: 'white',
                                pointBorderWidth: 2,
                                pointHoverBackgroundColor: 'white',
                                pointHoverBorderColor: 'rgba(34, 197, 94, 1)',
                                pointHoverBorderWidth: 3
                            }, {
                                label: 'Pengeluaran',
                                data: expenseData,
                                backgroundColor: gradient2,
                                borderColor: 'rgba(239, 68, 68, 1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 6,
                                pointHoverRadius: 8,
                                pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                                pointBorderColor: 'white',
                                pointBorderWidth: 2,
                                pointHoverBackgroundColor: 'white',
                                pointHoverBorderColor: 'rgba(239, 68, 68, 1)',
                                pointHoverBorderWidth: 3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Laporan Keuangan Proyek',
                                    font: {
                                        size: 18,
                                        weight: 'bold'
                                    },
                                    color: '#374151',
                                    padding: {
                                        top: 10,
                                        bottom: 30
                                    }
                                },
                                legend: {
                                    position: 'top',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 20,
                                        font: {
                                            size: 14,
                                            weight: '500'
                                        }
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                                    titleColor: '#374151',
                                    bodyColor: '#374151',
                                    borderColor: '#e5e7eb',
                                    borderWidth: 1,
                                    cornerRadius: 8,
                                    displayColors: true,
                                    callbacks: {
                                        label: function(context) {
                                            return context.dataset.label + ': Rp ' +
                                                new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(156, 163, 175, 0.2)',
                                        drawBorder: false
                                    },
                                    border: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#6b7280',
                                        font: {
                                            size: 12
                                        },
                                        padding: 10,
                                        callback: function(value) {
                                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    border: {
                                        color: 'rgba(156, 163, 175, 0.3)'
                                    },
                                    ticks: {
                                        color: '#6b7280',
                                        font: {
                                            size: 12,
                                            weight: '500'
                                        },
                                        padding: 10
                                    }
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            animation: {
                                duration: 2000,
                                easing: 'easeInOutQuart'
                            },
                            elements: {
                                line: {
                                    borderJoinStyle: 'round'
                                }
                            }
                        }
                    });

                    console.log('🎉 Curve chart berhasil dibuat!');
                } catch (error) {
                    console.error('❌ Error membuat chart:', error);
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initChart);
            } else {
                initChart();
            }
        </script>
    @endpush
