@extends('layouts.app')

@section('title', 'Tambah Pemasukan Proyek')

@section('content')

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-200 flex justify-center">
            <div class="w-full max-w-2xl">
                <h1 class="text-2xl font-extrabold text-gray-900 mb-8 text-center tracking-tight">
                    Tambah Pemasukan Proyek
                </h1>

                <form action="{{ route('pemasukan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Pilih Proyek --}}
                        <div class="space-y-1">
                            <label for="id_proyek" class="block text-sm font-medium text-gray-700">Proyek</label>
                            <div class="relative">
                                <select name="id_proyek" id="id_proyek"
                                    class="w-full pl-3 pr-10 py-2 text-base border-gray-300 
                    focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 
                    sm:text-sm rounded-md shadow-sm transition duration-150 ease-in-out"
                                    required>
                                    <option value="">-- Pilih Proyek --</option>
                                    @foreach ($proyek as $p)
                                        <option value="{{ $p->id_proyek }}" data-nama="{{ $p->nama_proyek }}">
                                            {{ $p->nama_proyek }} ({{ $p->kode_proyek }})
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="nama_proyek" id="nama_proyek">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Tanggal Pemasukan --}}
                        <div class="space-y-1">
                            <label for="tanggal_pemasukan" class="block text-sm font-medium text-gray-700">Tanggal Pemasukan</label>
                            <input type="date" name="tanggal_pemasukan" id="tanggal_pemasukan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                                required>
                        </div>
                    </div>

                    {{-- Pilih Termin --}}
                    <div class="space-y-1">
                        <label for="id_termin" class="block text-sm font-medium text-gray-700">Termin Pembayaran</label>
                        <div class="relative">
                            <select name="id_termin" id="id_termin"
                                class="w-full pl-3 pr-10 py-2 text-base border-gray-300 bg-gray-50
                focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 
                sm:text-sm rounded-md shadow-sm transition duration-150 ease-in-out"
                                required disabled>
                                <option value="">-- Pilih Proyek Terlebih Dahulu --</option>
                            </select>
                            <input type="hidden" name="termin_ke" id="termin_ke">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Jumlah (readonly dari termin yang dipilih) --}}
                    <div class="space-y-1">
                        <label for="jumlah_display" class="block text-sm font-medium text-gray-700">Jumlah Termin</label>
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="text" id="jumlah_display"
                                class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-900 focus:outline-none transition duration-150 ease-in-out"
                                placeholder="0" readonly>
                            <input type="hidden" name="jumlah" id="jumlah_value">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Jumlah akan otomatis terisi berdasarkan termin yang dipilih</p>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="space-y-1">
                        <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <div class="relative">
                            <select name="metode_pembayaran" id="metode_pembayaran"
                                class="w-full pl-3 pr-10 py-2 text-base border-gray-300 
                focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 
                sm:text-sm rounded-md shadow-sm transition duration-150 ease-in-out"
                                required>
                                <option value="">-- Pilih Metode Pembayaran --</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="Tunai">Tunai</option>
                                <option value="Cek">Cek</option>
                                <option value="Giro">Giro</option>
                                @foreach ($metodePembayaran as $metode)
                                    @if (!in_array($metode, ['Transfer Bank', 'Tunai', 'Cek', 'Giro']))
                                        <option value="{{ $metode }}">{{ $metode }}</option>
                                    @endif
                                @endforeach
                                <option value="Lainnya">Lainnya...</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Input Metode Pembayaran Lainnya --}}
                    <div id="metode_lainnya_wrapper" class="space-y-1 hidden">
                        <label for="metode_lainnya" class="block text-sm font-medium text-gray-700">Metode Pembayaran Lainnya</label>
                        <input type="text" name="metode_lainnya" id="metode_lainnya"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                            placeholder="Tuliskan metode pembayaran...">
                    </div>

                    {{-- Keterangan --}}
                    <div class="space-y-1">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                        <textarea name="keterangan" id="keterangan"
                            class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                            rows="3" placeholder="Masukkan keterangan tambahan..."></textarea>
                    </div>

                    {{-- File Bukti Transfer --}}
                    <div class="space-y-1">
                        <label for="file_bukti" class="block text-sm font-medium text-gray-700">Bukti Pembayaran</label>
                        <div class="mt-1 flex items-center space-x-2">
                            <input type="file" name="file_bukti" id="file_bukti" accept="image/*,application/pdf"
                                class="w-full text-sm text-gray-500
                border border-gray-300 rounded-md
                file:mr-4 file:py-2 file:px-4
                file:rounded-l-md file:border-0
                file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-700
                hover:file:bg-indigo-100"
                                required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF. Maks 2MB.</p>
                    </div>

                    {{-- Status --}}
                    @php
                        $rolesAllowed = ['bod', 'admin keuangan', 'super admin'];
                    @endphp

                    @if (in_array(Auth::user()->role, $rolesAllowed))
                        <div class="space-y-1">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="status" value="Pending">
                    @endif

                    <div class="pt-5">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('pemasukan.index') }}"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
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

    {{-- JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elemen-elemen formulir
            const proyekSelect = document.getElementById('id_proyek');
            const namaProyekInput = document.getElementById('nama_proyek');
            const terminSelect = document.getElementById('id_termin');
            const terminKeInput = document.getElementById('termin_ke');
            const jumlahDisplay = document.getElementById('jumlah_display');
            const jumlahValue = document.getElementById('jumlah_value');
            const metodePembayaranSelect = document.getElementById('metode_pembayaran');
            const metodeLainnyaWrapper = document.getElementById('metode_lainnya_wrapper');
            const metodeLainnyaInput = document.getElementById('metode_lainnya');

            // Fungsi untuk format rupiah
            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID').format(angka);
            }

            // Fungsi reset termin
            function resetTermins() {
                terminSelect.innerHTML = '<option value="">-- Pilih Proyek Terlebih Dahulu --</option>';
                terminSelect.disabled = true;
                terminSelect.classList.add('bg-gray-50');
                terminSelect.classList.remove('bg-white');
                jumlahDisplay.value = '';
                jumlahValue.value = '';
            }

            // Event listener untuk perubahan proyek
            proyekSelect.addEventListener('change', async function() {
                const selectedOption = proyekSelect.options[proyekSelect.selectedIndex];
                namaProyekInput.value = selectedOption.dataset.nama || '';
                
                const idProyek = this.value;
                resetTermins();

                if (!idProyek) return;

                try {
                    const response = await fetch(`/api/termins-by-proyek/${idProyek}`);
                    if (!response.ok) throw new Error('HTTP ' + response.status);
                    
                    const termins = await response.json();

                    if (Array.isArray(termins) && termins.length > 0) {
                        terminSelect.innerHTML = '<option value="">-- Pilih Termin --</option>';
                        
                        termins.forEach(termin => {
                            const option = document.createElement('option');
                            option.value = termin.id;
                            option.textContent = `Termin ${termin.termin_ke} - Rp ${formatRupiah(termin.jumlah)} (${termin.tanggal_jatuh_tempo})`;
                            option.dataset.terminKe = termin.termin_ke;
                            option.dataset.jumlah = termin.jumlah;
                            option.dataset.keterangan = termin.keterangan || '';
                            terminSelect.appendChild(option);
                        });

                        terminSelect.disabled = false;
                        terminSelect.classList.remove('bg-gray-50');
                        terminSelect.classList.add('bg-white');
                    } else {
                        terminSelect.innerHTML = '<option value="">-- Tidak Ada Termin --</option>';
                    }
                } catch (error) {
                    console.error('Error fetching termins:', error);
                    terminSelect.innerHTML = '<option value="">-- Error Memuat Termin --</option>';
                }
            });

            // Event listener untuk perubahan termin
            terminSelect.addEventListener('change', function() {
                const selectedOption = terminSelect.options[terminSelect.selectedIndex];
                
                if (selectedOption && selectedOption.value) {
                    const terminKe = selectedOption.dataset.terminKe;
                    const jumlah = selectedOption.dataset.jumlah;
                    
                    terminKeInput.value = terminKe;
                    jumlahValue.value = jumlah;
                    jumlahDisplay.value = formatRupiah(jumlah);
                } else {
                    terminKeInput.value = '';
                    jumlahValue.value = '';
                    jumlahDisplay.value = '';
                }
            });

            // Event listener untuk metode pembayaran
            metodePembayaranSelect.addEventListener('change', function() {
                if (this.value === 'Lainnya') {
                    metodeLainnyaWrapper.classList.remove('hidden');
                    metodeLainnyaInput.setAttribute('required', 'required');
                } else {
                    metodeLainnyaWrapper.classList.add('hidden');
                    metodeLainnyaInput.removeAttribute('required');
                    metodeLainnyaInput.value = '';
                }
            });

            // Set tanggal hari ini sebagai default
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal_pemasukan').value = today;
        });
    </script>

@endsection