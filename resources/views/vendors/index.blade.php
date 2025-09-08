@extends('layouts.app')

@section('title', 'Manajemen Vendor')

@section('content')
    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-0">Data Vendor</h1>
            <a href="{{ route('vendors.create') }}" class="btn btn-primary btn-sm sm:btn-md transition-all">
                Tambah Vendor
            </a>
        </div>

        {{-- Pesan Sukses --}}
        @if (session('success'))
            <div class="alert alert-success shadow-lg mb-6 text-white">
                <div>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif


        {{-- Search --}}
        <div class="mb-4 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
            <input type="text" id="vendorSearch" placeholder="Cari vendor..."
                class="input input-bordered w-full md:w-1/3" />
            <div id="vendorInfo" class="text-sm text-gray-600 dark:text-gray-300"></div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg">
            <table id="vendorTable" class="table table-zebra w-full text-sm text-gray-700 dark:text-gray-200">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode</th>
                        <th>Nama Vendor</th>
                        <th>Alamat</th>
                        <th>Kontak</th>
                        <th>Email</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        {{-- Pagination DaisyUI --}}
        <div class="mt-4 flex justify-center">
            <ul id="vendorPagination" class="inline-flex items-center space-x-1"></ul>
        </div>
    </div>

    <script>
        document.addEventListener("click", function(e) {
            if (e.target.closest(".delete-btn")) {
                e.preventDefault();
                let form = e.target.closest("form");

                Swal.fire({
                    title: 'Yakin mau hapus vendor ini?',
                    text: "Data vendor bakal hilang permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    background: '#f9fafb',
                    color: '#111827',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-xl shadow-lg',
                        confirmButton: 'px-4 py-2 rounded-md font-semibold',
                        cancelButton: 'px-4 py-2 rounded-md font-semibold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const table = $('#vendorTable').DataTable({
                processing: false,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('vendors.datatable') }}",
                    data: function(d) {
                        d.search.value = $('#vendorSearch').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_vendor',
                        name: 'kode_vendor'
                    },
                    {
                        data: 'nama_vendor',
                        name: 'nama_vendor'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'no_telp',
                        name: 'no_telp',
                        className: 'text-left'
                    }, // Kontak tetap kiri
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                language: window.dtLangId,
                stripeClasses: ['table-zebra', 'table-zebra'],
                dom: 't',
                drawCallback: function(settings) {
                    let info = table.page.info();
                    $('#vendorInfo').html(
                        `Menampilkan ${info.start + 1} sampai ${info.end} dari ${info.recordsTotal} vendor`
                    );

                    // DaisyUI Pagination
                    const pagination = $('#vendorPagination');
                    pagination.empty();
                    const totalPages = info.pages;
                    const currentPage = info.page;

                    const pageBtn = (label, page, disabled = false, active = false) =>
                        `<li>
                    <button class="btn btn-sm ${active ? 'btn-active' : 'btn-outline'} ${disabled ? 'btn-disabled' : ''}" data-page="${page}">
                        ${label}
                    </button>
                </li>`;

                    pagination.append(pageBtn('Prev', currentPage - 1, currentPage === 0));
                    for (let i = 0; i < totalPages; i++) {
                        pagination.append(pageBtn(i + 1, i, false, i === currentPage));
                    }
                    pagination.append(pageBtn('Next', currentPage + 1, currentPage === totalPages - 1));

                    $('#vendorPagination button').off('click').on('click', function() {
                        const page = $(this).data('page');
                        if (page >= 0 && page < totalPages) table.page(page).draw('page');
                    });
                }
            });

            // Custom search input
            $('#vendorSearch').on('input', function() {
                table.search(this.value).draw();
            });
        });
    </script>

    <style>
        /* Konten kolom Kontak tetap kiri */
        #vendorTable td:nth-child(5) {
            text-align: left !important;
        }

        /* Paksa header Kontak (kolom ke-5) sort icon di kanan */
        #vendorTable th:nth-child(5) {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
        }

        /* Kolom Kode Vendor (kolom ke-2) dilebarin dikit */
        #vendorTable th:nth-child(2),
        #vendorTable td:nth-child(2) {
            min-width: 120px;
            /* bisa disesuaikan */
            white-space: nowrap;
            /* jangan pecah baris */
        }

        /* Kolom Alamat (kolom ke-4) biar wrap */
        #vendorTable th:nth-child(4),
        #vendorTable td:nth-child(4) {
            white-space: normal !important;
            /* biar bisa wrap */
            word-break: break-word;
            /* pecah kata panjang */
            max-width: 250px;
            /* kasih max lebar */
        }
    </style>
@endsection
