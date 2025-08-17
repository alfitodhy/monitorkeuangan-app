@extends('layouts.app')

@section('content')

    <div class="max-w-6xl mx-auto p-10 bg-white rounded-2xl shadow-xl border border-gray-200 my-10">
        <h2 class="text-lg font-semibold mb-2 text-gray-900 text-left">Detail Proyek</h2>
        <hr class="mb-6 border-gray-300">

        {{-- Grid Detail --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm text-gray-700 dark:text-gray-200">
            <div>
                <span class="font-semibold">Kode Proyek:</span>
                <p>{{ $project->kode_proyek }}</p>
            </div>
            <div>
                <span class="font-semibold">Nama Klien:</span>
                <p>{{ $project->nama_klien }}</p>
            </div>
            <div>
                <span class="font-semibold">Nama Proyek:</span>
                <p>{{ $project->nama_proyek }}</p>
            </div>
            <div>
                <span class="font-semibold">Tipe Proyek:</span>
                <p>{{ $project->tipe_proyek == 'Lainnya' ? $project->tipe_lainnya : $project->tipe_proyek }}</p>
            </div>
            <div>
                <span class="font-semibold">Nilai Proyek:</span>
                <p>Rp {{ number_format($project->nilai_proyek, 0, ',', '.') }}</p>
            </div>
            <div>
                <span class="font-semibold">Estimasi HPP:</span>
                <p>{{ number_format($project->estimasi_hpp, 0, ',', '.') }}%</p>
            </div>
            <div>
                <span class="font-semibold">Tanggal Mulai:</span>
                <p>{{ \Carbon\Carbon::parse($project->tanggal_start_proyek)->format('d M Y') }}</p>
            </div>
            <div>
                <span class="font-semibold">Tanggal Deadline:</span>
                <p>{{ \Carbon\Carbon::parse($project->tanggal_deadline)->format('d M Y') }}</p>
            </div>
            <div>
                <span class="font-semibold">Durasi Pengerjaan:</span>
                <p>{{ $project->durasi_pengerjaan_bulan }} bulan</p>
            </div>
            <div>
                <span class="font-semibold">Status Proyek:</span>
                <p>
                    <span
                        class="inline-block px-2 py-0.5 rounded-full text-[11px] font-medium
                    {{ $project->status_proyek == 'selesai' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' }}">
                        {{ ucfirst($project->status_proyek ?: 'belum mulai') }}
                    </span>
                </p>
            </div>
            <div>
                <span class="font-semibold">Termin:</span>
                <p>{{ $project->termin ?? '-' }}</p>
            </div>
            <div class="sm:col-span-2 pb-4">
                <span class="font-semibold">Keterangan:</span>
                <p>{{ $project->keterangan ?? '-' }}</p>
            </div>


            {{-- Lampiran --}}
            <div class="mb-4 col-span-2">
                <label class="block text-sm font-medium text-gray-700">Lampiran yang Sudah Diupload:</label>

                @if ($project->attachment_file)
                    @php
                        $attachments = json_decode($project->attachment_file, true);
                    @endphp

                    <table class="w-full text-sm mt-2 border border-gray-200">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="p-2 border">No</th>
                                <th class="p-2 border">Preview</th>
                                <th class="p-2 border">Nama File</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                <tr class="border-b">
                                    <td class="p-2 border text-center">{{ $index + 1 }}</td>
                                    <td class="p-2 border text-center">
                                        <i class="fa-solid {{ $iconClass }} text-xl"></i>
                                    </td>
                                    <td class="p-2 border text-blue-600 hover:underline">
                                        <a href="{{ asset($baseFolder . $file) }}" target="_blank">{{ $file }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>

        {{-- Tombol --}}
        {{-- Tombol Kembali --}}
        <div class="mt-8">
            <a href="{{ route('projects.index') }}"
                class="inline-block px-3 py-1 text-xs bg-gray-500 text-white rounded hover:bg-gray-600">
                Kembali
            </a>
        </div>

    </div>


@endsection
