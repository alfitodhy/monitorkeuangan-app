@extends('layouts.app')

@section('title', 'Rekap Laporan Proyek')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold mb-8 text-gray-800 dark:text-gray-200 border-b-2 border-gray-400 pb-2 flex items-center">
        <i class="fas fa-chart-bar text-gray-600 dark:text-gray-400 mr-3"></i> Laporan Proyek
    </h1>

    {{-- Filter Proyek --}}
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 mb-8 border border-gray-200 dark:border-gray-700">
        <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-col sm:flex-row gap-4 items-center">
            <div class="flex-grow w-full">
                <label for="id_proyek" class="sr-only">Pilih Proyek</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-clipboard-list text-gray-400"></i>
                    </div>
                    <select name="id_proyek" id="id_proyek" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">-- Pilih Proyek --</option>
                        @foreach ($proyekList as $proyek)
                            <option value="{{ $proyek->id_proyek }}" {{ request('id_proyek') == $proyek->id_proyek ? 'selected' : '' }}>
                                {{ $proyek->nama_proyek }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 ease-in-out shadow-md">
                <i class="fas fa-search mr-2"></i> Tampilkan
            </button>
        </form>
    </div>

    @if(isset($laporan))
    {{-- Tabel Ringkasan --}}
    <div class="overflow-x-auto mb-8 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-700 dark:bg-gray-900">
                <tr class="text-white text-sm">
                    <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Nilai Proyek</th>
                    <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Adendum</th>
                    <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Estimasi HPP</th>
                    <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Real HPP</th>
                    <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Estimasi Profit</th>
                    <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Real Profit</th>
                    <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Margin HPP (%)</th>
                    <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Margin Nilai (%)</th>
                    <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Pembayaran Client</th>
                    <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Sisa Kewajiban Client</th>
                    <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Sisa Kas Proyek</th>
                    <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
                    <td class="px-4 py-4 text-gray-900 dark:text-gray-100 whitespace-nowrap">Rp {{ number_format($laporan['nilai_proyek'], 0, ',', '.') }}</td>
                    <td class="px-4 py-4 text-gray-900 dark:text-gray-100 whitespace-nowrap">Rp {{ number_format($laporan['adendum'], 0, ',', '.') }}</td>
                    <td class="px-4 py-4 text-gray-900 dark:text-gray-100 whitespace-nowrap">Rp {{ number_format($laporan['estimasi_hpp'], 0, ',', '.') }} <span class="text-xs text-gray-500 dark:text-gray-400">({{ number_format($proyek->estimasi_hpp ?? 0, 0) }}%)</span></td>
                    <td class="px-4 py-4 text-gray-900 dark:text-gray-100 whitespace-nowrap">Rp {{ number_format($laporan['real_hpp'], 0, ',', '.') }}</td>
                    <td class="px-4 py-4 text-gray-900 dark:text-gray-100 whitespace-nowrap">Rp {{ number_format($laporan['estimasi_profit'], 0, ',', '.') }}</td>
                    <td class="px-4 py-4 text-gray-900 dark:text-gray-100 whitespace-nowrap">Rp {{ number_format($laporan['real_profit'], 0, ',', '.') }}</td>
                    <td class="px-4 py-4 text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ $laporan['margin_hpp'] }}%</td>
                    <td class="px-4 py-4 text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ $laporan['margin_nilai'] }}%</td>
                    <td class="px-4 py-4 text-gray-900 dark:text-gray-100 whitespace-nowrap">Rp {{ number_format($laporan['total_pembayaran_client'], 0, ',', '.') }}</td>
                    <td class="px-4 py-4 text-gray-900 dark:text-gray-100 whitespace-nowrap">Rp {{ number_format($laporan['sisa_kewajiban_client'], 0, ',', '.') }}</td>
                    <td class="px-4 py-4 text-gray-900 dark:text-gray-100 whitespace-nowrap">Rp {{ number_format($laporan['sisa_kas'], 0, ',', '.') }}</td>
                    <td class="px-4 py-4 text-gray-900 dark:text-gray-100 whitespace-nowrap">
                        <button onclick="toggleDetail({{ $proyek->id_proyek }})" class="bg-green-600 text-white px-3 py-1 rounded-full hover:bg-green-700 transition-colors duration-200 ease-in-out shadow-md">
                            <i class="fas fa-search-plus mr-1"></i> Detail
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Detail Transaksi --}}
    <div id="detailContainer-{{ $proyek->id_proyek }}" class="hidden overflow-hidden transition-all duration-500 ease-in-out max-h-0">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                <i class="fas fa-list-ul text-blue-500 mr-2"></i> Riwayat Transaksi
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr class="text-left text-gray-600 dark:text-gray-200 uppercase text-sm leading-normal">
                            <th class="w-12 py-3 px-4 rounded-tl-lg">No</th>
                            <th class="py-3 px-6">Tanggal</th>
                            <th class="py-3 px-6">Keterangan</th>
                            <th class="py-3 px-6">Tipe</th>
                            <th class="py-3 px-6 text-right rounded-tr-lg">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 dark:text-gray-300 text-sm font-light divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($detailTransaksi as $item)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                            <td class="w-12 py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-6">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td class="py-3 px-6">{{ $item->keterangan }}</td>
                            <td class="py-3 px-6">
                                @if ($item->tipe === 'pemasukan')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-500 text-white dark:bg-green-600 shadow-sm">
                                        <i class="fas fa-plus"></i> Pemasukan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-500 text-white dark:bg-red-600 shadow-sm">
                                        <i class="fas fa-minus"></i> Pengeluaran
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-right font-bold whitespace-nowrap">Rp {{ number_format($item->jumlah,0,',','.') }}</td>
                        </tr>
                        @endforeach

                        @php
                            $totalMasuk = $detailTransaksi->where('tipe', 'pemasukan')->sum('jumlah');
                            $totalKeluar = $detailTransaksi->where('tipe', 'pengeluaran')->sum('jumlah');
                        @endphp

                        <tr class="bg-gray-50 dark:bg-gray-900 font-bold border-t-4 border-gray-400">
                            <td colspan="4" class="py-3 px-6 text-right text-gray-900 dark:text-gray-100">Total Pemasukan</td>
                            <td class="py-3 px-6 text-right text-green-600">Rp {{ number_format($totalMasuk,0,',','.') }}</td>
                        </tr>
                        <tr class="bg-gray-50 dark:bg-gray-900 font-bold">
                            <td colspan="4" class="py-3 px-6 text-right text-gray-900 dark:text-gray-100">Total Pengeluaran</td>
                            <td class="py-3 px-6 text-right text-red-600">Rp {{ number_format($totalKeluar,0,',','.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<script>
function toggleDetail(idProyek) {
    const container = document.getElementById('detailContainer-' + idProyek);
    const detailContent = container.querySelector('div');

    if (container.classList.contains('hidden')) {
        container.classList.remove('hidden');
        setTimeout(() => {
            container.style.maxHeight = detailContent.scrollHeight + 'px';
        }, 10);
    } else {
        container.style.maxHeight = '0px';
        container.addEventListener('transitionend', () => {
            container.classList.add('hidden');
        }, { once: true });
    }
}
</script>
@endpush