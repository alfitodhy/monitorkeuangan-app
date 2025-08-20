<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitor Keuangan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Font dari Google Fonts untuk tampilan yang lebih baik --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 antialiased">

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="grid md:grid-cols-2 grid-cols-1 w-full max-w-4xl bg-white shadow-2xl rounded-2xl overflow-hidden">
            
            <div class="hidden md:flex flex-col justify-between p-10 lg:p-12 bg-gradient-to-br from-blue-600 to-indigo-700 text-white">
                <div>
                    <h1 class="font-bold text-5xl leading-tight">Sistem Monitor Keuangan</h1>
                </div>
                <div class="flex justify-center items-center">
                    {{-- Ikon sederhana sebagai ilustrasi (SVG) --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-48 h-48 text-indigo-300 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-xs text-indigo-200">&copy; {{ date('Y') }} Bantubangun.id</p>
            </div>

            <div class="p-8 md:p-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang!</h2>
                <p class="text-gray-600 mb-8">Silakan masuk untuk melanjutkan.</p>

                {{-- Menampilkan Pesan Error Validasi --}}
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                        <p class="font-bold">Terjadi Kesalahan</p>
                        <ul class="mt-1 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                placeholder="Masukkan Email">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                placeholder="Masukkan Password">
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        {{-- <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember_me" class="ml-2 block text-gray-900">Ingat Saya</label>
                        </div> --}}
                        
                        {{-- @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">
                                Lupa Password?
                            </a>
                        @endif --}}
                    </div>

                    <div>
                        <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                            Masuk
                        </button>
                    </div>

                    {{-- @if (Route::has('register'))
                        <p class="text-center text-sm text-gray-600">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                                Daftar di sini
                            </a>
                        </p>
                    @endif --}}
                </form>
            </div>
        </div>
    </div>

</body>
</html>
