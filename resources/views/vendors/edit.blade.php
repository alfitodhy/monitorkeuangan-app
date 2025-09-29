@extends('layouts.app')

@section('title', 'Edit Vendor')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
                <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                    <div class="p-1.5 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 dark:text-indigo-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Edit Vendor</h1>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Perbarui informasi vendor</p>
                    </div>
                </div>
                <a href="{{ route('vendors.index') }}"
                    class="inline-flex items-center px-3 py-1.5 bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-medium rounded-lg text-xs shadow-sm transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>

            {{-- Alert Error --}}
            @if ($errors->any())
                <div
                    class="mb-6 p-3 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-lg text-sm">
                    <h3 class="font-semibold text-red-700 dark:text-red-300 mb-1">Terdapat beberapa kesalahan:</h3>
                    <ul class="list-disc pl-5 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li class="text-red-600 dark:text-red-300">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                <form action="{{ route('vendors.update', $vendor->id_vendor) }}" method="POST" class="p-4">
                    @csrf
                    @method('PUT')

                    {{-- Data Vendor --}}
                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama Vendor --}}
                            <div class="space-y-1">
                                <label for="nama_vendor" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                    Nama Vendor <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_vendor" id="nama_vendor"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md 
                       bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-white 
                       placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('nama_vendor', $vendor->nama_vendor) }}"
                                    placeholder="Masukkan nama vendor" required>
                            </div>

                            {{-- Jenis Vendor --}}
                            <div class="space-y-1">
                                <label for="jenis_vendor"
                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                    Jenis Vendor
                                </label>
                                <select name="jenis_vendor" id="jenis_vendor"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md 
                       bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-white 
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">-- Pilih Jenis Vendor --</option>
                                    <option value="Tukang"
                                        {{ strtolower(trim(old('jenis_vendor', $vendor->jenis_vendor))) == 'tukang' ? 'selected' : '' }}>
                                        Tukang</option>
                                    <option value="Material"
                                        {{ strtolower(trim(old('jenis_vendor', $vendor->jenis_vendor))) == 'material' ? 'selected' : '' }}>
                                        Material</option>
                                    <option value="Jasa dan Material"
                                        {{ strtolower(trim(old('jenis_vendor', $vendor->jenis_vendor))) == 'jasa dan material' ? 'selected' : '' }}>
                                        Jasa dan Material</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Kontak --}}
                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- No. Telepon --}}
                            <div class="space-y-1">
                                <label for="no_telp" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                    No. Telepon
                                </label>
                                <input type="text" name="no_telp" id="no_telp"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md 
                       bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-white 
                       placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('no_telp', $vendor->no_telp) }}" placeholder="Contoh: 08123456789">
                            </div>

                            {{-- Email --}}
                            <div class="space-y-1">
                                <label for="email" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                    Email
                                </label>
                                <input type="email" name="email" id="email"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md 
                       bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-white 
                       placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('email', $vendor->email) }}" placeholder="vendor@example.com">
                            </div>
                        </div>
                    </div>

                    {{-- Spesialisasi & Alamat --}}
                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Spesialisasi --}}
                            <div class="space-y-1">
                                <label for="spesialisasi"
                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                    Spesialisasi
                                </label>
                                <input type="text" name="spesialisasi" id="spesialisasi" list="spesialisasi_options"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md 
                       bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-white 
                       placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('spesialisasi', $vendor->spesialisasi) }}"
                                    placeholder="Contoh: Plumbing, Elektrik, dll">
                                <datalist id="spesialisasi_options">
                                    @foreach ($spesialisasi_vendor as $spesialisasi)
                                        <option value="{{ $spesialisasi }}">
                                    @endforeach
                                </datalist>
                            </div>

                            {{-- Alamat --}}
                            <div class="space-y-1">
                                <label for="alamat" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                    Alamat
                                </label>
                                <textarea name="alamat" id="alamat" rows="3"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md 
                       bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-white 
                       placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                                    placeholder="Alamat lengkap vendor">{{ old('alamat', $vendor->alamat) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Lokasi (row baru) --}}
                    <div class="mb-6">
                        <div class="space-y-1">
                            <label for="lokasi" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                Lokasi
                            </label>
                            <input type="text" name="lokasi" id="lokasi"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md 
                   bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-white 
                   placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                value="{{ old('lokasi', $vendor->lokasi) }}">
                        </div>
                    </div>



                    {{-- Rekening Bank --}}
                    <div class="mb-6">
                        <div
                            class="flex items-center justify-between mb-3 pb-1 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                                Rekening Bank
                            </h2>
                            <button type="button" id="add-rekening"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-md text-xs shadow-sm transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah
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
                                <div
                                    class="rekening-group p-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm text-sm transition-all duration-200 hover:shadow-md">

                                    <div class="flex items-center justify-between mb-3">
                                        <h3
                                            class="text-sm font-semibold text-gray-800 dark:text-gray-200 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                            Rekening {{ $key + 1 }}
                                        </h3>

                                        @if ($key > 0)
                                            <button type="button"
                                                class="remove-rekening p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-all duration-200"
                                                title="Hapus rekening">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        {{-- Atas Nama --}}
                                        <div class="space-y-1">
                                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                                                Atas Nama
                                            </label>
                                            <input type="text" name="rekening[{{ $key }}][atas_nama]"
                                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-900 text-sm text-gray-800 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                value="{{ $rekening['atas_nama'] ?? '' }}"
                                                placeholder="Nama pemilik rekening">
                                        </div>

                                        {{-- Nama Bank --}}
                                        <div class="space-y-1">
                                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                                                Nama Bank
                                            </label>
                                            <input type="text" name="rekening[{{ $key }}][nama_bank]"
                                                list="bank_options"
                                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-900 text-sm text-gray-800 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                value="{{ $rekening['nama_bank'] ?? '' }}"
                                                placeholder="Contoh: BCA, Mandiri">
                                        </div>

                                        {{-- No Rekening --}}
                                        <div class="space-y-1">
                                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                                                No Rekening
                                            </label>
                                            <input type="text" name="rekening[{{ $key }}][no_rekening]"
                                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-900 text-sm text-gray-800 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                value="{{ $rekening['no_rekening'] ?? '' }}"
                                                placeholder="Nomor rekening">
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                        </div>
                    </div>

                    <datalist id="bank_options">
                        @foreach ($nama_bank as $bank)
                            <option value="{{ $bank }}">
                        @endforeach
                    </datalist>

                    {{-- Keterangan --}}
                    <div class="mb-6">
                        <div class="space-y-1">
                            <label for="keterangan" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                Keterangan
                            </label>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                                placeholder="Catatan tambahan">{{ old('keterangan', $vendor->keterangan) }}</textarea>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div
                        class="flex flex-col sm:flex-row justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('vendors.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-medium rounded-md text-xs shadow-sm transition-all duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-md text-xs shadow-sm transition-all duration-200">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addButton = document.getElementById('add-rekening');
            const wrapper = document.getElementById('rekening-wrapper');

            let rekeningIndex = wrapper.querySelectorAll('.rekening-group').length;

            const createNewRekeningGroup = (index) => {
                const newRekeningGroup = document.createElement('div');
                newRekeningGroup.className =
                    'rekening-group p-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm text-sm transition-all duration-200 hover:shadow-md';

                newRekeningGroup.innerHTML = `
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            Rekening ${index + 1}
        </h3>
        <button type="button" 
            class="remove-rekening p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-all duration-200" 
            title="Hapus rekening">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Atas Nama</label>
            <input type="text" name="rekening[${index}][atas_nama]" 
                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-900 text-sm text-gray-800 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200" 
                   placeholder="Nama pemilik rekening">
        </div>
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Nama Bank</label>
            <input type="text" name="rekening[${index}][nama_bank]" list="bank_options" 
                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-900 text-sm text-gray-800 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200" 
                   placeholder="Contoh: BCA, Mandiri">
        </div>
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">No Rekening</label>
            <input type="text" name="rekening[${index}][no_rekening]" 
                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-900 text-sm text-gray-800 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200" 
                   placeholder="Nomor rekening">
        </div>
    </div>

     `;
                return newRekeningGroup;
            };

            addButton.addEventListener('click', function() {
                const newGroup = createNewRekeningGroup(rekeningIndex);
                wrapper.appendChild(newGroup);
                rekeningIndex++;
                reindexForms();
            });

            wrapper.addEventListener('click', function(e) {
                if (e.target.closest('.remove-rekening')) {
                    const rekeningGroup = e.target.closest('.rekening-group');
                    if (rekeningGroup && wrapper.querySelectorAll('.rekening-group').length > 1) {
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
                    const h3 = group.querySelector('h3');
                    if (h3) {
                        const icon = h3.querySelector('svg');
                        h3.innerHTML = '';
                        if (icon) h3.appendChild(icon);
                        h3.appendChild(document.createTextNode(`Rekening ${index + 1}`));
                    }
                    const removeButton = group.querySelector('.remove-rekening');
                    if (removeButton) {
                        removeButton.style.display = (rekeningGroups.length > 1 && index > 0) ? 'block' :
                            'none';
                    }
                });
                rekeningIndex = rekeningGroups.length;
            }

            reindexForms();
        });
    </script>


@endsection
