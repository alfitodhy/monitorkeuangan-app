{{-- Tombol Detail --}}
<a href="{{ route('pengeluaran.show', $item->id_pengeluaran) }}" title="Lihat Detail"
    class="inline-flex items-center justify-center w-7 h-7 bg-blue-500 hover:bg-blue-600 text-white rounded shadow">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
</a>

{{-- Tombol Cancel --}}
@if ($item->status === 'Pengajuan')
    <form action="{{ route('pengeluaran.cancel', $item->id_pengeluaran) }}" method="POST"
        class="inline-block cancel-form">
        @csrf
        @method('PATCH')
        <button type="submit" title="Batalkan Pengajuan"
            class="cancel-btn inline-flex items-center justify-center w-7 h-7 bg-red-500 hover:bg-red-600 
                   text-white rounded shadow transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="9" />
                <line x1="15" y1="9" x2="9" y2="15" />
            </svg>
        </button>
    </form>
@endif

{{-- Tambahan khusus role Super Admin & Direktur --}}
@if ($item->status === 'Sudah dibayar' && in_array(auth()->user()->role, ['super admin', 'bod']))
    <form action="{{ route('pengeluaran.cancel', $item->id_pengeluaran) }}" method="POST"
        class="inline-block cancel-form">
        @csrf
        @method('PATCH')
        <button type="submit" title="Batalkan Pengajuan"
            class="cancel-btn inline-flex items-center justify-center w-7 h-7 bg-red-500 hover:bg-red-600 
                   text-white rounded shadow transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="9" />
                <line x1="15" y1="9" x2="9" y2="15" />
            </svg>
        </button>
    </form>
@endif





{{-- Tombol Edit (opsional) --}}
{{-- @if ($item->status === 'Pengajuan' && (in_array(auth()->user()->role, ['bod', 'admin keuangan', 'super admin']) || auth()->user()->id_user === $item->user_created))
    <a href="{{ route('pengeluaran.edit', $item->id_pengeluaran) }}" title="Edit Pengeluaran"
        class="inline-flex items-center justify-center w-7 h-7 bg-yellow-500 hover:bg-yellow-600 text-white rounded shadow">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11 16H7v-4l2-2z" />
        </svg>
    </a>
@endif --}}

{{-- Tombol Hapus --}}
@if (
    $item->status === 'Ditolak' &&
        (in_array(auth()->user()->role, ['super admin']) || auth()->user()->id_user === $item->user_created))
    <form action="{{ route('pengeluaran.destroy', $item->id_pengeluaran) }}" method="POST"
        class="inline-block delete-form">
        @csrf
        @method('DELETE')
        <button type="button" title="Hapus Pengeluaran"
            class="delete-btn inline-flex items-center justify-center w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    </form>
@endif

{{-- Tombol Proses --}}
@if ($item->status === 'Pengajuan' && in_array(auth()->user()->role, ['bod', 'admin keuangan', 'super admin']))
    <div x-data="{ open: false, pilihan: '' }" class="inline-block">
        {{-- Tombol Proses --}}
        <button type="button"
            class="inline-flex items-center justify-center w-7 h-7 bg-orange-500 hover:bg-orange-600 text-white rounded shadow"
            @click="open = true" title="Proses Data Pengeluaran">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>

        {{-- Modal --}}
        <div x-show="open" x-transition x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 w-full max-w-sm transform transition-all duration-300">

                {{-- Icon Header --}}
                <div class="flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mx-auto mb-4">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">
                    Proses Pengeluaran
                </h3>
                <p class="text-sm text-center text-gray-500 dark:text-gray-400 mb-6">
                    Pilih tindakan untuk pengeluaran ini
                </p>

                {{-- Form --}}
                <form action="{{ route('pengeluaran.prosesPengeluaran', $item->id_pengeluaran) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- Dropdown Pilihan --}}
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Pilih Aksi
                    </label>
                    <select name="aksi" x-model="pilihan"
                        class="block w-full text-sm border rounded-md p-2 mb-4 dark:bg-gray-700 dark:text-white">
                        <option value="">-- Pilih --</option>
                        <option value="proses">Proses Data</option>
                        <option value="approve">Langsung Approve</option>
                    </select>

                    {{-- Upload File kalau Approve --}}
                    <div x-show="pilihan === 'approve'" x-transition>
                        <label for="file_buktitf"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Unggah Bukti Transfer
                        </label>
                        <input type="file" name="file_buktitf"
                            class="block w-full text-sm text-gray-600 dark:text-gray-300 
           file:mr-3 file:py-1.5 file:px-3 
           file:rounded-md file:border-0 
           file:bg-gray-100 dark:file:bg-gray-800 
           file:text-gray-700 dark:file:text-gray-300 
           hover:file:bg-gray-200 dark:hover:file:bg-gray-700 
           cursor-pointer mb-4">

                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="open = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md 
                                   hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md 
                                   hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 
                                   focus:ring-offset-2 transition-colors">
                            Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif


