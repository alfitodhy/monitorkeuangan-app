@extends('layouts.app')

@section('title', 'Edit Pengeluaran Proyek')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-200">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8 text-center tracking-tight">
            Edit Pengeluaran Proyek
        </h1>

        {{-- Tampilkan error validasi --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form edit --}}
        <form action="{{ route('pengeluaran.update', $pengeluaran->id_pengeluaran) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Proyek --}}
            <div>
                <label class="block font-semibold mb-2">Proyek</label>
                <select name="id_proyek" class="w-full border rounded-lg p-2">
                    @foreach($proyek as $p)
                        <option value="{{ $p->id_proyek }}" {{ $pengeluaran->id_proyek == $p->id_proyek ? 'selected' : '' }}>
                            {{ $p->nama_proyek }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Vendor --}}
            <div>
                <label class="block font-semibold mb-2">Vendor</label>
                <select name="id_vendor" class="w-full border rounded-lg p-2">
                    @foreach($vendor as $v)
                        <option value="{{ $v->id_vendor }}" {{ $pengeluaran->id_vendor == $v->id_vendor ? 'selected' : '' }}>
                            {{ $v->nama_vendor }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Nama Vendor --}}
            <div>
                <label class="block font-semibold mb-2">Nama Vendor</label>
                <input type="text" name="nama_vendor" value="{{ old('nama_vendor', $pengeluaran->nama_vendor) }}" class="w-full border rounded-lg p-2">
            </div>

            {{-- Rekening --}}
            <div>
                <label class="block font-semibold mb-2">Rekening</label>
                <input type="text" name="rekening" value="{{ old('rekening', $pengeluaran->rekening) }}" class="w-full border rounded-lg p-2">
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="block font-semibold mb-2">Tanggal Pengeluaran</label>
                <input type="date" name="tanggal_pengeluaran" value="{{ old('tanggal_pengeluaran', $pengeluaran->tanggal_pengeluaran) }}" class="w-full border rounded-lg p-2">
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block font-semibold mb-2">Kategori Pengeluaran</label>
                <input type="text" name="kategori_pengeluaran" value="{{ old('kategori_pengeluaran', $pengeluaran->kategori_pengeluaran) }}" class="w-full border rounded-lg p-2">
            </div>

            {{-- Jumlah --}}
            <div>
                <label class="block font-semibold mb-2">Jumlah</label>
                <input type="number" name="jumlah" value="{{ old('jumlah', $pengeluaran->jumlah) }}" class="w-full border rounded-lg p-2">
            </div>

            {{-- Metode Pembayaran --}}
            <div>
                <label class="block font-semibold mb-2">Metode Pembayaran</label>
                <input type="text" name="metode_pembayaran" value="{{ old('metode_pembayaran', $pengeluaran->metode_pembayaran) }}" class="w-full border rounded-lg p-2">
            </div>

            {{-- Status --}}
            <div>
                <label class="block font-semibold mb-2">Status</label>
                <select name="status" class="w-full border rounded-lg p-2">
                    <option value="Belum Dibayar" {{ $pengeluaran->status == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="Sudah Dibayar" {{ $pengeluaran->status == 'Sudah Dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                </select>
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-4">
                <a href="{{ route('pengeluaran.index') }}" class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
