@extends('layouts.app')

@section('title', 'Tambah Vendor')

@section('content')
    <div class="container mx-auto p-4 sm:p-6 lg:p-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-0">Tambah Vendor</h1>
            <a href="{{ route('vendors.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg shadow-sm text-sm inline-flex items-center space-x-2 transition duration-200 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Pesan Error --}}
        @if ($errors->any())
            <div
                class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <form action="{{ route('vendors.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Informasi Dasar --}}
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Vendor --}}
                        <div class="md:col-span-2">
                            <label for="nama_vendor"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Vendor <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_vendor" id="nama_vendor"
                                class="w-full h-10 px-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                value="{{ old('nama_vendor') }}" required>
                        </div>

                        {{-- No. Telepon --}}
                        <div>
                            <label for="no_telp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                No. Telepon
                            </label>
                            <input type="text" name="no_telp" id="no_telp"
                                class="w-full h-10 px-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                value="{{ old('no_telp') }}">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email
                            </label>
                            <input type="email" name="email" id="email"
                                class="w-full h-10 px-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                value="{{ old('email') }}">
                        </div>

                        {{-- Jenis Vendor --}}
                        <div>
                            <label for="jenis_vendor"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jenis Vendor
                            </label>
                            <select name="jenis_vendor" id="jenis_vendor"
                                class="w-full h-10 px-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                                <option value="">-- Pilih Jenis Vendor --</option>
                                <option value="Tukang" {{ old('jenis_vendor') == 'Tukang' ? 'selected' : '' }}>Tukang
                                </option>
                                <option value="Material" {{ old('jenis_vendor') == 'Material' ? 'selected' : '' }}>Material
                                </option>
                                <option value="Jasa dan Material"
                                    {{ old('jenis_vendor') == 'Jasa dan Material' ? 'selected' : '' }}>Jasa dan Material
                                </option>
                            </select>
                        </div>

                        {{-- Spesialisasi --}}
                        <div>
                            <label for="spesialisasi"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Spesialisasi
                            </label>
                            <input type="text" name="spesialisasi" id="spesialisasi" list="spesialisasi_options"
                                class="w-full h-10 px-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                value="{{ old('spesialisasi') }}">
                            <datalist id="spesialisasi_options">
                                @foreach ($spesialisasi_vendor as $spesialisasi)
                                    <option value="{{ $spesialisasi }}">
                                @endforeach
                            </datalist>
                        </div>

                        {{-- Alamat --}}
                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Alamat
                            </label>
                            <textarea name="alamat" id="alamat" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 resize-none">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Rekening Bank --}}
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Rekening Bank
                        </h3>
                        <button type="button" id="add-rekening"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm text-sm inline-flex items-center space-x-2 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Tambah Rekening</span>
                        </button>
                    </div>

                    <div id="rekening-wrapper" class="space-y-4">
                        @php
                            $rekening_list = old('rekening', []);
                            if (!is_array($rekening_list) || empty($rekening_list)) {
                                $rekening_list = [[]];
                            }
                        @endphp

                        @foreach ($rekening_list as $key => $rekening)
                            <div
                                class="rekening-group p-3 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-xs font-semibold text-gray-700 dark:text-gray-200">Rekening
                                        {{ $key + 1 }}</h4>
                                    @if ($key > 0)
                                        <button type="button"
                                            class="remove-rekening text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors duration-200 p-1"
                                            title="Hapus rekening">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Atas
                                            Nama</label>
                                        <input type="text" name="rekening[{{ $key }}][atas_nama]"
                                            class="w-full h-8 px-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-xs bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            value="{{ $rekening['atas_nama'] ?? '' }}">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nama
                                            Bank</label>
                                        <input type="text" name="rekening[{{ $key }}][nama_bank]"
                                            list="bank_options"
                                            class="w-full h-8 px-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-xs bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            value="{{ $rekening['nama_bank'] ?? '' }}">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">No
                                            Rekening</label>
                                        <input type="text" name="rekening[{{ $key }}][no_rekening]"
                                            class="w-full h-8 px-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-xs bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            value="{{ $rekening['no_rekening'] ?? '' }}">
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
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Keterangan
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 resize-none">{{ old('keterangan') }}</textarea>
                </div>

                {{-- Tombol --}}
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addButton = document.getElementById('add-rekening');
            const wrapper = document.getElementById('rekening-wrapper');

            let rekeningIndex = wrapper.querySelectorAll('.rekening-group').length;

            const createNewRekeningGroup = (index) => {
                const newRekeningGroup = document.createElement('div');
                newRekeningGroup.className =
                    'rekening-group p-3 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900';

                newRekeningGroup.innerHTML = `
    <div class="flex items-center justify-between mb-3">
        <h4 class="text-xs font-semibold text-gray-700 dark:text-gray-200">Rekening ${index + 1}</h4>
        <button type="button"
            class="remove-rekening text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors duration-200 p-1"
            title="Hapus rekening">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Atas Nama</label>
            <input type="text" name="rekening[${index}][atas_nama]"
                class="w-full h-8 px-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-xs
                       bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Bank</label>
            <input type="text" name="rekening[${index}][nama_bank]" list="bank_options"
                class="w-full h-8 px-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-xs
                       bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">No Rekening</label>
            <input type="text" name="rekening[${index}][no_rekening]"
                class="w-full h-8 px-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-xs
                       bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
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

@endsection
