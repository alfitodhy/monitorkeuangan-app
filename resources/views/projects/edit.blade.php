@extends('layouts.app')

@section('content')
    @php
        // Format ribuan untuk nilai proyek di awal
        $formattedNilaiProyek = number_format($project->nilai_proyek, 0, ',', '.');
        $formattedEstimasiHPP = $project->estimasi_hpp . '%';
    @endphp


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
        <h2 class="text-lg font-semibold mb-2 text-gray-900 text-left">Edit Proyek: {{ $project->nama_proyek }}</h2>
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

        <form action="{{ route('projects.update', $project->id_proyek) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <label for="kode_proyek" class="block text-sm font-medium text-gray-700 mb-0.5">Kode Proyek</label>
                    <input type="text" id="kode_proyek" name="kode_proyek"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm"
                        value="{{ old('kode_proyek', $project->kode_proyek) }}" required readonly>
                </div>

                <div>
                    <label for="nama_proyek" class="block text-sm font-medium text-gray-700 mb-0.5">Nama Proyek</label>
                    <input type="text" id="nama_proyek" name="nama_proyek"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm"
                        value="{{ old('nama_proyek', $project->nama_proyek) }}" required>
                </div>

                <div>
                    <label for="nama_klien" class="block text-sm font-medium text-gray-700 mb-0.5">Nama Klien</label>
                    <input type="text" id="nama_klien" name="nama_klien"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm"
                        value="{{ old('nama_klien', $project->nama_klien) }}" required>
                </div>

                {{-- File upload --}}
                <div>
                    <label for="attachment_file" class="block text-sm font-medium text-gray-700 mb-0.5">Tambah
                        Lampiran</label>
                    <input type="file" id="attachment_file" class="custom-file-input text-sm" name="attachment_file[]"
                        multiple>
                </div>

                <div>
                    <label for="nilai_proyek" class="block text-sm font-medium text-gray-700 mb-0.5">
                        Nilai Proyek
                    </label>
                    <input type="text" id="nilai_proyek" name="nilai_proyek"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm" placeholder="Rp 0"
                        value="{{ old('nilai_proyek', $formattedNilaiProyek) }}"
                        oninput="this.value = formatRibuan(this.value)" required>
                </div>

                <div>
                    <label for="estimasi_hpp" class="block text-sm font-medium text-gray-700 mb-0.5">Estimasi HPP
                        (%)</label>
                    <input type="text" id="estimasi_hpp" name="estimasi_hpp"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm"
                        value="{{ old('estimasi_hpp', rtrim(rtrim($project->estimasi_hpp, '0'), '.')) }}">
                </div>

                <div>
                    <label for="termin" class="block text-sm font-medium text-gray-700 mb-0.5">Jumlah Termin</label>
                    <input type="number" id="termin" name="termin"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm"
                        value="{{ old('termin', $project->termin) }}">
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

                <div>
                    <label for="tipe_proyek" class="block text-sm font-medium text-gray-700 mb-0.5">Tipe Proyek</label>
                    <select id="tipe_proyek" name="tipe_proyek" class="w-full border border-gray-300 rounded-md p-2 text-sm"
                        onchange="toggleTipeLainnya()" required>
                        <option value="">-- Pilih Tipe --</option>
                        @foreach (['Design', 'Renovasi', 'Bangun', 'Lainnya'] as $tipe)
                            <option value="{{ $tipe }}"
                                {{ old('tipe_proyek', $project->tipe_proyek) === $tipe ? 'selected' : '' }}>
                                {{ $tipe }}</option>
                        @endforeach
                    </select>
                    <input type="text" id="tipe_lainnya_input" name="tipe_lainnya"
                        class="w-full mt-2 border border-gray-300 rounded-md p-2 text-sm" style="display: none;"
                        value="{{ old('tipe_lainnya', $project->tipe_lainnya) }}"
                        placeholder="Tulis tipe proyek lainnya...">
                </div>

                <div class="flex gap-4">
                    <div class="flex-1">
                        <label for="tanggal_start_proyek" class="block text-sm font-medium text-gray-700 mb-0.5">Tanggal
                            Mulai</label>
                        <input type="date" id="tanggal_start_proyek" name="tanggal_start_proyek"
                            class="w-full border border-gray-300 rounded-md p-2 text-sm"
                            value="{{ old('tanggal_start_proyek', $project->tanggal_start_proyek) }}">
                    </div>
                    <div class="flex-1">
                        <label for="tanggal_deadline" class="block text-sm font-medium text-gray-700 mb-0.5">Tanggal
                            Deadline</label>
                        <input type="date" id="tanggal_deadline" name="tanggal_deadline"
                            class="w-full border border-gray-300 rounded-md p-2 text-sm"
                            value="{{ old('tanggal_deadline', $project->tanggal_deadline) }}">
                    </div>
                </div>

                <div>
                    <label for="durasi_pengerjaan_bulan" class="block text-sm font-medium text-gray-700 mb-0.5">Durasi
                        Pengerjaan (bulan)</label>
                    <input type="number" id="durasi_pengerjaan_bulan" name="durasi_pengerjaan_bulan"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm"
                        value="{{ old('durasi_pengerjaan_bulan', $project->durasi_pengerjaan_bulan) }}">
                </div>

                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-0.5">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm">{{ old('keterangan', $project->keterangan) }}</textarea>
                </div>

                <div class="mb-4 col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Lampiran yang Sudah Diupload:</label>

                    @php
                        $attachments = $project->attachment_file ? json_decode($project->attachment_file, true) : [];
                    @endphp

                    <table class="w-full text-sm mt-2 border border-gray-200">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="p-2 border">No</th>
                                <th class="p-2 border">Preview</th>
                                <th class="p-2 border">Nama File</th>
                                <th class="p-2 border">Ganti File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($attachments) > 0)
                                @foreach ($attachments as $file)
                                    @php
                                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        $iconClass = match (true) {
                                            in_array($ext, ['pdf']) => 'fa-file-pdf text-red-500',
                                            in_array($ext, ['doc', 'docx']) => 'fa-file-word text-blue-500',
                                            in_array($ext, ['xls', 'xlsx']) => 'fa-file-excel text-green-500',
                                            in_array($ext, ['ppt', 'pptx']) => 'fa-file-powerpoint text-orange-500',
                                            default => 'fa-file text-gray-500',
                                        };
                                    @endphp
                                    @php
                                        $baseFolder = 'storage/uploads/proyek/pr_' . $project->id_proyek . '/';
                                    @endphp

                                    <tr class="border-b">
                                        <td class="p-2 border text-center">{{ $loop->iteration }}</td>
                                        <td class="p-2 border text-center">
                                            @php
                                                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                                $iconClass = match (true) {
                                                    in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])
                                                        => 'fa-file-image text-purple-500',
                                                    in_array($ext, ['pdf']) => 'fa-file-pdf text-red-500',
                                                    in_array($ext, ['doc', 'docx']) => 'fa-file-word text-blue-500',
                                                    in_array($ext, ['xls', 'xlsx']) => 'fa-file-excel text-green-500',
                                                    in_array($ext, ['ppt', 'pptx'])
                                                        => 'fa-file-powerpoint text-orange-500',
                                                    default => 'fa-file text-gray-500',
                                                };
                                            @endphp
                                            <i class="fa-solid {{ $iconClass }} text-xl"></i>
                                        </td>
                                        <td class="p-2 border text-blue-600 hover:underline">
                                            <a href="{{ asset($baseFolder . $file) }}"
                                                target="_blank">{{ $file }}</a>
                                        </td>
                                        <td class="p-2 border text-center">
                                            <input type="file" name="replace_file[{{ $file }}]"
                                                class="text-sm text-gray-700">
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-gray-500 italic">
                                        Belum ada lampiran yang diupload.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>





            </div>

            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('projects.index') }}"
                    class="px-4 py-2 text-sm rounded-md bg-gray-200 hover:bg-gray-300">Batal</a>
                <button type="submit"
                    class="px-4 py-2 text-sm rounded-md bg-indigo-600 text-white hover:bg-indigo-700">Simpan
                    Perubahan</button>
            </div>
        </form>
    </div>

    <script>
        function formatRibuan(angka) {
            angka = angka.replace(/\D/g, '');
            return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function toggleTipeLainnya() {
            const select = document.getElementById('tipe_proyek');
            const inputLainnya = document.getElementById('tipe_lainnya_input');
            if (select.value === 'Lainnya') {
                inputLainnya.style.display = 'block';
                inputLainnya.required = true;
            } else {
                inputLainnya.style.display = 'none';
                inputLainnya.required = false;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleTipeLainnya();
        });
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
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            }

            function hitungHppProfit() {
                let nilaiProyekStr = nilaiProyekInput.value.replace(/\D/g, '');
                let nilaiProyek = parseFloat(nilaiProyekStr) || 0;

                // Ambil nilai estimasi HPP tanpa '%' dan koma, kemudian parse ke float
                let estimasiHppStr = estimasiHppInput.value.replace(/[^0-9]/g, '');
                let estimasiHpp = parseFloat(estimasiHppStr) || 0;

                let nominalHpp = (nilaiProyek * estimasiHpp) / 100;
                let profit = nilaiProyek - nominalHpp;

                nominalHppInput.value = nominalHpp > 0 ? formatRupiah(nominalHpp) : '';
                profitInput.value = profit > 0 ? formatRupiah(profit) : '';
            }

            // Jalankan saat halaman dimuat untuk menghitung nilai awal
            hitungHppProfit();

            // Memastikan nilai estimasi HPP awal memiliki simbol %
            if (estimasiHppInput.value) {
                estimasiHppInput.value = estimasiHppInput.value + '%';
            }

            nilaiProyekInput.addEventListener('input', function(e) {
                let val = e.target.value.replace(/\D/g, '');
                if (val) {
                    e.target.value = new Intl.NumberFormat('id-ID').format(val);
                } else {
                    e.target.value = '';
                }
                hitungHppProfit();
            });

            estimasiHppInput.addEventListener('input', function(e) {
                let val = e.target.value.replace(/[^0-9]/g, '');

                if (val) {
                    e.target.value = val + '%';
                } else {
                    e.target.value = '';
                }

                hitungHppProfit();
            });
        });
    </script>
@endsection
