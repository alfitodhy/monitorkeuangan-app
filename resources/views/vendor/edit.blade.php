@extends('layouts.app')

@section('title', 'Edit Vendor')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-0">Edit Vendor</h1>
        <a href="{{ route('vendor.index') }}"
            class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-1.5 px-4 rounded-md shadow-sm text-sm transition duration-300 ease-in-out">
            Kembali
        </a>
    </div>

    {{-- Pesan Error --}}
    @if ($errors->any())
        <div class="bg-red-100 dark:bg-red-950 border border-red-400 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg relative mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <form action="{{ route('vendor.update', $vendor->id_vendor) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Grid untuk input utama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                {{-- Nama Vendor --}}
                <div>
                    <label for="nama_vendor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nama Vendor <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_vendor" id="nama_vendor"
                        class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('nama_vendor', $vendor->nama_vendor) }}" required>
                </div>

                {{-- No. Telepon --}}
                <div>
                    <label for="no_telp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        No. Telepon
                    </label>
                    <input type="text" name="no_telp" id="no_telp"
                        class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('no_telp', $vendor->no_telp) }}">
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Email
                    </label>
                    <input type="email" name="email" id="email"
                        class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('email', $vendor->email) }}">
                </div>

                {{-- Jenis Vendor --}}
                <div>
                    <label for="jenis_vendor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Jenis Vendor
                    </label>
                  <select name="jenis_vendor" id="jenis_vendor"
    class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
    <option value="">-- Pilih Jenis Vendor --</option>
    <option value="Tukang" {{ strtolower(trim(old('jenis_vendor', $vendor->jenis_vendor))) == 'tukang' ? 'selected' : '' }}>Tukang</option>
    <option value="Material" {{ strtolower(trim(old('jenis_vendor', $vendor->jenis_vendor))) == 'material' ? 'selected' : '' }}>Material</option>
    <option value="Jasa dan Material" {{ strtolower(trim(old('jenis_vendor', $vendor->jenis_vendor))) == 'jasa dan material' ? 'selected' : '' }}>Jasa dan Material</option>
</select>
                </div>

                {{-- Spesialisasi --}}
                <div>
                    <label for="spesialisasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Spesialisasi
                    </label>
                    <input
                        type="text"
                        name="spesialisasi"
                        id="spesialisasi"
                        list="spesialisasi_options"
                        class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('spesialisasi', $vendor->spesialisasi) }}"
                    >
                    <datalist id="spesialisasi_options">
                        @foreach($spesialisasi_vendor as $spesialisasi)
                            <option value="{{ $spesialisasi }}">
                        @endforeach
                    </datalist>
                </div>
                
                {{-- Alamat --}}
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Alamat
                    </label>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">{{ old('alamat', $vendor->alamat) }}</textarea>
                </div>
            </div>

            {{-- Rekening (JSON) --}}
<div class="mt-4">
    <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
            Rekening Bank
        </h3>
        <button type="button" id="add-rekening" class="px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md shadow-sm inline-flex items-center space-x-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah</span>
        </button>
    </div>

    <div id="rekening-wrapper" class="space-y-4">
        @php
            $rekening_list = old('rekening', $vendor->rekening ?? []);
            if (is_string($rekening_list)) {
                $rekening_list = json_decode($rekening_list, true);
            }
            if (!is_array($rekening_list) || empty($rekening_list)) {
                $rekening_list = [[]];
            }
        @endphp

        @foreach ($rekening_list as $key => $rekening)
            <div class="rekening-group p-4 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm relative">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Rekening {{ $key + 1 }}</h4>
                    @if ($key > 0)
                        <button type="button" class="remove-rekening text-gray-400 hover:text-red-600 transition-colors duration-200" title="Hapus rekening">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Atas Nama</label>
                        <input type="text" name="rekening[{{ $key }}][atas_nama]" class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" value="{{ $rekening['atas_nama'] ?? '' }}">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Bank</label>
                        <input type="text" name="rekening[{{ $key }}][nama_bank]" list="bank_options" class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" value="{{ $rekening['nama_bank'] ?? '' }}">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">No Rekening</label>
                        <input type="text" name="rekening[{{ $key }}][no_rekening]" class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" value="{{ $rekening['no_rekening'] ?? '' }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<datalist id="bank_options">
    @foreach($nama_bank as $bank)
        <option value="{{ $bank }}">
    @endforeach
</datalist>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const addButton = document.getElementById('add-rekening');
    const wrapper = document.getElementById('rekening-wrapper');

    let rekeningIndex = wrapper.querySelectorAll('.rekening-group').length;

    const createNewRekeningGroup = (index) => {
        const newRekeningGroup = document.createElement('div');
        newRekeningGroup.className = 'rekening-group p-4 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm relative mt-4';
        newRekeningGroup.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Rekening ${index + 1}</h4>
                <button type="button" class="remove-rekening text-gray-400 hover:text-red-600 transition-colors duration-200" title="Hapus rekening">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Atas Nama</label>
                    <input type="text" name="rekening[${index}][atas_nama]" class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Bank</label>
                    <input type="text" name="rekening[${index}][nama_bank]" list="bank_options" class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">No Rekening</label>
                    <input type="text" name="rekening[${index}][no_rekening]" class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
        `;
        return newRekeningGroup;
    };

    addButton.addEventListener('click', function () {
        const newGroup = createNewRekeningGroup(rekeningIndex);
        wrapper.appendChild(newGroup);
        rekeningIndex++;
        reindexForms();
    });

    wrapper.addEventListener('click', function (e) {
        if (e.target.closest('.remove-rekening')) {
            const rekeningGroup = e.target.closest('.rekening-group');
            if (rekeningGroup) {
                rekeningGroup.remove();
                reindexForms();
            }
        }
    });

    function reindexForms() {
        const rekeningGroups = wrapper.querySelectorAll('.rekening-group');
        rekeningGroups.forEach((group, index) => {
            group.querySelectorAll('input').forEach(input => {
                input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
            });
            const h4 = group.querySelector('h4');
            if (h4) {
                h4.textContent = `Rekening ${index + 1}`;
            }
            const removeButton = group.querySelector('.remove-rekening');
            if (removeButton) {
                if (index === 0) {
                    removeButton.style.display = 'none';
                } else {
                    removeButton.style.display = 'block';
                }
            }
        });
        rekeningIndex = rekeningGroups.length;
    }

    reindexForms();
});
</script>

            {{-- Keterangan --}}
            <div class="mt-4">
                <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Keterangan
                </label>
                <textarea name="keterangan" id="keterangan" rows="3"
                    class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">{{ old('keterangan', $vendor->keterangan) }}</textarea>
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end space-x-2 mt-6">
                <a href="{{ route('vendor.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white py-1.5 px-4 rounded-md shadow-sm text-sm">
                    Batal
                </a>
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white py-1.5 px-4 rounded-md shadow-sm text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>



@endsection