{{-- Tombol Approve & Tolak --}}
{{-- Tombol Aksi --}}
@if (in_array(auth()->user()->role, ['bod', 'admin keuangan', 'super admin']) && $item->status === 'Sedang diproses')
    <button type="button" title="Approve" onclick="openApproveModal({{ $item->id_pengeluaran }})"
        class="inline-flex items-center justify-center w-7 h-7 bg-green-500 hover:bg-green-600 text-white rounded shadow">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </button>

    <button type="button" title="Tolak" onclick="openRejectModal({{ $item->id_pengeluaran }})"
        class="inline-flex items-center justify-center w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded shadow">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M18.364 5.636A9 9 0 105.636 18.364 9 9 0 0018.364 5.636z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12" />
        </svg>
    </button>
@endif
<div id="approveModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 w-full max-w-sm">
        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mx-auto mb-4">
            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">
            Approve Pengeluaran
        </h3>
        <form id="approveForm" method="POST" enctype="multipart/form-data">
            @csrf
            <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                Unggah Bukti Transfer
            </label>
            <input type="file" name="file_buktitf" required
                class="block w-full text-sm text-gray-700 dark:text-gray-200 
           file:mr-3 file:py-1.5 file:px-3 
           file:rounded-md file:border-0 
           file:text-xs file:font-medium 
           file:bg-gray-100 file:text-gray-700 
           hover:file:bg-gray-200 
           dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600
           mb-4 border border-gray-300 dark:border-gray-600 rounded-md p-1.5 
           bg-white dark:bg-gray-800 
           focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400 transition-all" />


            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeApproveModal()"
                    class="px-3 py-1.5 text-xs font-medium rounded-md border border-gray-300 dark:border-gray-600 
                   bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 
                   hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="px-3 py-1.5 text-xs font-semibold rounded-md text-white 
                   bg-green-600 hover:bg-green-700 
                   focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1 
                   shadow-sm transition-all">
                    Approve
                </button>
            </div>
        </form>

    </div>
</div>
<div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 w-full max-w-sm">
        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mx-auto mb-4">
            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">
            Tolak Pengeluaran
        </h3>
        <p class="text-sm text-center text-gray-500 dark:text-gray-400 mb-6">
            Mohon berikan alasan penolakan.</p>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="catatan_bod" rows="3" required
                class="w-full p-2.5 border border-gray-300 dark:border-gray-600 rounded-md mb-3 
           bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 
           placeholder-gray-400 dark:placeholder-gray-500 
           focus:ring-2 focus:ring-red-500 focus:border-red-500 shadow-sm text-sm transition-all"></textarea>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeRejectModal()"
                    class="px-3 py-1.5 text-xs font-medium rounded-md border border-gray-300 dark:border-gray-600 
               bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 
               hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="px-3 py-1.5 text-xs font-semibold rounded-md text-white 
               bg-red-600 hover:bg-red-700 
               focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 
               shadow-sm transition-all">
                    Tolak
                </button>
            </div>


        </form>
    </div>
</div>
