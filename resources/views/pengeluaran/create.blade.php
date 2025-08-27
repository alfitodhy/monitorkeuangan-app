@extends('layouts.app')

@section('title', 'Tambah Pengeluaran Proyek')

@section('content')

    @if ($errors->any())
        <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 flex justify-center">
            <div class="w-full max-w-2xl">
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-8 text-center tracking-tight">
                    Tambah Pengeluaran Proyek
                </h1>

                <form action="{{ route('pengeluaran.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Pilih Proyek --}}
                        <div class="space-y-1">
                            <label for="id_proyek" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Proyek</label>
                            <div class="relative">
                                <select name="id_proyek" id="id_proyek"
                                    class="w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white
                    focus:outline-none focus:ring-indigo-500 focus:border-indigo-500
                    sm:text-sm rounded-md shadow-sm transition duration-150 ease-in-out"
                                    required>
                                    <option value="">-- Pilih Proyek --</option>
                                    @foreach ($proyek as $p)
                                        <option value="{{ $p->id_proyek }}" data-nama="{{ $p->nama_proyek }}">
                                            {{ $p->nama_proyek }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="nama_proyek" id="nama_proyek"> {{-- Hidden Input --}}
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>


                        {{-- Tanggal Pengeluaran --}}
                        <div class="space-y-1">
                            <label for="tanggal_pengeluaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal
                                Pengeluaran</label>
                            <input type="date" name="tanggal_pengeluaran" id="tanggal_pengeluaran"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out dark:bg-gray-700 dark:text-white"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Pilih Jenis Vendor --}}
                        <div class="space-y-1">
                            <label for="jenis_vendor" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Vendor</label>
                            <div class="relative">
                                <select id="jenis_vendor"
                                    class="w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm transition duration-150 ease-in-out"
                                    required>
                                    <option value="">-- Pilih Jenis Vendor --</option>
                                    @foreach ($jenisVendor as $jenis)
                                        <option value="{{ $jenis }}">{{ $jenis }}</option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Pilih Vendor --}}
                        <div class="space-y-1">
                            <label for="vendor" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vendor</label>
                            <div class="relative">
                                <select id="vendor" name="id_vendor"
                                    class="w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm transition duration-150 ease-in-out"
                                    required disabled>
                                    <option value="">-- Pilih Vendor --</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400 dark:text-gray-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Pilih Rekening --}}
                        <div class="space-y-1">
                            <label for="rekening" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rekening Vendor</label>
                            <div class="relative">
                                <select id="rekening" name="rekening"
                                    class="w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm transition duration-150 ease-in-out"
                                    required disabled>
                                    <option value="">-- Pilih Rekening --</option>
                                    <option value="lainnya">Lainnya...</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400 dark:text-gray-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Input Rekening Lainnya --}}
                    <div id="rekening_lainnya" class="space-y-4 mt-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Detail Rekening Lainnya</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Atas Nama --}}
                            <div class="space-y-1">
                                <label for="atas_nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Atas Nama</label>
                                <input type="text" name="atas_nama" id="atas_nama"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out dark:bg-gray-700 dark:text-white"
                                    placeholder="Nama pemilik rekening">
                            </div>

                            {{-- Nama Bank --}}
                            <div class="space-y-1">
                                <label for="nama_bank" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Bank</label>
                                <input type="text" name="nama_bank" id="nama_bank"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out dark:bg-gray-700 dark:text-white"
                                    placeholder="Contoh: Bank BCA">
                            </div>

                            {{-- Nomor Rekening --}}
                            <div class="space-y-1">
                                <label for="no_rekening" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor
                                    Rekening</label>
                                <input type="text" name="no_rekening" id="no_rekening"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out dark:bg-gray-700 dark:text-white"
                                    placeholder="Nomor rekening">
                            </div>
                        </div>
                    </div>

                    {{-- Jumlah --}}
                    <div class="space-y-1">
                        <label for="jumlah_display" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah</label>
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                            </div>
                            <input type="text" id="jumlah_display"
                                class="w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out dark:bg-gray-700 dark:text-white"
                                placeholder="0" required>
                            <input type="hidden" name="jumlah" id="jumlah_value">
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div class="space-y-1">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan</label>
                        <textarea name="keterangan" id="keterangan"
                            class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out dark:bg-gray-700 dark:text-white"
                            rows="3" placeholder="Masukkan keterangan tambahan..."></textarea>
                    </div>

                    {{-- File Bukti Transfer / Nota --}}
                    <div class="space-y-1">
                        <label for="file_nota" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bukti Nota</label>
                        <div class="mt-1 flex items-center space-x-2">
                            <input type="file" name="file_nota" id="file_nota" accept="image/*"
                                class="w-full text-sm text-gray-500 dark:text-gray-400
                    border border-gray-300 dark:border-gray-600 rounded-md
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-l-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100 dark:file:bg-indigo-700 dark:file:text-indigo-100 dark:hover:file:bg-indigo-600"
                                required>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: JPG, PNG. Maks 2MB.</p>
                    </div>

                    {{-- Status hanya muncul jika role = bod, admin keuangan, super admin --}}
                    @php
                        $rolesAllowed = ['bod', 'admin keuangan', 'super admin'];
                    @endphp

                    @if (in_array(Auth::user()->role, $rolesAllowed))
                        <div class="space-y-1">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status" id="status"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out dark:bg-gray-700 dark:text-white">
                                <option value="Pengajuan">Pengajuan</option>
                                <option value="Approve">Approve</option>
                            </select>
                        </div>

                        {{-- Upload Bukti Transfer (muncul hanya kalau pilih Approve) --}}
                        <div class="space-y-1 mt-3 hidden" id="bukti_transfer_wrapper">
                            <label for="file_buktitf" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Bukti
                                Transfer</label>
                            <input type="file" name="file_buktitf" id="file_buktitf" accept="image/*,application/pdf"
                                class="w-full text-sm text-gray-500 dark:text-gray-400
                    border border-gray-300 dark:border-gray-600 rounded-md
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-l-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100 dark:file:bg-indigo-700 dark:file:text-indigo-100 dark:hover:file:bg-indigo-600">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: JPG, PNG, PDF. Maks 2MB.</p>
                        </div>
                    @endif

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const statusSelect = document.getElementById("status");
                            const buktiWrapper = document.getElementById("bukti_transfer_wrapper");

                            if (statusSelect) {
                                statusSelect.addEventListener("change", function() {
                                    if (this.value === "Approve") {
                                        buktiWrapper.classList.remove("hidden");
                                    } else {
                                        buktiWrapper.classList.add("hidden");
                                    }
                                });
                            }
                        });
                    </script>

                    <div class="pt-5">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('pengeluaran.index') }}"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Skrip JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elemen-elemen formulir
            const jenisEl = document.getElementById('jenis_vendor');
            const vendorEl = document.getElementById('vendor');
            const rekeningEl = document.getElementById('rekening');
            const rekeningLainnyaEl = document.getElementById('rekening_lainnya');
            const jumlahDisplay = document.getElementById('jumlah_display');
            const jumlahValue = document.getElementById('jumlah_value');
            const atasNamaInput = document.getElementById('atas_nama');
            const namaBankInput = document.getElementById('nama_bank');
            const noRekeningInput = document.getElementById('no_rekening');

            const proyekSelect = document.getElementById('id_proyek');
            const namaProyekInput = document.getElementById('nama_proyek');


            proyekSelect.addEventListener('change', function() {
                const selectedOption = proyekSelect.options[proyekSelect.selectedIndex];
                namaProyekInput.value = selectedOption.dataset.nama || '';
            });

            // Fungsi-fungsi reset
            function resetVendors() {
                vendorEl.innerHTML = '<option value="">-- Pilih Vendor --</option>';
                vendorEl.disabled = true;
                vendorEl.classList.add('bg-gray-50', 'dark:bg-gray-700');
                vendorEl.classList.remove('bg-white', 'dark:bg-gray-800');
                // Mengubah warna ikon menjadi abu-abu saat disabled
                const vendorIcon = vendorEl.parentElement.querySelector('.pointer-events-none');
                vendorIcon.classList.remove('text-gray-700', 'dark:text-gray-300');
                vendorIcon.classList.add('text-gray-400', 'dark:text-gray-500');
            }




            function resetRek() {
                // Kita akan membangun daftar opsi dari awal setiap kali reset
                const defaultOption = document.createElement('option');
                defaultOption.value = "";
                defaultOption.textContent = "-- Pilih Rekening --";

                const othersOption = document.createElement('option');
                othersOption.value = "lainnya";
                othersOption.textContent = "Lainnya...";

                rekeningEl.innerHTML = ''; // Kosongkan semua opsi
                rekeningEl.appendChild(defaultOption);
                rekeningEl.appendChild(othersOption);

                rekeningEl.disabled = true;
                rekeningEl.classList.add('bg-gray-50', 'dark:bg-gray-700');
                rekeningEl.classList.remove('bg-white', 'dark:bg-gray-800');
                rekeningLainnyaEl.classList.add('hidden');
                atasNamaInput.removeAttribute('required');
                namaBankInput.removeAttribute('required');
                noRekeningInput.removeAttribute('required');
                // Mengubah warna ikon menjadi abu-abu saat disabled
                const rekeningIcon = rekeningEl.parentElement.querySelector('.pointer-events-none');
                rekeningIcon.classList.remove('text-gray-700', 'dark:text-gray-300');
                rekeningIcon.classList.add('text-gray-400', 'dark:text-gray-500');
            }

            // Event listener untuk Jenis Vendor
            jenisEl.addEventListener('change', async function() {
                resetVendors();
                resetRek();

                const jenis = this.value;
                if (!jenis) return;

                try {
                    const res = await fetch(`/vendor-by-jenis/${encodeURIComponent(jenis)}`);
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    const data = await res.json();

                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(v => {
                            const opt = document.createElement('option');
                            opt.value = v.id;
                            opt.textContent = v.nama_vendor ?? v.nama ?? ('Vendor ' + v.id);
                            opt.dataset.rekening = JSON.stringify(v.rekening ?? []);
                            vendorEl.appendChild(opt);
                        });
                        vendorEl.disabled = false;
                        vendorEl.classList.remove('bg-gray-50', 'dark:bg-gray-700');
                        vendorEl.classList.add('bg-white', 'dark:bg-gray-800');
                        // Mengubah warna ikon menjadi normal saat enabled
                        const vendorIcon = vendorEl.parentElement.querySelector('.pointer-events-none');
                        vendorIcon.classList.remove('text-gray-400', 'dark:text-gray-500');
                        vendorIcon.classList.add('text-gray-700', 'dark:text-gray-300');
                    }
                } catch (err) {
                    console.error('fetch vendor error', err);
                    vendorEl.disabled = true;
                    vendorEl.classList.add('bg-gray-50', 'dark:bg-gray-700');
                    vendorEl.classList.remove('bg-white', 'dark:bg-gray-800');
                }
            });

            // Event listener untuk Vendor
            vendorEl.addEventListener('change', function() {
                resetRek();
                const sel = this.selectedOptions[0];
                if (!sel) return;

                const rekeningData = sel.dataset.rekening;
                if (!rekeningData || rekeningData === 'null' || rekeningData === '[]') return;

                try {
                    const arr = JSON.parse(rekeningData);
                    if (Array.isArray(arr) && arr.length > 0) {
                        // Simpan opsi "Lainnya..."
                        const othersOption = rekeningEl.querySelector('option[value="lainnya"]');
                        rekeningEl.removeChild(othersOption);

                        // Tambahkan opsi-opsi rekening dari data
                        arr.forEach(rk => {
                            const display =
                                `${rk.nama_bank} - ${rk.no_rekening} a/n ${rk.atas_nama}`;
                            const opt = document.createElement('option');
                            opt.value = display;
                            opt.textContent = display;
                            rekeningEl.appendChild(opt);
                        });

                        // Tambahkan kembali opsi "Lainnya..." di paling bawah
                        rekeningEl.appendChild(othersOption);

                        rekeningEl.disabled = false;
                        rekeningEl.classList.remove('bg-gray-50', 'dark:bg-gray-700');
                        rekeningEl.classList.add('bg-white', 'dark:bg-gray-800');
                        if (arr.length === 1) rekeningEl.selectedIndex = 1;
                        // Mengubah warna ikon menjadi normal saat enabled
                        const rekeningIcon = rekeningEl.parentElement.querySelector('.pointer-events-none');
                        rekeningIcon.classList.remove('text-gray-400', 'dark:text-gray-500');
                        rekeningIcon.classList.add('text-gray-700', 'dark:text-gray-300');
                    }
                } catch (e) {
                    console.error('parse rekening error', e);
                }
            });

            // Event listener untuk Rekening Vendor
            rekeningEl.addEventListener('change', function() {
                const selectedValue = this.value;

                if (selectedValue === 'lainnya') {
                    rekeningLainnyaEl.classList.remove('hidden');
                    atasNamaInput.setAttribute('required', 'required');
                    namaBankInput.setAttribute('required', 'required');
                    noRekeningInput.setAttribute('required', 'required');
                } else {
                    rekeningLainnyaEl.classList.add('hidden');
                    atasNamaInput.removeAttribute('required');
                    namaBankInput.removeAttribute('required');
                    noRekeningInput.removeAttribute('required');
                }
            });

            // Event listener untuk input Jumlah (memformat angka)
            jumlahDisplay.addEventListener('input', function(e) {
                let value = e.target.value;
                value = value.replace(/\D/g, '');
                let formattedValue = new Intl.NumberFormat('id-ID').format(value);
                e.target.value = formattedValue;
                jumlahValue.value = value;
            });

            // Inisialisasi format jumlah jika ada nilai default
            if (jumlahValue.value) {
                let value = jumlahValue.value;
                let formattedValue = new Intl.NumberFormat('id-ID').format(value);
                jumlahDisplay.value = formattedValue;
            }

        });
    </script>

@endsection