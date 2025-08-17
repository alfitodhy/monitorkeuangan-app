@extends('layouts.app')
@section('title', 'Tambah User')

@section('content')
<div class="container mx-auto px-4 py-8 md:px-8 lg:px-12">
    <div class="max-w-5xl mx-auto bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-6 md:p-10">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-8 text-left">Tambah Pengguna Baru</h2>
        
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Kolom Kiri untuk Input Form --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_lengkap" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 text-sm transition-colors" 
                                   value="{{ old('nama_lengkap') }}" required>
                            @error('nama_lengkap')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Username</label>
                            <input type="text" name="username" id="username" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 text-sm transition-colors" 
                                   value="{{ old('username') }}" required>
                            @error('username')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative">
                            <label for="password" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Password</label>
                            <input type="password" name="password" id="password" 
                                   class="mt-1 block w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-sm transition-colors" 
                                   required>
                            <span id="toggle-password" class="absolute inset-y-0 right-0 top-6 pr-3 flex items-center text-sm leading-5 cursor-pointer text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                <svg id="icon-eye-open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.575 3.01 9.963 7.172a.997.997 0 010 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.575-3.01-9.963-7.172z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg id="icon-eye-closed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.774 3.162 10.066 7.472a10.476 10.476 0 01-2.92 7.722M10.5 13.5h.008v.008h-.008v-.008zm1.5-1.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Email</label>
                            <input type="email" name="email" id="email" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 text-sm transition-colors" 
                                   value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="no_telp" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">No. Telepon</label>
                            <input type="text" name="no_telp" id="no_telp" 
                                   value="{{ old('no_telp') }}"
                                   oninput="this.value = this.value.replace(/[^0-9+\-()\s]/g, '')"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 text-sm transition-colors">
                            @error('no_telp')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Role</label>
                            <select name="role" id="role" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-sm transition-colors" 
                                    required>
                                <option value="">Pilih Role</option>
                                <option value="admin keuangan" {{ old('role') == 'admin keuangan' ? 'selected' : '' }}>Admin Keuangan</option>
                                <option value="kepala operational" {{ old('role') == 'kepala operational' ? 'selected' : '' }}>Kepala Operational</option>
                                <option value="bod" {{ old('role') == 'bod' ? 'selected' : '' }}>BOD</option>
                                <option value="super admin" {{ old('role') == 'super admin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label for="status_aktif" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Status Aktif</label>
                        <select name="status_aktif" id="status_aktif" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-sm transition-colors" 
                                required>
                            <option value="aktif" {{ old('status_aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak aktif" {{ old('status_aktif') == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status_aktif')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Kolom Kanan untuk Foto Profil --}}
                <div class="lg:col-span-1 flex flex-col items-center justify-center space-y-4">
    <!-- Label -->
    <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 text-center">Foto Profil</label>

    <!-- Gambar atau icon user -->
    <div class="relative">
        <img id="image-preview" src="https://via.placeholder.com/150/E5E7EB/6B7280?text=" 
             class="h-40 w-40 object-cover border-4 border-gray-200 dark:border-gray-700 shadow-lg rounded-full bg-gray-100">

        <!-- Icon user di tengah -->
        <div class="absolute inset-0 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-16 h-16 text-gray-400" viewBox="0 0 24 24">
                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v1.2h19.2v-1.2c0-3.2-6.4-4.8-9.6-4.8z"/>
            </svg>
        </div>
    </div>

    <!-- Tombol Pilih Foto -->
    <label class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out flex items-center space-x-2">
        <!-- Icon upload -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-4.5-3L12 6m0 0L7.5 13.5M12 6v12" />
        </svg>
        <span id="upload-button-text">Pilih Foto</span>
        <input type="file" name="foto_profil" id="foto_profil" class="hidden" accept="image/*">
    </label>

    <!-- Error -->
    @error('foto_profil')
        <p class="mt-1 text-xs text-red-600 text-center">{{ $message }}</p>
    @enderror
</div>

            </div>
            
        <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700 mt-8">
    <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 flex items-center space-x-2 text-sm">
        Batal
    </a>
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 text-sm">
        Simpan
    </button>
</div>

        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('toggle-password');
        const iconEyeOpen = document.getElementById('icon-eye-open');
        const iconEyeClosed = document.getElementById('icon-eye-closed');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            if (type === 'password') {
                iconEyeOpen.classList.remove('hidden');
                iconEyeClosed.classList.add('hidden');
            } else {
                iconEyeOpen.classList.add('hidden');
                iconEyeClosed.classList.remove('hidden');
            }
        });

        const imageInput = document.getElementById('foto_profil');
        const imagePreview = document.getElementById('image-preview');
        const uploadButtonText = document.getElementById('upload-button-text');
        
        imageInput.addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                imagePreview.src = URL.createObjectURL(file);
                uploadButtonText.textContent = 'Ganti Foto';
            }
        });
    });
</script>
@endsection