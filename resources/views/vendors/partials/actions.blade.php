<a href="{{ route('vendors.show', $item->id_vendor) }}"
   title="Lihat Detail"
   class="inline-flex items-center justify-center w-7 h-7 bg-blue-500 hover:bg-blue-600 text-white rounded shadow">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
    </svg>
</a>

<a href="{{ route('vendors.edit', $item->id_vendor) }}"
   title="Edit Vendor"
   class="inline-flex items-center justify-center w-7 h-7 bg-yellow-500 hover:bg-yellow-600 text-white rounded shadow">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11 16H7v-4l2-2z"/>
    </svg>
</a>

<form action="{{ route('vendors.destroy', $item->id_vendor) }}" method="POST" class="inline-block delete-form">
    @csrf
    @method('DELETE')
    <button type="button"
            title="Hapus Vendor"
            class="delete-btn inline-flex items-center justify-center w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded shadow">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</form>
