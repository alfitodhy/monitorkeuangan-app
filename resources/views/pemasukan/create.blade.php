@extends('layouts.app')

@section('content')

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


    <div class="max-w-4xl mx-auto p-10 bg-white rounded-2xl shadow-xl border border-gray-200 my-10">
        <h2 class="text-lg font-semibold mb-2 text-gray-900 text-left">Tambah Pemasukan Proyek</h2>
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

        <form action="{{ route('pemasukan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                {{-- Proyek --}}
                <div>
                    <label for="id_proyek" class="block text-sm font-medium text-gray-700 mb-0.5">Proyek</label>
                    <select name="id_proyek" id="id_proyek"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                        required>
                        <option value="">-- Pilih Proyek --</option>
                        @foreach ($proyek as $item)
                            <option value="{{ $item->id_proyek }}" data-klien="{{ $item->nama_klien }}"
                                data-termin="{{ $item->termin }}" data-nilai="{{ $item->nilai_project }}"
                                {{ old('id_proyek') == $item->id_proyek ? 'selected' : '' }}>
                                {{ $item->nama_proyek }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama Klien (readonly) --}}
                <div>
                    <label for="nama_klien" class="block text-sm font-medium text-gray-700 mb-0.5">Nama Klien</label>
                    <input type="text" id="nama_klien" name="nama_klien"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900 placeholder:italic placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-gray-100"
                        placeholder="auto by system" value="{{ old('nama_klien') }}" readonly required>

                </div>

                {{-- Termin Ke (readonly) --}}
                <div>
                    <label for="termin_ke" class="block text-sm font-medium text-gray-700 mb-0.5">Termin Ke</label>
                    <input type="text" id="termin_ke" name="termin_ke"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900 placeholder:italic bg-gray-100 shadow-sm"
                        placeholder="Auto by system" readonly>
                </div>

                {{-- Tanggal Pemasukan --}}
                <div>
                    <label for="tanggal_pemasukan" class="block text-sm font-medium text-gray-700 mb-0.5">Tanggal
                        Pemasukan</label>
                    <input type="date" id="tanggal_pemasukan" name="tanggal_pemasukan"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                        value="{{ old('tanggal_pemasukan') }}" required>
                </div>


                {{-- Jumlah --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-0.5">Jumlah</label>
        <input type="text" id="jumlah" name="jumlah"
            class="w-full border border-gray-300 rounded-md p-2 text-sm" 
            placeholder="Masukkan jumlah"
            required>
    </div>

    <div>
        <label for="jumlah_harus_dibayar" class="block text-sm font-medium text-gray-700 mb-0.5">Jumlah yang
            harus dibayar</label>
        <input type="text" id="jumlah_harus_dibayar" name="jumlah_harus_dibayar"
            class="w-full border border-gray-300 rounded-md p-2 text-sm bg-gray-100"
            placeholder="Jumlah yang harus dibayar"
            oninput="updateJumlahBayar()" readonly>
    </div>
</div>
                {{-- Metode Pembayaran --}}
                <div>
                    <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-0.5">
                        Metode Pembayaran
                    </label>
                    <input list="metode-list" name="metode_pembayaran" id="metode_pembayaran"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900"
                        placeholder="Masukkan Metode Pembayaran" value="{{ old('metode_pembayaran') }}">

                    <datalist id="metode-list">
                        @forelse($metodePembayaran as $metode)
                            <option value="{{ $metode }}">
                            @empty
                                {{-- Tidak ada opsi, user bisa ketik manual --}}
                        @endforelse
                    </datalist>
                </div>


                <div>
                    <label for="attachment_file" class="block text-sm font-medium text-gray-700 mb-0.5">Upload Bukti</label>
                    <input type="file" id="attachment_file" class="custom-file-input text-sm" name="attachment_file"
                        accept="image/*">
                </div>

                {{-- Keterangan --}}
                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-0.5">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900 placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                        placeholder="Tambahkan keterangan...">{{ old('keterangan') }}</textarea>
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('pemasukan.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md shadow text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition">
                    Kembali
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md shadow text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>

   <script>
    document.addEventListener('DOMContentLoaded', function() {
        const jumlahInput = document.getElementById('jumlah');
        const jumlahHarusDibayarInput = document.getElementById('jumlah_harus_dibayar');

        // Format angka saat mengetik, biar input tetap bersih dari karakter selain angka
        jumlahInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // hapus semua selain digit angka
            if (value) {
                e.target.value = new Intl.NumberFormat('id-ID').format(value);
            } else {
                e.target.value = '';
            }
        });

        // Pastikan hapus semua titik sebelum submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const jumlahInput = document.getElementById('jumlah');
            if (jumlahInput.value) {
                // Hapus semua tanda titik dan koma jika ada
                jumlahInput.value = jumlahInput.value.replace(/[.,]/g, '');
            }
        });

        // Pindahkan fungsi updateJumlahBayar ke sini untuk memastikan scope yang benar
        function updateJumlahBayar(value) {
            const formattedValue = new Intl.NumberFormat('id-ID').format(value);
            jumlahHarusDibayarInput.value = `Rp ${formattedValue}`;
        }
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('id_proyek').addEventListener('change', async function() {
        const selected = this.options[this.selectedIndex];
        const idProyek = this.value;
        const namaKlien = selected.getAttribute('data-klien') || '';
        const terminInput = document.getElementById('termin_ke');
        const jumlahInput = document.getElementById('jumlah');
        const jumlahHarusDibayarInput = document.getElementById('jumlah_harus_dibayar'); // Ambil elemen baru

        // Reset dulu nilai input yang tergantung proyek
        document.getElementById('nama_klien').value = '';
        terminInput.value = '';
        jumlahInput.placeholder = 'Masukkan jumlah';
        jumlahInput.removeAttribute('data-max');
        jumlahHarusDibayarInput.placeholder = 'Jumlah yang harus dibayar'; // Reset placeholder juga

        if (!idProyek) {
            return;
        }

        try {
            const response = await fetch(`/api/termin-detail/${idProyek}`);
            const data = await response.json();

            const terminSekarang = data.termin_sekarang;
            const totalTerminProyek = data.jumlah_termin; // Ambil jumlah termin total dari API

            // VALIDASI BARU: Cek apakah proyek sudah lunas
            if (terminSekarang > totalTerminProyek) {
                await Swal.fire({
                    icon: 'success', // Ganti icon menjadi success atau info
                    title: 'Proyek Selesai',
                    text: 'Proyek ini sudah lunas. Anda tidak bisa lagi menambah pemasukan.',
                    confirmButtonText: 'OK'
                });

                // Reset pilihan proyek
                this.value = "";
                return;
            }

            if (terminSekarang > 1) {
                const terminSebelumnya = terminSekarang - 1;

                const resPrev = await fetch(`/api/termin-status/${idProyek}/${terminSebelumnya}`);
                const prevData = await resPrev.json();

                if (!prevData.lunas) {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Termin Belum Lunas',
                        text: `Termin sebelumnya (Termin ${terminSebelumnya}) belum lunas! Harap selesaikan dulu.`,
                        confirmButtonText: 'OK'
                    });

                    this.value = "";
                    return;
                }
            }

            // Jika lolos validasi, set field sesuai data
            document.getElementById('nama_klien').value = namaKlien;
            terminInput.value = terminSekarang;

            const maxPerTermin = Math.floor(data.maksimal_nominal);
            
            // Pindah placeholder ke jumlah_harus_dibayar
            jumlahHarusDibayarInput.placeholder = `Rp ${maxPerTermin.toLocaleString('id-ID')}`;
            jumlahInput.setAttribute('data-max', maxPerTermin);

        } catch (err) {
            console.error('Gagal ambil data termin:', err);
        }
    });
</script>

@endsection
