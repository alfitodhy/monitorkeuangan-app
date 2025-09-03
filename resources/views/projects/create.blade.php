@extends('layouts.app')

@section('title', 'Tambah Proyek')

@section('content')

    <body class="bg-gray-50 dark:bg-gray-900">
        <div
            class="max-w-6xl mx-auto p-10 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 my-10">
            <h2 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white text-left">Tambah Proyek Baru</h2>
            <hr class="mb-6 border-gray-300 dark:border-gray-600">

            <div id="error-section"
                class="hidden mb-6 p-4 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 rounded-lg border border-red-200 dark:border-red-800 shadow-sm">
                <strong class="font-semibold">Terjadi kesalahan!</strong>
                <ul class="list-disc ml-6 mt-2 text-sm">
                    <li>Contoh error message</li>
                </ul>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <!-- Kode Proyek -->
                    <div>
                        <label for="kode_proyek"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-0.5">Kode
                            Proyek</label>
                        <input type="text" id="kode_proyek" name="kode_proyek"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 bg-white dark:bg-gray-700"
                            value="{{ old('kode_proyek') }}" placeholder="Masukkan kode proyek" required>
                    </div>

                    <!-- Nama Proyek -->
                    <div>
                        <label for="nama_proyek"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-0.5">Nama
                            Proyek</label>
                        <input type="text" id="nama_proyek" name="nama_proyek"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 bg-white dark:bg-gray-700"
                            value="{{ old('nama_proyek') }}" placeholder="Masukkan nama proyek" required>
                    </div>

                    <!-- Nama Klien -->
                    <div>
                        <label for="nama_klien"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-0.5">Nama
                            Klien</label>
                        <input type="text" id="nama_klien" name="nama_klien"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 bg-white dark:bg-gray-700"
                            value="{{ old('nama_klien') }}" placeholder="Masukkan nama klien" required>
                    </div>

                    <!-- Lampiran File -->
                    <div>
                        <label for="attachment_file"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-0.5">Upload Dokumen
                            (boleh lebih dari satu)</label>
                        <input type="file" id="attachment_file"
                            class="w-full text-sm 
        text-gray-700 dark:text-gray-300
        border border-gray-300 dark:border-gray-600 
        rounded-md
        file:mr-4 file:py-2 file:px-4
        file:rounded-l-md file:border-0
        file:text-sm file:font-semibold
        file:bg-indigo-50 file:text-indigo-700
        hover:file:bg-indigo-100
        dark:file:bg-gray-700 dark:file:text-indigo-300
        dark:hover:file:bg-gray-600
        bg-white dark:bg-gray-800"
                            name="attachment_file[]" multiple>

                    </div>

                    <!-- Nilai Proyek -->
                    <div>
                        <label for="nilai_proyek"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-0.5">Nilai Proyek</label>
                        <div class="relative mt-1">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400 text-sm">
                                Rp
                            </span>
                            <input type="text" id="nilai_proyek" name="nilai_proyek"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md pl-10 p-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 
                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 bg-white dark:bg-gray-700"
                                value="{{ old('nilai_proyek') }}" placeholder="1.000.000.000"
                                oninput="this.value = formatRibuan(this.value)" required>
                        </div>
                    </div>

                    <!-- Estimasi HPP -->
                    <div>
                        <label for="estimasi_hpp"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-0.5">Estimasi HPP
                            (%)</label>
                        <input type="text" id="estimasi_hpp" name="estimasi_hpp"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 bg-white dark:bg-gray-700"
                            value="{{ old('estimasi_hpp') }}" placeholder="Contoh: 60"
                            oninput="this.value = formatPersen(this.value)">
                    </div>

                    <div>
                        <label for="termin"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-0.5">Jumlah
                            Termin</label>
                        <input type="number" id="termin" name="termin"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 bg-white dark:bg-gray-700"
                            value="{{ old('termin', 1) }}" min="1" max="20" placeholder="Contoh: 3">
                    </div>

                    <div>
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <label for="nominal_hpp"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-0.5">Nominal
                                    HPP</label>
                                <input type="text" id="nominal_hpp" name="nominal_hpp"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 bg-white dark:bg-gray-700"
                                    readonly placeholder="Nominal HPP anda">
                            </div>

                            <!-- Profit (readonly) -->
                            <div class="flex-1">
                                <label for="profit"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-0.5">Profit</label>
                                <input type="text" id="profit" name="profit"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 bg-white dark:bg-gray-700"
                                    readonly placeholder="Profit anda">
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic termin inputs -->
                    <div id="termin-container" class="mt-4 space-y-6"></div>

                    <!-- Tanggal Proyek -->
                    <div class="space-y-4">
                        <div>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label for="tanggal_start_proyek"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-0.5">Tanggal
                                        Mulai</label>
                                    <input type="date" id="tanggal_start_proyek" name="tanggal_start_proyek"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 bg-white dark:bg-gray-700"
                                        value="{{ old('tanggal_start_proyek') }}">
                                </div>
                                <div class="flex-1">
                                    <label for="tanggal_deadline"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-0.5">Tanggal
                                        Deadline</label>
                                    <input type="date" id="tanggal_deadline" name="tanggal_deadline"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 bg-white dark:bg-gray-700"
                                        value="{{ old('tanggal_deadline') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Tipe Proyek -->
                        <div>
                            <label for="tipe_proyek"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-0.5">Tipe
                                Proyek</label>
                            <select id="tipe_proyek" name="tipe_proyek"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 bg-white dark:bg-gray-700"
                                onchange="toggleTipeLainnya()" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="Design" {{ old('tipe_proyek') == 'Design' ? 'selected' : '' }}>Design
                                </option>
                                <option value="Renovasi" {{ old('tipe_proyek') == 'Renovasi' ? 'selected' : '' }}>Renovasi
                                </option>
                                <option value="Bangun" {{ old('tipe_proyek') == 'Bangun' ? 'selected' : '' }}>Bangun
                                </option>
                                <option value="Lainnya" {{ old('tipe_proyek') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>

                            <!-- Input tambahan, hidden default -->
                            <input type="text" id="tipe_lainnya_input" name="tipe_lainnya"
                                class="w-full mt-2 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 bg-white dark:bg-gray-700"
                                placeholder="Tulis tipe proyek..." style="display: none;"
                                value="{{ old('tipe_lainnya') }}">
                        </div>

                        <!-- Durasi Pengerjaan -->
                        <div>
                            <label for="durasi_pengerjaan_bulan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-0.5">Durasi
                                Pengerjaan (bulan)</label>
                            <input type="number" id="durasi_pengerjaan_bulan" name="durasi_pengerjaan_bulan"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 bg-white dark:bg-gray-700"
                                value="{{ old('durasi_pengerjaan_bulan') }}" placeholder="Contoh: 6">
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="md:col-span-2">
                        <label for="keterangan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-0.5">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" rows="3"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 bg-white dark:bg-gray-700"
                            placeholder="Tambahkan keterangan proyek di sini...">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('projects.index') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md 
          bg-gray-100 text-gray-700 hover:bg-gray-200 
          dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 
          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 
          transition duration-200">
                        Kembali
                    </a>

                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md 
          bg-indigo-600 text-white hover:bg-indigo-700 
          dark:bg-indigo-500 dark:hover:bg-indigo-600 
          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 
          transition duration-200">
                        Simpan
                    </button>

                </div>
            </form>
        </div>

        <script>
            // Existing JavaScript functions
            function formatRibuan(angka) {
                let number_string = angka.replace(/[^,\d]/g, "").toString(),
                    split = number_string.split(","),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? "." : "";
                    rupiah += separator + ribuan.join(".");
                }

                rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
                return rupiah;
            }

            function formatPersen(value) {
                return value.replace(/[^0-9]/g, '');
            }

            function toggleTipeLainnya() {
                const select = document.getElementById('tipe_proyek');
                const input = document.getElementById('tipe_lainnya_input');

                if (select.value === 'Lainnya') {
                    input.style.display = 'block';
                    input.required = true;
                } else {
                    input.style.display = 'none';
                    input.required = false;
                    input.value = '';
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const terminInput = document.getElementById('termin');
                const terminContainer = document.getElementById('termin-container');
                const nilaiProyekInput = document.getElementById('nilai_proyek');
                const estimasiHppInput = document.getElementById('estimasi_hpp');
                const nominalHppInput = document.getElementById('nominal_hpp');
                const profitInput = document.getElementById('profit');

                function formatRupiah(angka) {
                    let number_string = angka.replace(/[^,\d]/g, "").toString(),
                        split = number_string.split(","),
                        sisa = split[0].length % 3,
                        rupiah = split[0].substr(0, sisa),
                        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                    if (ribuan) {
                        let separator = sisa ? "." : "";
                        rupiah += separator + ribuan.join(".");
                    }

                    rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
                    return rupiah ? "Rp " + rupiah : "";
                }

                function calculateHppAndProfit() {
                    const nilaiProyek = parseFloat(nilaiProyekInput.value.replace(/[^0-9]/g, '')) || 0;
                    const estimasiHpp = parseFloat(estimasiHppInput.value.replace(/[^0-9]/g, '')) || 0;

                    const nominalHpp = (nilaiProyek * estimasiHpp) / 100;
                    const profit = nilaiProyek - nominalHpp;

                    nominalHppInput.value = formatRupiah(nominalHpp.toString());
                    profitInput.value = formatRupiah(profit.toString());
                }

                nilaiProyekInput.addEventListener('input', calculateHppAndProfit);
                estimasiHppInput.addEventListener('input', calculateHppAndProfit);

                function renderTerminFields(jumlahTermin) {
                    terminContainer.innerHTML = '';

                    for (let i = 1; i <= jumlahTermin; i++) {
                        const wrapper = document.createElement('div');
                        wrapper.classList.add('p-4', 'border', 'border-gray-200', 'dark:border-gray-600', 'rounded-lg',
                            'shadow-sm',
                            'bg-gray-50', 'dark:bg-gray-700', 'transition-colors', 'duration-300');

                        wrapper.innerHTML = `
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Termin ke-${i}</h4>
                        
      <!-- Wrapper per termin -->
<div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm bg-gray-50 dark:bg-gray-800">
    <!-- Grid atas -->
    <div class="grid grid-cols-6 gap-4 items-start">
        <!-- Termin Ke -->
        <div class="col-span-1">
            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-0.5 whitespace-nowrap">
                Termin Ke
            </label>
            <input type="number" name="termins[${i}][termin_ke]" value="${i}"
                class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm 
                                                       focus:ring-indigo-500 focus:border-indigo-500 
                                                       bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                readonly>
        </div>

        <!-- Jatuh Tempo & Jumlah (kasih jarak dari Termin Ke) -->
        <div class="col-span-5 flex gap-4 ml-2">
            <!-- Jatuh Tempo -->
            <div class="flex-1 basis-[55%]">
                <label class="block text-sm text-gray-600 dark:text-gray-400 mb-0.5">Jatuh Tempo</label>
                <input type="date" name="termins[${i}][tanggal_jatuh_tempo]"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm 
                           focus:ring-indigo-500 focus:border-indigo-500 
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <!-- Jumlah -->
            <div class="flex-1 basis-[45%]">
                <label class="block text-sm text-gray-600 dark:text-gray-400 mb-0.5">Jumlah</label>
                <input type="text" name="termins[${i}][jumlah]" id="jumlah-${i}"
                    class="rupiah-input w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm 
                           focus:ring-indigo-500 focus:border-indigo-500 
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                           placeholder-gray-400 dark:placeholder-gray-500"
                    placeholder="Rp 0">
            </div>
        </div>
    </div>

    <!-- Keterangan (full width di bawah) -->
    <div class="mt-3">
        <label class="block text-sm text-gray-600 dark:text-gray-400 mb-0.5">Keterangan</label>
        <input type="text" name="termins[${i}][keterangan]"
            class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm 
                   focus:ring-indigo-500 focus:border-indigo-500 
                   bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                   placeholder-gray-400 dark:placeholder-gray-500"
            placeholder="Opsional">
    </div>
</div>

                    `;
                        terminContainer.appendChild(wrapper);
                    }

                    // pasang formatter rupiah di semua input jumlah
                    document.querySelectorAll('.rupiah-input').forEach(input => {
                        input.addEventListener('keyup', function() {
                            this.value = formatRupiah(this.value);
                        });
                    });
                }

                // render awal
                renderTerminFields(parseInt(terminInput.value || 1));

                // render ulang kalau jumlah termin berubah
                terminInput.addEventListener('input', function() {
                    let jumlah = parseInt(this.value) || 1;
                    renderTerminFields(jumlah);
                });
            });
        </script>
    </body>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            console.log('Form sedang di-submit, membersihkan format...');

            // Bersihkan nilai_proyek
            const nilaiProyekInput = document.getElementById('nilai_proyek');
            if (nilaiProyekInput) {
                const cleanValue = nilaiProyekInput.value.replace(/[^\d]/g, '');
                nilaiProyekInput.value = cleanValue;
                console.log('Nilai proyek dibersihkan:', cleanValue);
            }

            // Bersihkan estimasi_hpp
            const estimasiInput = document.getElementById('estimasi_hpp');
            if (estimasiInput) {
                estimasiInput.value = estimasiInput.value.replace(/[^\d]/g, '');
            }

            // Bersihkan semua input termin
            let totalTermin = 0;
            const terminInputs = document.querySelectorAll('input[name*="[jumlah]"]');

            terminInputs.forEach((input, index) => {
                const originalValue = input.value;
                const cleanValue = input.value.replace(/[^\d]/g, '');
                input.value = cleanValue;

                const numericValue = parseInt(cleanValue) || 0;
                totalTermin += numericValue;

                console.log(`Termin ${index + 1}: ${originalValue} -> ${cleanValue} (${numericValue})`);
            });

            // Validasi total termin
            const nilaiProyek = parseInt(nilaiProyekInput.value) || 0;
            console.log(`Total termin: ${totalTermin}, Nilai proyek: ${nilaiProyek}`);

            if (terminInputs.length > 0 && totalTermin !== nilaiProyek) {
                e.preventDefault();
                alert(
                    `Total jumlah termin (${totalTermin.toLocaleString('id-ID')}) harus sama dengan Nilai Proyek (${nilaiProyek.toLocaleString('id-ID')}).`
                );
                return false;
            }

            console.log('Form validation passed, submitting...');
        });
    </script>



    <script>
        // Format ribuan untuk input nilai proyek
        function formatRibuan(angka) {
            angka = angka.replace(/\D/g, ''); // Hapus semua selain angka
            return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Tambahkan % secara otomatis saat input estimasi_hpp
        document.addEventListener('DOMContentLoaded', function() {
            const estimasiInput = document.getElementById('estimasi_hpp');
            const nilaiProyek = document.getElementById('nilai_proyek');

            // Saat load, format nilai proyek kalau sudah ada
            if (nilaiProyek && nilaiProyek.value) {
                nilaiProyek.value = formatRibuan(nilaiProyek.value);
            }

            // Tambahkan % otomatis saat ketik estimasi_hpp
            if (estimasiInput) {
                estimasiInput.addEventListener('input', function() {
                    let val = estimasiInput.value.replace(/[^0-9]/g, ''); // Hapus semua kecuali angka
                    if (val !== '') {
                        estimasiInput.value = val + '%';
                    } else {
                        estimasiInput.value = '';
                    }
                });
            }

            // Tampilkan input teks jika pilih "Lainnya"
            toggleTipeLainnya();
        });

        // Hapus simbol % saat submit
        document.querySelector('form').addEventListener('submit', function() {
            const estimasiInput = document.getElementById('estimasi_hpp');
            estimasiInput.value = estimasiInput.value.replace('%', '');

            const nilaiProyek = document.getElementById('nilai_proyek');
            nilaiProyek.value = nilaiProyek.value.replace(/\./g, '');
        });

        // Tampilkan input teks jika pilih "Lainnya"
        function toggleTipeLainnya() {
            const select = document.getElementById('tipe_proyek');
            const inputLainnya = document.getElementById('tipe_lainnya_input');

            if (select && inputLainnya) {
                if (select.value === 'Lainnya') {
                    inputLainnya.style.display = 'block';
                    inputLainnya.required = true;
                } else {
                    inputLainnya.style.display = 'none';
                    inputLainnya.required = false;
                }
            }
        }
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nilaiProyekInput = document.getElementById('nilai_proyek');
            const estimasiHppInput = document.getElementById('estimasi_hpp');
            const nominalHppInput = document.getElementById('nominal_hpp');
            const profitInput = document.getElementById('profit');

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(number);
            }

            function hitungHppProfit() {
                // Ambil nilai proyek, hapus tanda selain angka
                let nilaiProyekStr = nilaiProyekInput.value.replace(/\D/g, '');
                let nilaiProyek = parseFloat(nilaiProyekStr) || 0;

                let estimasiHpp = parseFloat(estimasiHppInput.value) || 0;

                // Hitung nominal HPP dan profit
                let nominalHpp = (nilaiProyek * estimasiHpp) / 100;
                let profit = nilaiProyek - nominalHpp;

                // Tampilkan hasil dalam format Rupiah
                nominalHppInput.value = nominalHpp > 0 ? formatRupiah(nominalHpp) : '';
                profitInput.value = profit > 0 ? formatRupiah(profit) : '';
            }

            // Event listener input perubahan nilai proyek dan estimasi HPP
            nilaiProyekInput.addEventListener('input', function(e) {
                // Format nilai proyek input biar ada pemisah ribuan saat ketik
                let val = e.target.value.replace(/\D/g, '');
                if (val) {
                    e.target.value = new Intl.NumberFormat('id-ID').format(val);
                } else {
                    e.target.value = '';
                }
                hitungHppProfit();
            });

            estimasiHppInput.addEventListener('input', hitungHppProfit);
        });
    </script>


@endsection
