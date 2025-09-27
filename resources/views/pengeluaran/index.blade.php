@extends('layouts.app')

@section('title', 'Pengeluaran Proyek')

@section('content')


    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ $errors->first() }}</span>
        </div>
    @endif


    <div class="container mx-auto p-4 sm:p-6 lg:p-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-0">Data Pengeluaran Proyek</h1>
            <a href="{{ route('pengeluaran.create') }}" class="btn btn-sm btn-primary">+ Tambah</a>
        </div>

        {{-- Filter Status --}}
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <div class="flex gap-2 flex-wrap flex-1">
                <button
                    class="status-filter-btn btn btn-sm border border-gray-400 text-gray-100 hover:bg-gray-500 hover:text-white active"
                    data-status="">Semua</button>

                <button
                    class="status-filter-btn btn btn-sm border border-orange-500 text-orange-600 hover:bg-orange-500 hover:text-white"
                    data-status="Pengajuan">Pengajuan</button>

                <button
                    class="status-filter-btn btn btn-sm border border-blue-500 text-blue-600 hover:bg-blue-500 hover:text-white"
                    data-status="Sedang diproses">Sedang diproses</button>

                <button
                    class="status-filter-btn btn btn-sm border border-green-500 text-green-600 hover:bg-green-500 hover:text-white"
                    data-status="Sudah dibayar">Sudah dibayar</button>

                <button
                    class="status-filter-btn btn btn-sm border border-red-500 text-red-600 hover:bg-red-500 hover:text-white"
                    data-status='["Ditolak","Cancel"]'>Ditolak/Cancel</button>


            </div>

            <input id="pengeluaranSearch" type="text" placeholder="Cari..."
                class="input input-sm border-gray-400 ml-auto">
        </div>


        {{-- Loading Indicator --}}
        <div id="loadingIndicator" class="text-center py-4 hidden">
            <span class="loading loading-spinner loading-md"></span>
            <span class="ml-2">Memuat data...</span>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <table id="pengeluaranTable" class="table table-zebra w-full text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                            data-column="0">
                            No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                            data-column="1">
                            Proyek
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                            data-column="2">
                            Vendor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                            data-column="3">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                            data-column="4">
                            Jumlah
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody
                    class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 
              text-gray-700 dark:text-gray-100">
                </tbody>
            </table>

            {{-- Custom Pagination --}}
            <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <div id="pengeluaranInfo" class="text-sm text-gray-700 dark:text-gray-300"></div>
                    <ul id="pengeluaranPagination" class="flex gap-1"></ul>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal Proses Pengeluaran --}}
    <div id="prosesModal"
        class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md p-6">
            <h2 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Proses Pengeluaran</h2>
            <form id="prosesForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="Sedang diproses">
                <p class="text-sm text-gray-600 dark:text-gray-300">Apakah Anda yakin ingin memproses pengeluaran ini?</p>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('prosesModal')" class="btn btn-sm">Batal</button>
                    <button type="submit" class="btn btn-sm bg-orange-500 hover:bg-orange-600 text-white">Proses</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Approve Pengeluaran --}}
    <div id="approveModal"
        class="modal-overlay hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50">
        <div class="modal-content bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-sm mx-4">
            <div class="p-6">
                <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mx-auto mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">
                    Approve Pengeluaran
                </h3>
                <p class="text-sm text-center text-gray-500 dark:text-gray-400 mb-6">
                    Unggah bukti transfer untuk menyelesaikan approval.
                </p>
                <form id="approveForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                        Unggah Bukti Transfer
                    </label>
                    <input type="file" name="file_buktitf" required
                        class="block w-full text-sm text-gray-700 dark:text-gray-200 
                               file:mr-3 file:py-1.5 file:px-3 
                               file:rounded-md file:border-0 
                               file:text-xs file:font-medium 
                               file:bg-gray-100 file:text-gray-700 
                               hover:file:bg-gray-200 
                               dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600
                               mb-4 border border-gray-300 dark:border-gray-600 rounded-md p-1.5 
                               bg-white dark:bg-gray-800 
                               focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400 transition-all" />

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeApproveModal()"
                            class="px-3 py-1.5 text-xs font-medium rounded-md border border-gray-300 dark:border-gray-600 
                   bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 
                   hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-3 py-1.5 text-xs font-semibold rounded-md text-white 
                   bg-green-600 hover:bg-green-700 
                   focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1 
                   shadow-sm transition-all">
                            Approve
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Reject Pengeluaran --}}
    <div id="rejectModal"
        class="modal-overlay hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50">
        <div class="modal-content bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-sm mx-4">
            <div class="p-6">
                <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mx-auto mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">
                    Tolak Pengeluaran
                </h3>
                <p class="text-sm text-center text-gray-500 dark:text-gray-400 mb-6">
                    Mohon berikan alasan penolakan yang jelas.</p>
                <form id="rejectForm" method="POST">
                    @csrf
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                        Alasan Penolakan
                    </label>
                    <textarea name="catatan_bod" rows="3" required placeholder="Masukkan alasan penolakan..."
                        class="w-full p-2.5 border border-gray-300 dark:border-gray-600 rounded-md mb-3 
                               bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 
                               placeholder-gray-400 dark:placeholder-gray-500 
                               focus:ring-2 focus:ring-red-500 focus:border-red-500 shadow-sm text-sm transition-all resize-none"></textarea>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeRejectModal()"
                            class="px-3 py-1.5 text-xs font-medium rounded-md border border-gray-300 dark:border-gray-600 
               bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 
               hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-3 py-1.5 text-xs font-semibold rounded-md text-white 
               bg-red-600 hover:bg-red-700 
               focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 
               shadow-sm transition-all">
                            Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .status-filter-btn.active {
            @apply bg-gray-700 text-white dark:bg-gray-900 dark:text-white;
        }

        /* Modal fixes for mobile */
        .modal-overlay {
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        .modal-content {
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            transform: scale(1);
            transition: all 0.3s ease;
        }

        /* Prevent body scroll when modal is open */
        body.modal-open {
            overflow: hidden !important;
            position: fixed !important;
            width: 100% !important;
            height: 100% !important;
        }

        /* Mobile specific adjustments */
        @media (max-width: 640px) {
            .modal-content {
                max-height: 95vh !important;
                margin: 1rem;
            }

            .modal-overlay {
                padding: 1rem !important;
            }
        }

        /* Touch-friendly buttons */
        .btn-mobile {
            min-height: 44px !important;
            min-width: 44px !important;
            touch-action: manipulation;
        }
    </style>


    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const buttons = document.querySelectorAll(".status-filter-btn");

            buttons.forEach(btn => {
                btn.addEventListener("click", () => {
                    buttons.forEach(b => b.classList.remove("active"));
                    btn.classList.add("active");
                });
            });
        });
    </script>


    @push('scripts')
        <script>
            function openProsesModal(id) {
                let form = document.getElementById('prosesForm');
                form.action = `/pengeluaran/${id}/update-status`;
                document.getElementById('prosesModal').classList.remove('hidden');
            }

            function closeModal(modalId) {
                document.getElementById(modalId).classList.add('hidden');
            }

            // FIXED: Improved modal functions for mobile
            function openApproveModal(id) {
                console.log('Opening approve modal for ID:', id);

                // Set form action
                const form = document.getElementById('approveForm');
                if (form) {
                    form.action = `/pengeluaran/${id}/approve`;
                }

                // Prevent body scroll
                document.body.classList.add('modal-open');

                // Show modal
                const modal = document.getElementById('approveModal');
                if (modal) {
                    modal.classList.remove('hidden');

                    // Focus for accessibility
                    setTimeout(() => {
                        const firstInput = modal.querySelector('input[type="file"]');
                        if (firstInput) firstInput.focus();
                    }, 100);
                }
            }

            function closeApproveModal() {
                console.log('Closing approve modal');

                const modal = document.getElementById('approveModal');
                if (modal) {
                    modal.classList.add('hidden');
                }

                // Restore body scroll
                document.body.classList.remove('modal-open');

                // Reset form
                const form = document.getElementById('approveForm');
                if (form) {
                    form.reset();
                }
            }

            function openRejectModal(id) {
                console.log('Opening reject modal for ID:', id);

                // Set form action
                const form = document.getElementById('rejectForm');
                if (form) {
                    form.action = `/pengeluaran/${id}/reject`;
                }

                // Prevent body scroll
                document.body.classList.add('modal-open');

                // Show modal
                const modal = document.getElementById('rejectModal');
                if (modal) {
                    modal.classList.remove('hidden');

                    // Focus for accessibility
                    setTimeout(() => {
                        const textarea = modal.querySelector('textarea');
                        if (textarea) textarea.focus();
                    }, 100);
                }
            }

            function closeRejectModal() {
                console.log('Closing reject modal');

                const modal = document.getElementById('rejectModal');
                if (modal) {
                    modal.classList.add('hidden');
                }

                // Restore body scroll
                document.body.classList.remove('modal-open');

                // Reset form
                const form = document.getElementById('rejectForm');
                if (form) {
                    form.reset();
                }
            }

            // Close modals when clicking outside
            document.addEventListener('click', function(event) {
                if (event.target && event.target.id === 'approveModal') {
                    closeApproveModal();
                }
                if (event.target && event.target.id === 'rejectModal') {
                    closeRejectModal();
                }
            });

            // Close modals with Escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    const approveModal = document.getElementById('approveModal');
                    const rejectModal = document.getElementById('rejectModal');

                    if (approveModal && !approveModal.classList.contains('hidden')) {
                        closeApproveModal();
                    }
                    if (rejectModal && !rejectModal.classList.contains('hidden')) {
                        closeRejectModal();
                    }
                }
            });
        </script>
    @endpush


    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                document.addEventListener("click", function(e) {
                    const btn = e.target.closest(".delete-btn");
                    if (btn) {
                        e.preventDefault();
                        const form = btn.closest("form");

                        Swal.fire({
                            title: 'Apakah kamu yakin?',
                            text: "Data yang dihapus tidak bisa dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444', // merah modern
                            cancelButtonColor: '#6b7280', // abu-abu netral
                            confirmButtonText: 'Ya, Hapus',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                            customClass: {
                                popup: 'rounded-xl shadow-lg',
                                title: 'text-lg font-semibold text-gray-800',
                                confirmButton: 'px-4 py-2 rounded-lg text-sm font-medium',
                                cancelButton: 'px-4 py-2 rounded-lg text-sm font-medium'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: 'Terhapus!',
                                    text: 'Data berhasil dihapus.',
                                    icon: 'success',
                                    confirmButtonColor: '#10b981',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                setTimeout(() => form.submit(), 1500);
                            }
                        });
                    }
                });
            });
        </script>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // SweetAlert untuk Cancel
                document.addEventListener("click", function(e) {
                    const btn = e.target.closest(".cancel-btn");
                    if (btn) {
                        e.preventDefault();
                        const form = btn.closest("form");

                        Swal.fire({
                            title: 'Batalkan Pengajuan?',
                            text: "Pengeluaran ini akan dibatalkan.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, Batalkan',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                            customClass: {
                                popup: 'rounded-xl shadow-lg',
                                title: 'text-lg font-semibold text-gray-800',
                                confirmButton: 'px-4 py-2 rounded-lg text-sm font-medium',
                                cancelButton: 'px-4 py-2 rounded-lg text-sm font-medium'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: 'Dibatalkan!',
                                    text: 'Pengeluaran berhasil dibatalkan.',
                                    icon: 'success',
                                    confirmButtonColor: '#10b981',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                setTimeout(() => form.submit(), 1500);
                            }
                        });
                    }
                });
            });
        </script>
    @endpush



    {{-- Pastikan jQuery dan DataTables sudah dimuat --}}
    @push('styles')
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            .sort-indicator {
                opacity: 0.5;
                transition: opacity 0.2s;
            }

            .sorting_asc .sort-indicator::after {
                content: "↑";
                opacity: 1;
            }

            .sorting_desc .sort-indicator::after {
                content: "↓";
                opacity: 1;
            }

            th:hover .sort-indicator {
                opacity: 1;
            }

            .status-badge {
                display: inline-block;
                padding: 0.25rem 0.5rem;
                border-radius: 0.375rem;
                font-size: 0.75rem;
                font-weight: 500;
            }

            .status-pengajuan {
                background-color: #fef3c7;
                color: #92400e;
            }

            .status-diproses {
                background-color: #dbeafe;
                color: #1e40af;
            }

            .status-dibayar {
                background-color: #d1fae5;
                color: #065f46;
            }

            .status-ditolak {
                background-color: #fee2e2;
                color: #991b1b;
            }

            .status-cancel {
                background-color: #fee2e2;
                color: #991b1b;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const table = $('#pengeluaranTable').DataTable({
                    processing: false,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                        url: "{{ route('pengeluaran.data') }}",
                        data: function(d) {
                            let statusFilter = $('.status-filter-btn.btn-active').data('status') || '';
                            // kalau ada koma, ubah jadi array
                            if (statusFilter.includes(',')) {
                                d.status_filter = statusFilter.split(',');
                            } else {
                                d.status_filter = statusFilter;
                            }
                            d.custom_search = $('#pengeluaranSearch').val();
                        }
                    },


                    columns: [{
                            data: 'DT_RowIndex',
                            className: 'text-center',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama_proyek',
                            name: 'nama_proyek'
                        },
                        {
                            data: 'nama_vendor',
                            name: 'nama_vendor'
                        },
                        {
                            data: 'tanggal',
                            name: 'tanggal_pengeluaran'
                        },
                        {
                            data: 'jumlah',
                            name: 'jumlah',
                            render: data => 'Rp ' + parseFloat(data).toLocaleString('id-ID')
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false,
                            render: data => {
                                let cls = {
                                    'Pengajuan': 'status-pengajuan',
                                    'Sedang diproses': 'status-diproses',
                                    'Sudah dibayar': 'status-dibayar',
                                    'Ditolak': 'status-ditolak',
                                    'Cancel': 'status-cancel'
                                } [data] || 'bg-gray-100 text-gray-800';

                                return `<span class="status-badge ${cls}">${data}</span>`;
                            }

                        },
                        {
                            data: 'aksi',
                            className: 'text-center',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    order: [
                        [3, 'desc']
                    ],
                    language: window.dtLangId,
                    stripeClasses: ['table-zebra', 'table-zebra'],
                    dom: 'rt',
                    pageLength: 10,
                    drawCallback: function() {
                        const info = table.page.info();
                        $('#pengeluaranInfo').html(
                            `Menampilkan ${info.start + 1} sampai ${info.end} dari ${info.recordsTotal} data`
                        );

                        // Pagination custom
                        const pagination = $('#pengeluaranPagination');
                        pagination.empty();
                        const totalPages = info.pages;
                        const currentPage = info.page;
                        const maxVisible = 5; // jumlah halaman yang ditampilkan

                        const pageBtn = (label, page, disabled = false, active = false) =>
                            `<li><button class="btn btn-sm ${active ? 'btn-active' : 'btn-outline'} ${disabled ? 'btn-disabled' : ''}" data-page="${page}">${label}</button></li>`;

                        // Prev
                        pagination.append(pageBtn('Prev', currentPage - 1, currentPage === 0));

                        let start = Math.max(0, currentPage - Math.floor(maxVisible / 2));
                        let end = start + maxVisible - 1;

                        if (end >= totalPages) {
                            end = totalPages - 1;
                            start = Math.max(0, end - maxVisible + 1);
                        }

                        // Jika ada halaman sebelum start, tampilkan ...
                        if (start > 0) {
                            pagination.append(pageBtn(1, 0, false, currentPage === 0));
                            if (start > 1) pagination.append(
                                `<li><span class="btn btn-sm btn-disabled">...</span></li>`);
                        }

                        // Halaman tengah
                        for (let i = start; i <= end; i++) {
                            pagination.append(pageBtn(i + 1, i, false, i === currentPage));
                        }

                        // Jika ada halaman setelah end, tampilkan ...
                        if (end < totalPages - 1) {
                            if (end < totalPages - 2) pagination.append(
                                `<li><span class="btn btn-sm btn-disabled">...</span></li>`);
                            pagination.append(pageBtn(totalPages, totalPages - 1, false, currentPage ===
                                totalPages - 1));
                        }

                        // Next
                        pagination.append(pageBtn('Next', currentPage + 1, currentPage === totalPages - 1));

                        $('#pengeluaranPagination button').off('click').on('click', function() {
                            const page = $(this).data('page');
                            if (page >= 0 && page < totalPages) table.page(page).draw('page');
                        });
                    }

                });

                // Filter status
                $('.status-filter-btn').on('click', function() {
                    $('.status-filter-btn').removeClass('btn-active').addClass('btn-outline');
                    $(this).removeClass('btn-outline').addClass('btn-active');
                    table.ajax.reload();
                });

                // Custom search
                $('#pengeluaranSearch').on('input', function() {
                    table.search(this.value).draw();
                });
            });
        </script>
    @endpush




@endsection
