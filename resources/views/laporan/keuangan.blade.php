@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans transition-colors duration-300 ease-in-out">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 dark:text-gray-100">
                Laporan Keuangan Proyek
            </h1>
            <p class="text-sm sm:text-base text-gray-500 dark:text-gray-300 mt-2">Ringkasan & analisis pemasukan dan pengeluaran</p>
        </div>
    </div>

    {{-- Form Filter --}}
    <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 mb-8 transition-colors duration-300 ease-in-out">
        <h2 class="text-xl font-bold text-gray-700 dark:text-gray-100 mb-6 flex items-center">
            <i class="fas fa-filter text-gray-500 dark:text-gray-400 mr-2"></i>Filter Laporan
        </h2>
        <form method="GET" action="{{ route('laporan.keuangan') }}" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tgl_mulai" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Tanggal Mulai
                    </label>
                    <input type="date" name="tgl_mulai" id="tgl_mulai" value="{{ $tgl_mulai?->format('Y-m-d') ?? '' }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400">
                </div>
                <div>
                    <label for="tgl_akhir" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Tanggal Akhir
                    </label>
                    <input type="date" name="tgl_akhir" id="tgl_akhir" value="{{ $tgl_akhir?->format('Y-m-d') ?? '' }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="periode" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Tampilkan Per Periode
                    </label>
                    <div class="relative">
                        <select name="periode" id="periode"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm appearance-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                            <option value="minggu" {{ ($periode ?? '') == 'minggu' ? 'selected' : '' }}>Mingguan</option>
                            <option value="bulan" {{ ($periode ?? '') == 'bulan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="hari" {{ ($periode ?? '') == 'hari' ? 'selected' : '' }}>Harian</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="jenis" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Jenis Transaksi
                    </label>
                    <div class="relative">
                        <select name="jenis" id="jenis"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm appearance-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                            <option value="semua" {{ ($jenis ?? '') == 'semua' ? 'selected' : '' }}>Semua Jenis</option>
                            <option value="pemasukan" {{ ($jenis ?? '') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="pengeluaran" {{ ($jenis ?? '') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-4 flex justify-end">
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition duration-300 flex items-center justify-center text-sm">
                    <i class="fas fa-search-dollar mr-2"></i> Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>

    @php
        $totalMasuk = $grouped->flatten()->where('tipe', 'pemasukan')->sum('jumlah');
        $totalKeluar = $grouped->flatten()->where('tipe', 'pengeluaran')->sum('jumlah');
    @endphp

    {{-- Ringkasan --}}
    <div class="flex flex-wrap -mx-3 mb-8">
        <div class="w-full sm:w-1/2 px-3 mb-4">
            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-md flex items-center space-x-4 border-l-4 border-green-500 transition-colors duration-300 ease-in-out">
                <div class="flex-shrink-0">
                    <div class="bg-green-100 dark:bg-green-700 p-2 rounded-full">
                        <i class="fas fa-wallet text-green-600 dark:text-green-300 text-xl"></i>
                    </div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-300">Total Pemasukan</div>
                    <div class="text-xl font-bold text-green-700 dark:text-green-300 mt-1">
                        Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full sm:w-1/2 px-3 mb-4">
            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-md flex items-center space-x-4 border-l-4 border-red-500 transition-colors duration-300 ease-in-out">
                <div class="flex-shrink-0">
                    <div class="bg-red-100 dark:bg-red-700 p-2 rounded-full">
                        <i class="fas fa-receipt text-red-600 dark:text-red-300 text-xl"></i>
                    </div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-300">Total Pengeluaran</div>
                    <div class="text-xl font-bold text-red-700 dark:text-red-300 mt-1">
                        Rp {{ number_format($totalKeluar, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-xl mb-8 transition-colors duration-300 ease-in-out">
        <h2 class="text-xl font-bold text-gray-700 dark:text-gray-100 mb-6 flex items-center">
            <i class="fas fa-chart-line text-blue-500 mr-2"></i> Grafik Arus Keuangan
        </h2>
        <div style="height: 400px;">
            <canvas id="financialChart"></canvas>
        </div>
    </div>

    {{-- Tabel per periode --}}
    @forelse($grouped as $key => $items)
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mb-6 transition-colors duration-300 ease-in-out">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
            <i class="fas fa-calendar-alt text-gray-500 dark:text-gray-400 mr-2"></i>
            @if ($periode === 'bulan')
                {{ \Carbon\Carbon::createFromFormat('Y-m', $key)->translatedFormat('F Y') }}
            @elseif($periode === 'minggu')
                @php
                    [$year, $week] = explode('-W', $key);
                    $date = \Carbon\Carbon::createFromFormat('Y-m', $year);
                @endphp
                Minggu ke-{{ $week }} ({{ $date->translatedFormat('F Y') }})
            @else
                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $key)->translatedFormat('d F Y') }}
            @endif
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr class="text-left text-gray-600 dark:text-gray-200 uppercase text-sm leading-normal">
                        <th class="py-3 px-4 rounded-tl-lg w-12">No</th>
                        <th class="py-3 px-6">Tanggal</th>
                        <th class="py-3 px-6">Proyek</th>
                        <th class="py-3 px-6">Keterangan</th>
                        <th class="py-3 px-6">Tipe</th>
                        <th class="py-3 px-6 text-right rounded-tr-lg">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 dark:text-gray-300 text-sm font-light divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($items as $item)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-6">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td class="py-3 px-6 font-semibold text-gray-900 dark:text-gray-100">{{ $item->nama_proyek }}</td>
                            <td class="py-3 px-6">{{ $item->deskripsi }}</td>
                            <td class="py-3 px-6">
                                @if ($item->tipe == 'pemasukan')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-500 text-white shadow-sm">
                                        <i class="fas fa-plus-circle"></i> Pemasukan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-500 text-white shadow-sm">
                                        <i class="fas fa-minus-circle"></i> Pengeluaran
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-right font-bold {{ $item->tipe == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xl transition-colors duration-300 ease-in-out">
            <p class="text-gray-500 dark:text-gray-300 text-center text-lg py-10">
                <i class="fas fa-info-circle mr-2"></i> Tidak ada transaksi pada periode yang dipilih.
            </p>
        </div>
    @endforelse
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-i2gE5QfD1rVvT0yO4p2FmF/hF+6jT5L3N5s2d5Fj3k2p5s5j3d6m8C6qWJp3hF4pL2v5m8M0N7vC2g8L5yN3s4A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    :root {
        --chart-income: #10b981;
        --chart-expense: #ef4444;
        --text-color: #374151;
        --grid-color: rgba(156, 163, 175, 0.2);
    }

    @media (prefers-color-scheme: dark) {
        :root {
            --chart-income: #34d399;
            --chart-expense: #ef4444;
            --text-color: #f9fafb;
            --grid-color: rgba(156, 163, 175, 0.2);
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js"></script>
<script>
    function initChart() {
        if (typeof Chart === 'undefined') {
            setTimeout(initChart, 100);
            return;
        }

        const rawGrouped = @json($grouped);
        const periode = @json($periode);

        const canvas = document.getElementById('financialChart');
        if (!canvas) return;

        const labelsRaw = Object.keys(rawGrouped || {});
        if (labelsRaw.length === 0) {
            canvas.parentElement.innerHTML +=
                '<p class="text-gray-500 dark:text-gray-300 text-center mt-4">Tidak ada data untuk periode ini</p>';
            return;
        }

        const labels = labelsRaw.map(l => {
            if (periode === 'hari') {
                return new Date(l).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            } else if (periode === 'minggu') {
                const [year, week] = l.split('-W');
                return `Minggu ke-${week} (${new Date(`${year}-01-01`).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' })})`;
            } else {
                return new Date(l + "-01").toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
            }
        });

        const sumByType = (key, tipe) => {
            const items = rawGrouped[key] || [];
            const arr = Array.isArray(items) ? items : Object.values(items);
            return arr.filter(i => i.tipe === tipe).reduce((s, i) => s + Number(i.jumlah || 0), 0);
        };

        const incomeData = labelsRaw.map(k => sumByType(k, 'pemasukan'));
        const expenseData = labelsRaw.map(k => sumByType(k, 'pengeluaran'));

        const ctx = canvas.getContext('2d');
        const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        const createGradient = (color) => {
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, `${color}A0`);
            gradient.addColorStop(1, `${color}10`);
            return gradient;
        };

        const incomeGradient = createGradient(isDark ? '#34d399' : '#10b981');
        const expenseGradient = createGradient('#ef4444');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pemasukan',
                    data: incomeData,
                    backgroundColor: incomeGradient,
                    borderColor: isDark ? '#34d399' : '#10b981',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: isDark ? '#34d399' : '#10b981',
                    pointBorderColor: 'white',
                    pointBorderWidth: 2
                }, {
                    label: 'Pengeluaran',
                    data: expenseData,
                    backgroundColor: expenseGradient,
                    borderColor: '#ef4444',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: 'white',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: false,
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 14,
                                weight: '500'
                            },
                            color: isDark ? '#f9fafb' : '#374151'
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: isDark ? 'rgba(55,65,81,0.95)' : 'rgba(255,255,255,0.95)',
                        titleColor: isDark ? '#f9fafb' : '#374151',
                        bodyColor: isDark ? '#f9fafb' : '#374151',
                        borderColor: isDark ? '#4b5563' : '#e5e7eb',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(ctx) {
                                return ctx.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(ctx.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: isDark ? 'rgba(156,163,175,0.1)' : 'rgba(156,163,175,0.2)',
                            drawBorder: false
                        },
                        ticks: {
                            color: isDark ? '#9ca3af' : '#6b7280',
                            callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: isDark ? '#9ca3af' : '#6b7280'
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initChart);
</script>
@endpush