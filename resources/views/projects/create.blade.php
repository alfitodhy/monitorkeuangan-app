@extends('layouts.app')

@section('content')
    {{-- **PENTING:** Tambahkan CSS kustom berikut ke file CSS utama Anda (misalnya app.css) --}}
    <style>
        .custom-file-input::-webkit-file-upload-button {
            visibility: hidden;
        }

        .custom-file-input::before {
            content: 'Pilih Dokumen';
            display: inline-block;
            background: linear-gradient(to right, #6366f1, #8b5cf6);
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            outline: none;
            white-space: nowrap;
            -webkit-user-select: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.875rem;
            transition: background-color 0.3s ease;
        }

        .custom-file-input:hover::before {
            background: linear-gradient(to right, #4f46e5, #7c3aed);
        }

        .custom-file-input:active::before {
            background: linear-gradient(to right, #4338ca, #6d28d9);
        }

        .custom-file-input {
            background-color: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem;
            width: 100%;
            cursor: pointer;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .custom-file-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
    </style>

    <div class="max-w-6xl mx-auto p-10 bg-white rounded-2xl shadow-xl border border-gray-200 my-10">
        <h2 class="text-lg font-semibold mb-2 text-gray-900 text-left">Tambah Proyek Baru</h2>
        <hr class="mb-6 border-gray-300">

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg border border-red-200 shadow-sm">
                <strong class="font-semibold">Terjadi kesalahan!</strong>
                <ul class="list-disc ml-6 mt-2 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                {{-- Kode Proyek --}}
                <div>
                    <label for="kode_proyek" class="block text-sm font-medium text-gray-700 mb-0.5">Kode Proyek</label>
                    <input type="text" id="kode_proyek" name="kode_proyek"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200"
                        value="{{ old('kode_proyek') }}" placeholder="Masukkan kode proyek" required>
                </div>

                {{-- Nama Proyek --}}
                <div>
                    <label for="nama_proyek" class="block text-sm font-medium text-gray-700 mb-0.5">Nama Proyek</label>
                    <input type="text" id="nama_proyek" name="nama_proyek"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200"
                        value="{{ old('nama_proyek') }}" placeholder="Masukkan nama proyek" required>
                </div>

                {{-- Nama Klien --}}
                <div>
                    <label for="nama_klien" class="block text-sm font-medium text-gray-700 mb-0.5">Nama Klien</label>
                    <input type="text" id="nama_klien" name="nama_klien"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200"
                        value="{{ old('nama_klien') }}" placeholder="Masukkan nama klien" required>
                </div>

                {{-- Lampiran File --}}
                <div>
                    <label for="attachment_file" class="block text-sm font-medium text-gray-700 mb-0.5">Upload Dokumen
                        (boleh lebih dari satu)</label>
                    <input type="file" id="attachment_file" class="custom-file-input text-sm" name="attachment_file[]"
                        multiple>
                </div>

                {{-- Nilai Proyek --}}
                <div>
                    <label for="nilai_proyek" class="block text-sm font-medium text-gray-700 mb-0.5">Nilai Proyek</label>
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-sm">
                            Rp
                        </span>
                        <input type="text" id="nilai_proyek" name="nilai_proyek"
                            class="w-full border border-gray-300 rounded-md pl-10 p-2 text-sm text-gray-900 placeholder-gray-400 
                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200"
                            value="{{ old('nilai_proyek') }}" placeholder="1.000.000.000"
                            oninput="this.value = formatRibuan(this.value)" required>
                    </div>
                </div>




                {{-- Estimasi HPP --}}
                <div>
                    <label for="estimasi_hpp" class="block text-sm font-medium text-gray-700 mb-0.5">Estimasi HPP
                        (%)</label>
                    <input type="text" id="estimasi_hpp" name="estimasi_hpp"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200"
                        value="{{ old('estimasi_hpp') }}" placeholder="Contoh: 60"
                        oninput="this.value = formatPersen(this.value)">
                </div>
                <div>
                    <label for="termin" class="block text-sm font-medium text-gray-700 mb-0.5">Jumlah Termin</label>
                    <input type="number" id="termin" name="termin"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200"
                        value="{{ old('termin', 1) }}" placeholder="Contoh: 3">
                </div>

                <div>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label for="nominal_hpp" class="block text-sm font-medium text-gray-700 mb-0.5">Nominal
                                HPP</label>
                            <input type="text" id="nominal_hpp" name="nominal_hpp"
                                class="w-full border border-gray-300 rounded-md p-2 text-sm bg-gray-100" readonly
                                placeholder="Nominal HPP anda">
                        </div>

                        <!-- Profit (readonly) -->
                        <div class="flex-1">
                            <label for="profit" class="block text-sm font-medium text-gray-700 mb-0.5">Profit</label>
                            <input type="text" id="profit" name="profit"
                                class="w-full border border-gray-300 rounded-md p-2 text-sm bg-gray-100" readonly
                                placeholder="Profit anda">
                        </div>
                    </div>
                </div>
                {{-- Jumlah Termin --}}

                {{-- Tipe Proyek --}}
                <div>
                    <label for="tipe_proyek" class="block text-sm font-medium text-gray-700 mb-0.5">Tipe Proyek</label>
                    <select id="tipe_proyek" name="tipe_proyek"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200"
                        onchange="toggleTipeLainnya()" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="Design" {{ old('tipe_proyek') == 'Design' ? 'selected' : '' }}>Design</option>
                        <option value="Renovasi" {{ old('tipe_proyek') == 'Renovasi' ? 'selected' : '' }}>Renovasi</option>
                        <option value="Bangun" {{ old('tipe_proyek') == 'Bangun' ? 'selected' : '' }}>Bangun</option>
                        <option value="Lainnya" {{ old('tipe_proyek') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>

                    <!-- Input tambahan, hidden default -->
                    <input type="text" id="tipe_lainnya_input" name="tipe_lainnya"
                        class="w-full mt-2 border border-gray-300 rounded-md p-2 text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200"
                        placeholder="Tulis tipe proyek..." style="display: none;" value="{{ old('tipe_lainnya') }}">
                </div>


                {{-- Tanggal Proyek --}}
                <div>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label for="tanggal_start_proyek"
                                class="block text-sm font-medium text-gray-700 mb-0.5">Tanggal Mulai</label>
                            <input type="date" id="tanggal_start_proyek" name="tanggal_start_proyek"
                                class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200"
                                value="{{ old('tanggal_start_proyek') }}">
                        </div>
                        <div class="flex-1">
                            <label for="tanggal_deadline" class="block text-sm font-medium text-gray-700 mb-0.5">Tanggal
                                Deadline</label>
                            <input type="date" id="tanggal_deadline" name="tanggal_deadline"
                                class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200"
                                value="{{ old('tanggal_deadline') }}">
                        </div>
                    </div>
                </div>


                {{-- Durasi Pengerjaan --}}
                <div>
                    <label for="durasi_pengerjaan_bulan" class="block text-sm font-medium text-gray-700 mb-0.5">Durasi
                        Pengerjaan (bulan)</label>
                    <input type="number" id="durasi_pengerjaan_bulan" name="durasi_pengerjaan_bulan"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200"
                        value="{{ old('durasi_pengerjaan_bulan') }}" placeholder="Contoh: 6">
                </div>

                {{-- Keterangan --}}
                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-0.5">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200"
                        placeholder="Tambahkan keterangan proyek di sini...">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('projects.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                    Kembali
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                    Simpan Proyek
                </button>
            </div>
        </form>

    </div>

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
