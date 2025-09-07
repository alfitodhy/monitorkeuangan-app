@extends('layouts.app')

@section('title','Pemasukan Proyek')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-0">Data Pemasukan Proyek</h1>
        <a href="{{ route('pemasukan.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-1.5 px-4 rounded-md shadow-sm text-sm transition duration-300 ease-in-out">
            Tambah Pemasukan
        </a>
    </div>

    {{-- Pesan Sukses --}}
    @if(session('success'))
    <div class="bg-green-100 dark:bg-green-950 border border-green-400 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg relative mb-6">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Search --}}
    <div class="mb-4 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
        <input type="text" id="pemasukanSearch" placeholder="Cari proyek..." class="input input-bordered w-full md:w-1/3" />
        <div id="pemasukanInfo" class="text-sm text-gray-600 dark:text-gray-300"></div>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        <table id="pemasukanTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-3 py-2 text-center font-semibold text-gray-600 dark:text-gray-300 uppercase">No</th>
                    <th class="px-3 py-2 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase">Proyek</th>
                    <th class="px-3 py-2 text-right font-semibold text-gray-600 dark:text-gray-300 uppercase">Nilai Proyek</th>
                    <th class="px-3 py-2 text-right font-semibold text-gray-600 dark:text-gray-300 uppercase">Nilai Proyek Addendum</th>
                    <th class="px-3 py-2 text-right font-semibold text-gray-600 dark:text-gray-300 uppercase">Total Termin</th>
                    <th class="px-3 py-2 text-right font-semibold text-gray-600 dark:text-gray-300 uppercase">Termin Addendum</th>
                    <th class="px-3 py-2 text-right font-semibold text-gray-600 dark:text-gray-300 uppercase">Total Dibayar</th>
                    <th class="px-3 py-2 text-right font-semibold text-gray-600 dark:text-gray-300 uppercase">Sisa</th>
                    <th class="px-3 py-2 text-center font-semibold text-gray-600 dark:text-gray-300 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"></tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 flex justify-center">
        <ul id="pemasukanPagination" class="inline-flex items-center space-x-1"></ul>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const table = $('#pemasukanTable').DataTable({
        processing: false,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('pemasukan.datatable') }}",
            data: function(d){
                d.search.value = $('#pemasukanSearch').val();
            }
        },
        columns:[
            {data:'DT_RowIndex',className:'text-center',orderable:false,searchable:false},
            {data:'nama_proyek',name:'nama_proyek'},
            {data:'nilai_proyek',name:'nilai_proyek',className:'text-right',render: $.fn.dataTable.render.number('.',',',0,'Rp ')},
            {data:'nilai_proyek_addendum',name:'nilai_proyek_addendum',className:'text-right text-indigo-600',render: $.fn.dataTable.render.number('.',',',0,'Rp ')},
            {data:'termin',name:'termin',className:'text-right',render: $.fn.dataTable.render.number('.',',',0)},
            {data:'termin_addendum',name:'termin_addendum',className:'text-right text-indigo-600',render: $.fn.dataTable.render.number('.',',',0)},
            {data:'total_dibayar',name:'total_dibayar',className:'text-right text-green-600',render: $.fn.dataTable.render.number('.',',',0,'Rp ')},
            {data:'sisa_bayar',name:'sisa_bayar',className:'text-right text-red-600 font-semibold',render: $.fn.dataTable.render.number('.',',',0,'Rp ')},
            {data:'aksi',className:'text-center',orderable:false,searchable:false}
        ],
        order:[[1,'asc']],
        stripeClasses:['table-zebra','table-zebra'],
        dom:'t',
        drawCallback: function(settings){
            let info = table.page.info();
            $('#pemasukanInfo').html(`Menampilkan ${info.start+1} sampai ${info.end} dari ${info.recordsTotal} proyek`);

            const pagination = $('#pemasukanPagination');
            pagination.empty();
            const totalPages = info.pages;
            const currentPage = info.page;

            const pageBtn = (label,page,disabled=false,active=false) =>
                `<li><button class="btn btn-sm ${active?'btn-active':'btn-outline'} ${disabled?'btn-disabled':''}" data-page="${page}">${label}</button></li>`;

            pagination.append(pageBtn('Prev',currentPage-1,currentPage===0));
            for(let i=0;i<totalPages;i++){
                pagination.append(pageBtn(i+1,i,false,i===currentPage));
            }
            pagination.append(pageBtn('Next',currentPage+1,currentPage===totalPages-1));

            $('#pemasukanPagination button').off('click').on('click',function(){
                const page = $(this).data('page');
                if(page>=0 && page<totalPages) table.page(page).draw('page');
            });
        }
    });

    // Custom search input
    $('#pemasukanSearch').on('input',function(){
        table.search(this.value).draw();
    });
});
</script>
@endsection
