@extends('layouts.app')

@section('title', 'Detail Pemasukan Proyek')

@section('content')

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ $errors->first() }}</span>
        </div>
    @endif

    <div x-data="{ modalOpen: null, proofModalData: null }" class="relative z-0 min-h-screen bg-white">
        <div class="container mx-auto p-4 sm:p-6 relative z-0">
            <div class="flex items-center justify-between mb-4 lg:mb-6">
                <h1 class="text-xl lg:text-2xl font-bold text-gray-800">{{ $proyek->nama_proyek }}</h1>
                <a href="{{ route('pemasukan.index') }}"
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md shadow-md text-gray-700 bg-white hover:bg-gray-100 focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200">
                    <p class="text-xs font-medium text-gray-500">Total Termin</p>
                    <p class="text-xl font-bold text-gray-900 mt-0.5">{{ $proyek->total_termin }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200">
                    <p class="text-xs font-medium text-gray-500">Nilai Proyek</p>
                    <p class="text-xl font-bold text-gray-900 mt-0.5">Rp
                        {{ number_format($proyek->nilai_proyek, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200">
                    <p class="text-xs font-medium text-gray-500">Total Dibayar</p>
                    <p class="text-xl font-bold text-green-600 mt-0.5">Rp
                        {{ number_format($total_dibayar, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200">
                    <p class="text-xs font-medium text-gray-500">Sisa Bayar</p>
                    <p class="text-xl font-bold text-red-600 mt-0.5">Rp
                        {{ number_format($sisa_bayar, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Termin Ke</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Nominal Seharusnya</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Total Dibayar</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Sisa Bayar</th>
                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Lihat Bukti</th>
                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php
                            $allow_payment = true;
                        @endphp
                        @foreach ($termins as $termin)
                            @php
                                $is_paid = $termin->total_bayar >= $termin->nilai_termin;
                                $status = $is_paid ? 'Lunas' : 'Belum Lunas';
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{ $termin->termin_ke }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-right text-gray-700">Rp
                                    {{ number_format($termin->nilai_termin, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-right text-green-600 font-medium">Rp
                                    {{ number_format($termin->total_bayar, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-right text-red-600 font-medium">Rp
                                    {{ number_format($termin->sisa_bayar, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-center">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-xs font-bold
                                        {{ $is_paid ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    @if (isset($bukti_per_termin[$termin->id_termin]) && $bukti_per_termin[$termin->id_termin]->isNotEmpty())
                                        <button
                                            @click="proofModalData = {
                                                termin_ke: {{ $termin->termin_ke }},
                                                bukti: @js($bukti_per_termin[$termin->id_termin]->map(fn($b) => ['file' => $b->attachment_file]))
                                            }"
                                            class="px-3 py-1 text-blue-500 rounded-lg shadow">
                                            Lihat Bukti
                                        </button>
                                    @else
                                        <span class="text-gray-500 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-center">
                                    @if (!$is_paid && $allow_payment)
                                        <button @click="modalOpen = {{ $termin->termin_ke }}"
                                            class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                            Lunasi
                                        </button>
                                    @elseif($is_paid)
                                        <span class="text-gray-400 text-sm"></span>
                                    @else
                                        <span class="text-gray-400 text-sm"></span>
                                    @endif
                                </td>
                            </tr>
                            @php
                                if (!$is_paid) {
                                    $allow_payment = false;
                                }
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="proofModalData" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;"
            @click.away="proofModalData = null">
            <div x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                class="bg-white rounded-lg p-6 w-11/12 max-w-xl relative z-50 shadow-lg" @click.stop>

                <h2 class="text-lg font-bold text-gray-800 mb-4">Bukti Pembayaran Termin <span
                        x-text="proofModalData ? proofModalData.termin_ke : ''"></span></h2>

                <div x-data="{ currentFile: null, isImage: true }" class="space-y-4">
                    <template x-if="proofModalData">
                        <div x-init="if (proofModalData.bukti.length > 0) {
                            currentFile = proofModalData.bukti[0].file;
                            isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(currentFile.split('.').pop().toLowerCase());
                        }">
                            <div class="w-full h-64 bg-gray-100 border border-gray-200 rounded-md flex items-center justify-center overflow-hidden mb-4">
                                <template x-if="isImage">
                                    <img :src="'{{ asset('storage') }}/' + currentFile" alt="Bukti Pembayaran" class="object-contain w-full h-full">
                                </template>
                                <template x-if="!isImage">
                                    <embed :src="'{{ asset('storage') }}/' + currentFile" type="application/pdf" class="w-full h-full">
                                </template>
                            </div>

                            <ul class="space-y-1 max-h-40 overflow-y-auto pr-2">
                                <template x-for="(file, index) in proofModalData.bukti" :key="index">
                                    <li>
                                        <button
                                            @click="currentFile = file.file; isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(file.file.split('.').pop().toLowerCase());"
                                            :class="{ 'font-bold text-blue-800': currentFile === file.file, 'text-gray-600': currentFile !== file.file }"
                                            class="text-sm hover:underline">
                                            <i class="fas fa-file-alt mr-2"></i> Bukti Pembayaran ke-<span x-text="index + 1"></span>
                                        </button>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>
                </div>
                
                <div class="flex justify-end mt-4">
                    <button type="button" @click="proofModalData = null"
                        class="px-3 py-1.5 text-sm text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-colors duration-150">
                        Tutup
                    </button>
                </div>

                <button @click="proofModalData = null"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 transition-colors duration-150"
                    aria-label="Close modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        @foreach ($termins as $termin)
            <div x-show="modalOpen === {{ $termin->termin_ke }}" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;"
                @click.away="modalOpen = null">
                <div x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200 transform"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 w-11/12 max-w-sm relative z-50" @click.stop>

                    <div class="flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mx-auto mb-4">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">Lunasi Termin
                        ke-{{ $termin->termin_ke }}</h2>
                    <p class="text-sm text-center text-gray-500 dark:text-gray-400 mb-6">Lengkapi data pembayaran untuk
                        melunasi termin ini.</p>

                    <form method="POST"
                        action="{{ route('pemasukan.updateLunasi', [$proyek->id_proyek, $termin->termin_ke]) }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah yang
                                harus dilunasi</label>
                            <input type="text" value="Rp. {{ number_format($termin->sisa_bayar, 0, ',', '.') }}"
                                readonly
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 cursor-not-allowed text-sm">
                            <input type="hidden" name="sisa_bayar_termin" value="{{ $termin->sisa_bayar }}">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                for="metode_pembayaran_{{ $termin->termin_ke }}">Metode Pembayaran</label>
                            <input list="metode-list" name="metode_pembayaran"
                                id="metode_pembayaran_{{ $termin->termin_ke }}"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-sm text-gray-900 dark:text-gray-300 bg-white dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan Metode Pembayaran" value="{{ old('metode_pembayaran') }}">
                            <datalist id="metode-list">
                                @forelse($metodePembayaran as $metode)
                                    <option value="{{ $metode }}">
                                    @empty
                                        {{-- Tidak ada opsi, user bisa ketik manual --}}
                                @endforelse
                            </datalist>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                for="jumlah_bayar_{{ $termin->termin_ke }}">Jumlah Bayar</label>
                            <input type="text" name="jumlah_bayar" id="jumlah_bayar_{{ $termin->termin_ke }}"
                                x-data="rupiahInput()" x-on:input="onInput" x-init="if (value) $el.value = formatRupiah(value)"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm text-gray-900 dark:text-gray-300 bg-white dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150"
                                placeholder="Masukkan jumlah pembayaran" required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                for="attachment_file_{{ $termin->termin_ke }}">Bukti Pembayaran</label>
                            <input type="file" name="attachment_file" id="attachment_file_{{ $termin->termin_ke }}"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"
                                accept=".pdf,.jpg,.jpeg,.png">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: PDF, JPG, PNG (maks. 2MB)</p>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="modalOpen = null"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                Bayar
                            </button>
                        </div>
                    </form>

                    <button @click="modalOpen = null"
                        class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 transition-colors duration-150"
                        aria-label="Close modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('rupiahInput', () => ({
                value: '',
                onInput(event) {
                    let input = event.target;
                    let originalValue = input.value.replace(/[^,\d]/g, '').toString();
                    let formattedValue = this.formatRupiah(originalValue);
                    input.value = formattedValue;
                },
                formatRupiah(numberString) {
                    if (!numberString) return '';
                    let split = numberString.split(',');
                    let sisa = split[0].length % 3;
                    let rupiah = split[0].substr(0, sisa);
                    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                    if (ribuan) {
                        let separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }

                    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                    return 'Rp. ' + rupiah;
                },
                getRawNumber(formatted) {
                    return formatted.replace(/[^,\d]/g, '').replace(',', '.');
                }
            }))
        })
    </script>
@endsection