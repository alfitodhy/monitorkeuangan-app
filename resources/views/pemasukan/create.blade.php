@extends('layouts.app')

@section('title', 'Tambah Pemasukan Proyek')

@section('content')
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

                {{-- Pilih Proyek --}}
                <div>
                    <label for="id_proyek" class="block text-sm font-medium text-gray-700 mb-0.5">Proyek</label>
                    <select name="id_proyek" id="id_proyek" class="w-full border border-gray-300 rounded-md p-2 text-sm"
                        required>
                        <option value="">-- Pilih Proyek --</option>
                        @foreach ($proyek as $item)
                            <option value="{{ $item->id_proyek }}" data-klien="{{ $item->nama_klien }}">
                                {{ $item->nama_proyek }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama Klien --}}
                <div>
                    <label for="nama_klien" class="block text-sm font-medium text-gray-700 mb-0.5">Nama Klien</label>
                    <input type="text" id="nama_klien" name="nama_klien"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm bg-gray-100" readonly>
                </div>

                {{-- Termin Ke --}}
                <div>
                    <label for="termin_ke" class="block text-sm font-medium text-gray-700 mb-0.5">Termin Ke</label>
                    <input type="text" id="termin_ke" name="termin_ke"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm bg-gray-100" readonly>
                    {{-- hidden id termin --}}
                    <input type="hidden" id="id_termin" name="id_termin">
                </div>


                <div>
                    <label for="jumlah_harus_dibayar" class="block text-sm font-medium text-gray-700 mb-0.5">Jumlah yang
                        harus dibayar</label>
                    <input type="text" id="jumlah_harus_dibayar" name="jumlah_harus_dibayar"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm bg-gray-100" readonly>
                </div>



                {{-- Jumlah dan Jumlah Harus Dibayar 1 baris 2 kolom --}}
                <div class="md:col-span-2 grid grid-cols-2 gap-4">
                    {{-- Jumlah --}}
                    <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-0.5">Jumlah</label>
                        <input type="text" id="jumlah_display"
                            class="w-full border border-gray-300 rounded-md p-2 text-sm" placeholder="Rp. 0">

                        {{-- hidden untuk dikirim ke server --}}
                        <input type="hidden" id="jumlah" name="jumlah">
                    </div>

                    {{-- Tanggal Pemasukan --}}
                    <div>
                        <label for="tanggal_pemasukan" class="block text-sm font-medium text-gray-700 mb-0.5">Tanggal
                            Pemasukan</label>
                        <input type="date" id="tanggal_pemasukan" name="tanggal_pemasukan"
                            class="w-full border border-gray-300 rounded-md p-2 text-sm" required>
                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="md:col-span-2">
                    <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-0.5">Metode
                        Pembayaran</label>
                    <input list="metode-list" name="metode_pembayaran" id="metode_pembayaran"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm"
                        placeholder="Masukkan Metode Pembayaran">
                    <datalist id="metode-list">
                        @foreach ($metodePembayaran as $metode)
                            <option value="{{ $metode }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- Upload Bukti --}}
                <div class="md:col-span-2">
                    <label for="attachment_file" class="block text-sm font-medium text-gray-700 mb-0.5">Upload Bukti</label>
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
                        name="attachment_file" accept="image/*">
                </div>

                {{-- Keterangan --}}
                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-0.5">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3" class="w-full border border-gray-300 rounded-md p-2 text-sm"
                        placeholder="Tambahkan keterangan..."></textarea>
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('pemasukan.index') }}"
                    class="px-4 py-2 text-sm rounded bg-gray-200 hover:bg-gray-300">Kembali</a>
                <button type="submit"
                    class="px-4 py-2 text-sm rounded bg-indigo-600 text-white hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const proyekSelect = document.getElementById('id_proyek');
            const namaKlienInput = document.getElementById('nama_klien');
            const terminInput = document.getElementById('termin_ke');
            const jumlahHarusInput = document.getElementById('jumlah_harus_dibayar');
            const tanggalInput = document.getElementById('tanggal_pemasukan');
            const jumlahDisplay = document.getElementById('jumlah_display');
            const jumlahHidden = document.getElementById('jumlah');

            // Set tanggal default hari ini
            tanggalInput.value = new Date().toISOString().split('T')[0];

            // Format Rupiah
            function formatRupiah(angka, prefix = 'Rp. ') {
                let number_string = angka.toString().replace(/[^0-9]/g, '');
                let sisa = number_string.length % 3;
                let rupiah = number_string.substr(0, sisa);
                let ribuan = number_string.substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                return number_string ? prefix + rupiah : prefix + '0';
            }

            // Event ketik di jumlah_display
            jumlahDisplay.addEventListener('input', function() {
                let rawValue = this.value.replace(/[^0-9]/g, '');
                jumlahHidden.value = rawValue;
                this.value = formatRupiah(rawValue);
            });

            // Saat pilih proyek
            proyekSelect.addEventListener('change', async function() {
                const idProyek = this.value;
                if (!idProyek) {
                    console.log("⚠️ Tidak ada proyek dipilih");
                    namaKlienInput.value = '';
                    terminInput.value = '';
                    jumlahHarusInput.value = '';
                    document.getElementById('id_termin').value = '';
                    return;
                }

                try {
                    console.log("🔎 Fetch ke API termin proyek:", `/api/termin-proyek/${idProyek}`);
                    const res = await fetch(`/api/termin-proyek/${idProyek}`);
                    const data = await res.json();

                    console.log("✅ Response dari API:", data);

                    // Kalau semua termin sudah lunas
                    if (data.termin_lunas) {
                        console.log("🎉 Semua termin lunas");
                        await Swal.fire({
                            icon: 'success',
                            title: 'Proyek Lunas',
                            text: data.message || 'Semua termin sudah dibayar!',
                            confirmButtonText: 'OK'
                        });
                        this.value = '';
                        namaKlienInput.value = '';
                        terminInput.value = '';
                        jumlahHarusInput.value = '';
                        document.getElementById('id_termin').value = '';
                        return;
                    }

                    // Kalau harus lunasi termin sebelumnya
                    if (data.harus_lunasi_termin_sebelumnya) {
                        console.log("⚠️ Ada termin sebelumnya yang belum lunas");
                        await Swal.fire({
                            icon: 'warning',
                            title: 'Belum Bisa Input',
                            text: data.message ||
                                `Silakan lunasi dulu termin ke-${data.termin_yang_harus_dilunasi} sebelum melanjutkan ke termin ke-${data.termin_sekarang}`,
                            confirmButtonText: 'OK'
                        });
                        this.value = '';
                        namaKlienInput.value = '';
                        terminInput.value = '';
                        jumlahHarusInput.value = '';
                        document.getElementById('id_termin').value = '';
                        return;
                    }

                    // Kalau aman → isi otomatis
                    console.log("✅ Aman, isi otomatis field");
                    namaKlienInput.value = data.nama_klien || '';
                    terminInput.value = data.termin_sekarang || '';
                    jumlahHarusInput.value = data.maksimal_nominal ? formatRupiah(data
                        .maksimal_nominal) : '';
                    document.getElementById('id_termin').value = data.id_termin || '';

                } catch (err) {
                    console.error('❌ Gagal ambil data termin:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal mengambil data termin. Cek console untuk detail.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    </script>


@endsection
