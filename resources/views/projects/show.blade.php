@extends('layouts.app')

@section('title', 'Detail Proyek')

@section('content')

    <div
        class="max-w-6xl mx-auto p-10 bg-white dark:bg-gray-800 rounded-2xl shadow-xl dark:shadow-none border border-gray-200 dark:border-gray-700 my-10">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white text-left">Detail Proyek</h2>
        <hr class="mb-6 border-gray-300 dark:border-gray-600">

        {{-- Grid Detail --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 text-sm text-gray-700 dark:text-gray-300">

            <div class="space-y-1">
                <span class="font-semibold text-gray-900 dark:text-white">Nama Klien:</span>
                <p>{{ $project->nama_klien }}</p>
            </div>
            <div class="space-y-1">
                <span class="font-semibold text-gray-900 dark:text-white">Nama Proyek:</span>
                <p>{{ $project->nama_proyek }}</p>
            </div>
            <div class="space-y-1">
                <span class="font-semibold text-gray-900 dark:text-white">Tipe Proyek:</span>
                <p>{{ $project->tipe_proyek == 'Lainnya' ? $project->tipe_lainnya : $project->tipe_proyek }}</p>
            </div>
            <div class="space-y-1">
                <span class="font-semibold text-gray-900 dark:text-white">Nilai Proyek:</span>
                <p>Rp {{ number_format($project->nilai_proyek, 0, ',', '.') }}</p>
            </div>
            <div class="space-y-1">
                <span class="font-semibold text-gray-900 dark:text-white">Estimasi HPP:</span>
                <p>{{ number_format($project->estimasi_hpp, 0, ',', '.') }}%</p>
            </div>
            <div class="space-y-1">
                <span class="font-semibold text-gray-900 dark:text-white">Tanggal Mulai:</span>
                <p>{{ \Carbon\Carbon::parse($project->tanggal_start_proyek)->format('d M Y') }}</p>
            </div>
            <div class="space-y-1">
                <span class="font-semibold text-gray-900 dark:text-white">Tanggal Deadline:</span>
                <p>{{ \Carbon\Carbon::parse($project->tanggal_deadline)->format('d M Y') }}</p>
            </div>
            <div class="space-y-1">
                <span class="font-semibold text-gray-900 dark:text-white">Durasi Pengerjaan:</span>
                <p>{{ $project->durasi_pengerjaan_bulan }} bulan</p>
            </div>
            <div class="space-y-1">
                <span class="font-semibold text-gray-900 dark:text-white">Lokasi Proyek:</span>
                <p>{{ $project->lokasi_proyek ?? '-' }}</p>
            </div>
            <div class="space-y-1">
                <span class="font-semibold text-gray-900 dark:text-white">Status Proyek:</span>
                <p>
                    <span
                        class="inline-block px-2 py-0.5 rounded-full text-xs font-medium
                {{ $project->status_proyek == 'selesai' ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-50' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-50' }}">
                        {{ ucfirst($project->status_proyek ?: 'belum mulai') }}
                    </span>
                </p>
            </div>
            <div class="space-y-1">
                <span class="font-semibold text-gray-900 dark:text-white">Termin:</span>
                <p>{{ $project->termin ?? '-' }}</p>
            </div>
            <div class="md:col-span-2 space-y-1">
                <span class="font-semibold text-gray-900 dark:text-white">Keterangan:</span>
                <p>{{ $project->keterangan ?? '-' }}</p>
            </div>
        </div>




        {{-- Detail Termin --}}
        @if ($termins && $termins->count() > 0)
            <div class="mt-8">
                <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Detail Termin</h3>
                <div class="space-y-4">
                    @foreach ($termins as $termin)
                        <div class="p-5 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
                                <span class="text-base text-gray-800 dark:text-white font-semibold">Termin
                                    Ke-{{ $termin->termin_ke }}</span>
                                <span
                                    class="inline-block mt-2 sm:mt-0 px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $termin->status_pembayaran == 'lunas' ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-50' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-50' }}">
                                    {{ ucfirst($termin->status_pembayaran ?? 'Belum Bayar') }}
                                </span>
                            </div>
                            <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                                <li>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">Jumlah:</span> Rp
                                    {{ number_format($termin->jumlah, 0, ',', '.') }}
                                </li>
                                <li>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">Jatuh Tempo:</span>
                                    {{ \Carbon\Carbon::parse($termin->tanggal_jatuh_tempo)->format('d M Y') }}
                                </li>
                                @if ($termin->status_pembayaran == 'lunas' && $termin->tanggal_lunas)
                                    <li>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">Tanggal Lunas:</span>
                                        {{ \Carbon\Carbon::parse($termin->tanggal_lunas)->format('d M Y') }}
                                    </li>
                                @endif
                                @if (!empty($termin->keterangan) && strtolower($termin->keterangan) !== 'null')
                                    <li>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">Keterangan:</span>
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
            <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Lampiran</h3>
            @if ($project->attachment_file)
                @php
                    $attachments = json_decode($project->attachment_file, true);
                @endphp
                @if (count($attachments) > 0)
                    <div class="overflow-x-auto">
                        <table
                            class="w-full text-sm border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                <tr>
                                    <th class="p-3 border-r dark:border-gray-600 text-left w-12">No</th>
                                    <th class="p-3 border-r dark:border-gray-600 text-left">Nama File</th>
                                    <th class="p-3 text-left w-24">Preview</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                @php
                                    $baseFolder = 'storage/uploads/proyek/pr_' . $project->id_proyek . '/';
                                @endphp
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
                                    <tr>
                                        <td class="p-3 border-r dark:border-gray-600 text-center">{{ $index + 1 }}</td>
                                        <td
                                            class="p-3 border-r dark:border-gray-600 text-blue-500 dark:text-blue-400 hover:underline">
                                            <a href="{{ asset($baseFolder . $file) }}"
                                                target="_blank">{{ $file }}</a>
                                        </td>
                                        <td class="p-3 text-center">
                                            <i class="fa-solid {{ $iconClass }} text-xl"></i>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 italic">Tidak ada lampiran yang diunggah.</p>
                @endif
            @else
                <p class="text-gray-500 dark:text-gray-400 italic">Tidak ada lampiran yang diunggah.</p>
            @endif
        </div>



        {{-- Tombol --}}
        <div class="mt-8 text-right">
            <a href="{{ route('projects.index') }}"
                class="inline-flex items-center px-3 py-1 text-xs bg-gray-500 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-white rounded-md transition-colors duration-200">
                <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

@endsection
