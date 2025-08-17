@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">

        {{-- Pesan Selamat Datang --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-xl font-medium">Selamat datang kembali, {{ Auth::user()->nama_lengkap }}! </h3>
            </div>
        </div>


    </div>
@endsection