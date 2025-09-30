@extends('layouts.app')

@section('title', 'Detail Proyek')

@section('content')




    <div
        class="max-w-7xl mx-auto p-6 lg:p-10 bg-white dark:bg-gray-800 rounded-2xl shadow-xl dark:shadow-none border border-gray-200 dark:border-gray-700 my-10">

        {{-- Judul Utama --}}
        <div class="mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Detail Proyek</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $project->nama_proyek }}</p>
        </div>

        {{-- Konten Utama dengan Grid Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Kolom Kiri: Profil & Waktu --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Kartu: Profil Proyek --}}
                <div class="p-6 border rounded-xl bg-gray-50 dark:bg-gray-900/50 dark:border-gray-700">
                    <h3 class="flex items-center text-xl font-bold mb-4 text-gray-900 dark:text-white">
                        <i class="fa-solid fa-briefcase w-6 h-6 mr-3 text-blue-500"></i>
                        Profil Proyek {{ $project->nama_proyek }}
                    </h3>
                    <hr class="mb-5 border-gray-300 dark:border-gray-600">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-5 text-sm text-gray-700 dark:text-gray-300">
                        <div class="space-y-1">
                            <span class="font-semibold text-gray-900 dark:text-white">Nama Klien</span>
                            <p>{{ $project->nama_klien }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="font-semibold text-gray-900 dark:text-white">Tipe Proyek</span>
                            <p>{{ $project->tipe_proyek == 'Lainnya' ? $project->tipe_lainnya : $project->tipe_proyek }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="font-semibold text-gray-900 dark:text-white">Lokasi Proyek</span>
                            <p>{{ $project->lokasi_proyek ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="font-semibold text-gray-900 dark:text-white">Status Proyek</span>
                            <p>
                                <span
                                    class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $project->status_proyek == 'selesai' ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-50' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-50' }}">
                                    {{ ucfirst($project->status_proyek ?: 'belum mulai') }}
                                </span>
                            </p>
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <span class="font-semibold text-gray-900 dark:text-white">Keterangan</span>
                            <p class="leading-relaxed">{{ $project->keterangan ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Kartu: Waktu & Durasi --}}
                <div class="p-6 border rounded-xl bg-gray-50 dark:bg-gray-900/50 dark:border-gray-700">
                    <h3 class="flex items-center text-xl font-bold mb-4 text-gray-900 dark:text-white">
                        <i class="fa-solid fa-calendar-days w-6 h-6 mr-3 text-red-500"></i>
                        Waktu & Durasi
                    </h3>
                    <hr class="mb-5 border-gray-300 dark:border-gray-600">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-10 gap-y-5 text-sm text-gray-700 dark:text-gray-300">
                        <div class="space-y-1">
                            <span class="font-semibold text-gray-900 dark:text-white">Tanggal Mulai</span>
                            <p>{{ \Carbon\Carbon::parse($project->tanggal_start_proyek)->format('d M Y') }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="font-semibold text-gray-900 dark:text-white">Tanggal Deadline</span>
                            <p>{{ \Carbon\Carbon::parse($project->tanggal_deadline)->format('d M Y') }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="font-semibold text-gray-900 dark:text-white">Durasi Pengerjaan</span>
                            <p>{{ $project->durasi_pengerjaan_bulan }} bulan</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Keuangan --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Kartu: Keuangan Proyek --}}
                <div class="p-6 border rounded-xl bg-gray-50 dark:bg-gray-900/50 dark:border-gray-700">
                    <h3 class="flex items-center text-xl font-bold mb-4 text-gray-900 dark:text-white">
                        <i class="fa-solid fa-coins w-6 h-6 mr-3 text-green-500"></i>
                        Keuangan Proyek
                    </h3>
                    <hr class="mb-5 border-gray-300 dark:border-gray-600">
                    <div class="space-y-5 text-sm text-gray-700 dark:text-gray-300">
                        <div class="space-y-1">
                            <span class="font-semibold text-gray-900 dark:text-white">Nilai Proyek</span>
                            <p>Rp {{ number_format($project->nilai_proyek, 0, ',', '.') }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="font-semibold text-gray-900 dark:text-white">Estimasi HPP</span>
                            <p>{{ number_format($project->estimasi_hpp, 0, ',', '.') }}%</p>
                        </div>
                        <div class="space-y-1">
                            <span class="font-semibold text-gray-900 dark:text-white">Jumlah Termin</span>
                            <p>{{ $project->termin ?? '-' }} Termin</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Addendum --}}
        @if (isset($addendums) && $addendums->count() > 0)
            <div class="mt-8 p-6 border rounded-xl bg-gray-50 dark:bg-gray-900/50 dark:border-gray-700">
                <h3 class="flex items-center text-xl font-bold mb-4 text-gray-900 dark:text-white">
                    <i class="fa-solid fa-file-circle-plus w-6 h-6 mr-3 text-purple-500"></i>
                    Detail Addendum Proyek
                </h3>
                <hr class="mb-5 border-gray-300 dark:border-gray-600">
                <div class="space-y-6">
                    @foreach ($addendums as $index => $addendum)
                        <div class="p-4 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm">
                            <h4 class="font-bold text-base text-gray-800 dark:text-white mb-3">
                                Addendum Ke-{{ $index + 1 }}
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 ml-2">
                                    ({{ \Carbon\Carbon::parse($addendum->tanggal_addendum)->format('d M Y') }})
                                </span>
                            </h4>

                            {{-- Grid Detail Addendum --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-4 text-sm mb-4">
                                <div class="space-y-1">
                                    <span class="font-semibold text-gray-900 dark:text-white">Nilai Proyek Baru:</span>
                                    <p class="text-gray-700 dark:text-gray-300">Rp
                                        {{ number_format($addendum->nilai_proyek_addendum, 0, ',', '.') }}</p>
                                </div>
                                <div class="space-y-1">
                                    <span class="font-semibold text-gray-900 dark:text-white">Estimasi HPP Baru:</span>
                                    <p class="text-gray-700 dark:text-gray-300">
                                        {{ number_format($addendum->estimasi_hpp_addendum, 0, ',', '.') }}%</p>
                                </div>
                                <div class="space-y-1">
                                    <span class="font-semibold text-gray-900 dark:text-white">Tambahan Termin:</span>
                                    <p class="text-gray-700 dark:text-gray-300">
                                        {{ $addendum->tambahan_termin_addendum ?? '0' }}</p>
                                </div>
                            </div>
                            <div class="space-y-1 text-sm">
                                <span class="font-semibold text-gray-900 dark:text-white">Deskripsi:</span>
                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                    {{ $addendum->deskripsi_addendum ?? '-' }}</p>
                            </div>

                            {{-- Lampiran Addendum --}}
                            @if (!empty($addendum->attachment_file))
                                @php
                                    // Pastikan hasil akhirnya array
                                    $addendumAttachments = is_string($addendum->attachment_file)
                                        ? json_decode($addendum->attachment_file, true)
                                        : $addendum->attachment_file;

                                    $baseFolder = 'storage/uploads/proyek/pr_' . $project->id_proyek . '/';
                                @endphp

                                @if ($addendumAttachments && count($addendumAttachments) > 0)
                                    <div class="mt-4">
                                        <h5 class="font-semibold text-sm text-gray-800 dark:text-gray-200 mb-2">Lampiran
                                            Addendum:</h5>
                                        <ul class="text-sm space-y-2">
                                            @foreach ($addendumAttachments as $file)
                                                <li
                                                    class="flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                                                    <i class="fa-solid fa-link mr-2"></i>
                                                    <a href="{{ asset($baseFolder . $file) }}"
                                                        target="_blank">{{ $file }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endif

                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Detail Termin --}}
        {{-- Detail Termin Normal --}}
        @if ($termins && $termins->where('kategori_termin', 'termin')->count() > 0)
            <div class="mt-8 p-6 border rounded-xl bg-gray-50 dark:bg-gray-900/50 dark:border-gray-700">
                <h3 class="flex items-center text-xl font-bold mb-4 text-gray-900 dark:text-white">
                    <i class="fa-solid fa-file-invoice-dollar w-6 h-6 mr-3 text-yellow-500"></i>
                    Detail Pembayaran Termin
                </h3>
                <hr class="mb-5 border-gray-300 dark:border-gray-600">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($termins->where('kategori_termin', 'termin') as $termin)
                        <div class="p-4 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-base text-gray-800 dark:text-white font-semibold">
                                    Termin Ke-{{ $termin->termin_ke }}
                                </span>
                                <span
                                    class="inline-block px-2.5 py-1 rounded-full text-xs font-medium
                            {{ $termin->status_pembayaran == 'lunas'
                                ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-50'
                                : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-50' }}">
                                    {{ ucfirst($termin->status_pembayaran ?? 'Belum Bayar') }}
                                </span>
                            </div>
                            <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1.5 mt-3">
                                <li><span
                                        class="font-semibold text-gray-800 dark:text-gray-200 w-24 inline-block">Jumlah:</span>
                                    Rp {{ number_format($termin->jumlah, 0, ',', '.') }}
                                </li>
                                <li><span class="font-semibold text-gray-800 dark:text-gray-200 w-24 inline-block">Jatuh
                                        Tempo:</span>
                                    {{ \Carbon\Carbon::parse($termin->tanggal_jatuh_tempo)->format('d M Y') }}
                                </li>
                                @if ($termin->status_pembayaran == 'lunas' && $termin->tanggal_lunas)
                                    <li><span class="font-semibold text-gray-800 dark:text-gray-200 w-24 inline-block">Tgl.
                                            Lunas:</span>
                                        {{ \Carbon\Carbon::parse($termin->tanggal_lunas)->format('d M Y') }}
                                    </li>
                                @endif
                                @if (!empty($termin->keterangan) && strtolower($termin->keterangan) !== 'null')
                                    <li class="pt-1">
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-200 block">Keterangan:</span>
                                        {{ $termin->keterangan }}
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif


        {{-- Detail Termin Addendum --}}
        @if ($termins && $termins->count() > 0)
            <div class="mt-8 p-6 border rounded-xl bg-gray-50 dark:bg-gray-900/50 dark:border-gray-700">
                <h3 class="flex items-center text-xl font-bold mb-4 text-gray-900 dark:text-white">
                    <i class="fa-solid fa-file-invoice-dollar w-6 h-6 mr-3 text-yellow-500"></i>
                    Detail Pembayaran Termin
                </h3>
                <hr class="mb-5 border-gray-300 dark:border-gray-600">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($termins as $termin)
                        <div class="p-4 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-base text-gray-800 dark:text-white font-semibold">
                                    Termin Ke-{{ $termin->termin_ke }}
                                    @if ($termin->kategori_termin === 'termin addendum')
                                        (addendum)
                                    @endif
                                </span>
                                <span
                                    class="inline-block px-2.5 py-1 rounded-full text-xs font-medium
                            {{ $termin->status_pembayaran == 'lunas'
                                ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-50'
                                : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-50' }}">
                                    {{ ucfirst($termin->status_pembayaran ?? 'Belum Bayar') }}
                                </span>
                            </div>
                            <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1.5 mt-3">
                                <li>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200 w-24 inline-block">
                                        Jumlah:
                                    </span>
                                    Rp {{ number_format($termin->jumlah, 0, ',', '.') }}
                                </li>
                                <li>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200 w-24 inline-block">
                                        Jatuh Tempo:
                                    </span>
                                    {{ \Carbon\Carbon::parse($termin->tanggal_jatuh_tempo)->format('d M Y') }}
                                </li>
                                @if ($termin->status_pembayaran == 'lunas' && $termin->tanggal_lunas)
                                    <li>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200 w-24 inline-block">
                                            Tgl. Lunas:
                                        </span>
                                        {{ \Carbon\Carbon::parse($termin->tanggal_lunas)->format('d M Y') }}
                                    </li>
                                @endif
                                @if (!empty($termin->keterangan) && strtolower($termin->keterangan) !== 'null')
                                    <li class="pt-1">
                                        <span class="font-semibold text-gray-800 dark:text-gray-200 block">
                                            Keterangan:
                                        </span>
                                        {{ $termin->keterangan }}
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif



        {{-- Lampiran --}}
        <div class="mt-8">
            <h3 class="flex items-center text-xl font-bold mb-4 text-gray-900 dark:text-white">
                <i class="fa-solid fa-paperclip w-6 h-6 mr-3 text-gray-500"></i>
                Lampiran
            </h3>
            @if ($project->attachment_file && count(json_decode($project->attachment_file, true)) > 0)
                @php
                    $attachments = json_decode($project->attachment_file, true);
                    $baseFolder = 'storage/uploads/proyek/pr_' . $project->id_proyek . '/';
                @endphp
                <div class="overflow-x-auto border rounded-lg dark:border-gray-700">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="p-3 text-left w-12">No</th>
                                <th class="p-3 text-left">Nama File</th>
                                <th class="p-3 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach ($attachments as $index => $file)
                                @php
                                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                    $iconClass = match (true) {
                                        in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])
                                            => 'fa-file-image text-purple-500',
                                        in_array($ext, ['pdf']) => 'fa-file-pdf text-red-500',
                                        in_array($ext, ['doc', 'docx']) => 'fa-file-word text-blue-500',
                                        in_array($ext, ['xls', 'xlsx']) => 'fa-file-excel text-green-500',
                                        in_array($ext, ['ppt', 'pptx']) => 'fa-file-powerpoint text-orange-500',
                                        default => 'fa-file text-gray-500',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="p-3 text-center">{{ $index + 1 }}</td>
                                    <td class="p-3">
                                        <div class="flex items-center">
                                            <i class="fa-solid {{ $iconClass }} text-xl mr-3"></i>
                                            <span>{{ $file }}</span>
                                        </div>
                                    </td>
                                    <td class="p-3 text-center">
                                        <a href="{{ asset($baseFolder . $file) }}" target="_blank"
                                            class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 font-semibold">
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div
                    class="border-2 border-dashed rounded-lg p-8 text-center text-gray-500 dark:border-gray-600 dark:text-gray-400">
                    <p>Tidak ada lampiran yang diunggah.</p>
                </div>
            @endif
        </div>

        {{-- Tombol Kembali --}}
        <div class="mt-10 pt-6 border-t dark:border-gray-700 text-right">
            <a href="{{ route('projects.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-white font-semibold text-sm rounded-md shadow-sm transition-colors duration-200">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

@endsection
