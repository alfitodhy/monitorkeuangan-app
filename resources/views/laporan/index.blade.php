@extends('layouts.app')

@section('title', 'Rekap Laporan Proyek')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-0">Laporan Proyek On Going</h1>
    </div>

    {{-- Search --}}
    <div class="mb-4 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
        <input type="text" id="laporanSearch" placeholder="Cari proyek..."
            class="input input-bordered w-full md:w-1/3 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600" />
        <div id="laporanInfo" class="text-sm text-gray-600 dark:text-gray-300"></div>
    </div>

    {{-- Tabel Rekap Laporan Proyek --}}
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        <table id="laporanTable"
            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs text-gray-900 dark:text-gray-100">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                <tr class="uppercase tracking-wider text-xs">
                    <th class="px-3 py-2 text-center font-semibold">No</th>
                    <th class="px-3 py-2 text-left font-semibold">Nama Proyek</th>
                    <th class="px-3 py-2 text-right font-semibold">Nilai Total Proyek</th>
                    <th class="px-3 py-2 text-right font-semibold">Real HPP</th>
                    <th class="px-3 py-2 text-right font-semibold">Target HPP Total</th>
                    <th class="px-3 py-2 text-right font-semibold">Target HPP Termin</th>
                    <th class="px-3 py-2 text-right font-semibold">Real Profit</th>
                    <th class="px-3 py-2 text-right font-semibold">Margin Nilai (%)</th>
                    <th class="px-3 py-2 text-right font-semibold">Tanggal Deadline</th>
                    <th class="px-3 py-2 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-100"></tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 flex justify-end">
        <ul id="laporanPagination" class="inline-flex items-center space-x-1"></ul>
    </div>

    {{-- Container buat detail transaksi --}}
    <div id="detailContainer" class="hidden mt-6">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <h2
                class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                Riwayat Transaksi
            </h2>
            <div class="overflow-x-auto">
                <table id="detailTable"
                    class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-900 dark:text-gray-100">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <tr class="text-left uppercase text-sm tracking-wider">
                            <th class="w-12 py-3 px-4">No</th>
                            <th class="py-3 px-6">Tanggal</th>
                            <th class="py-3 px-6">Keterangan</th>
                            <th class="py-3 px-6">Tipe</th>
                            <th class="py-3 px-6 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-100"></tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-900 font-bold">
                        <tr>
                            <td colspan="4" class="py-3 px-6 text-right">Total Pemasukan</td>
                            <td id="totalMasuk" class="py-3 px-6 text-right text-green-600">Rp 0</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="py-3 px-6 text-right">Total Pengeluaran</td>
                            <td id="totalKeluar" class="py-3 px-6 text-right text-red-600">Rp 0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const table = $('#laporanTable').DataTable({
        processing: false,
        responsive: true,
        serverSide: true,
        paging: true,
        info: false,
        searching: false,
        ajax: {
            url: "/laporan/data",
            data: function(d) {
                d.search.value = $('#laporanSearch').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
            { data: 'nama_proyek', name: 'nama_proyek' },
            { data: 'total_nilai_proyek', name: 'total_nilai_proyek', className: 'text-left' },
            { data: 'real_hpp', name: 'real_hpp', className: 'text-left' },
            { data: 'target_hpp_total', name: 'target_hpp_total', className: 'text-left' },
            { data: 'target_hpp_termin', name: 'target_hpp_termin', className: 'text-left' },
            { data: 'real_profit', name: 'real_profit', className: 'text-left' },
            { data: 'margin_nilai', name: 'margin_nilai', className: 'text-left font-semibold' },
            { data: 'tanggal_deadline', name: 'tanggal_deadline', className: 'text-center font-semibold' },
            { data: 'aksi', className: 'text-center', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        dom: 'rt',
        drawCallback: function(settings) {
            let info = table.page.info();
            $('#laporanInfo').html(`Menampilkan ${info.start+1} sampai ${info.end} dari ${info.recordsTotal} proyek`);

            const pagination = $('#laporanPagination');
            pagination.empty();
            const totalPages = info.pages;
            const currentPage = info.page;
            const maxVisible = 5;

            const pageBtn = (label, page, disabled = false, active = false) =>
                `<li><button class="btn btn-sm ${active?'btn-active':'btn-outline'} ${disabled?'btn-disabled':''}" data-page="${page}">${label}</button></li>`;

            pagination.append(pageBtn('Prev', currentPage - 1, currentPage === 0));

            let start = Math.max(0, currentPage - Math.floor(maxVisible / 2));
            let end = start + maxVisible - 1;

            if (end >= totalPages) {
                end = totalPages - 1;
                start = Math.max(0, end - maxVisible + 1);
            }

            if (start > 0) {
                pagination.append(pageBtn(1, 0, false, currentPage === 0));
                if (start > 1) pagination.append(`<li><span class="btn btn-sm btn-disabled">...</span></li>`);
            }

            for (let i = start; i <= end; i++) {
                pagination.append(pageBtn(i + 1, i, false, i === currentPage));
            }

            if (end < totalPages - 1) {
                if (end < totalPages - 2) pagination.append(`<li><span class="btn btn-sm btn-disabled">...</span></li>`);
                pagination.append(pageBtn(totalPages, totalPages - 1, false, currentPage === totalPages - 1));
            }

            pagination.append(pageBtn('Next', currentPage + 1, currentPage === totalPages - 1));

            $('#laporanPagination button').off('click').on('click', function() {
                const page = $(this).data('page');
                if (page >= 0 && page < totalPages) table.page(page).draw('page');
            });
        }
    });

    $('#laporanSearch').on('input', function() {
        table.search(this.value).draw();
    });
});

// Toggle detail transaksi
function toggleDetail(idProyek) {
    const container = document.getElementById('detailContainer');
    container.classList.remove('hidden');

    $.ajax({
        url: "{{ url('laporan/transaksi') }}/" + idProyek,
        method: 'GET',
        success: function(res) {
            let rows = '';
            let totalMasuk = 0, totalKeluar = 0;

            res.forEach((item, index) => {
                let tipeBadge = item.tipe === 'pemasukan' ?
                    '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-500 text-white"><i class="fas fa-plus"></i> Pemasukan</span>' :
                    '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-500 text-white"><i class="fas fa-minus"></i> Pengeluaran</span>';

                rows += `
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                        <td class="w-12 py-3 px-4">${index+1}</td>
                        <td class="py-3 px-6">${item.tanggal}</td>
                        <td class="py-3 px-6">${item.keterangan}</td>
                        <td class="py-3 px-6">${tipeBadge}</td>
                        <td class="py-3 px-6 text-right font-bold">Rp ${new Intl.NumberFormat('id-ID').format(item.jumlah)}</td>
                    </tr>
                `;

                if (item.tipe === 'pemasukan') totalMasuk += item.jumlah;
                else totalKeluar += item.jumlah;
            });

            $('#detailTable tbody').html(rows);
            $('#totalMasuk').text('Rp ' + new Intl.NumberFormat('id-ID').format(totalMasuk));
            $('#totalKeluar').text('Rp ' + new Intl.NumberFormat('id-ID').format(totalKeluar));
        }
    });
}
</script>
@endpush
