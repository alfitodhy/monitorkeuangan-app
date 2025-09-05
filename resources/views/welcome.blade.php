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
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    {{-- Font Awesome untuk ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 antialiased transition-colors duration-500 ease-in-out">

    <div class="min-h-screen flex items-center justify-center p-4 relative">
        <div
            class="grid md:grid-cols-2 grid-cols-1 w-full max-w-4xl bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden transition-colors duration-500 ease-in-out">

            {{-- Bagian Kiri (Informasi/Branding) --}}
            <div
                class="hidden md:flex flex-col justify-between p-10 lg:p-12 bg-gradient-to-br from-blue-600 to-indigo-700 text-white relative overflow-hidden">
                <div class="absolute inset-0 z-0 opacity-10">
                    {{-- Latar belakang pola geometris --}}
                    <svg class="w-full h-full" fill="none" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <pattern id="pattern-circles" x="0" y="0" width="10" height="10"
                            patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1" fill="currentColor" opacity="0.1" />
                        </pattern>
                        <rect x="0" y="0" width="100%" height="100%" fill="url(#pattern-circles)"
                            class="text-indigo-200 dark:text-blue-300" />
                    </svg>
                </div>
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div>
                        <h1 class="font-bold text-4xl lg:text-5xl leading-tight mb-4">Sistem Monitor Keuangan</h1>
                    </div>
                    <div class="flex justify-center items-center my-8">
                        {{-- Ikon lebih modern atau ilustrasi sederhana --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-48 h-48 text-indigo-300 opacity-50"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-xs text-indigo-200 mt-auto">&copy; {{ date('Y') }} Bantubangun.id. All rights
                        reserved.</p>
                </div>
            </div>

            {{-- Bagian Kanan (Form Login) --}}
            <div class="p-8 md:p-12 flex flex-col justify-center">
                <h2
                    class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-2 transition-colors duration-500 ease-in-out">
                    Selamat Datang Kembali!</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-8 transition-colors duration-500 ease-in-out">Silakan
                    masuk ke akun Anda.</p>

                {{-- Menampilkan Pesan Error Validasi --}}
                @if ($errors->any())
                    <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 dark:border-red-600 text-red-700 dark:text-red-300 p-4 mb-6 rounded-md transition-colors duration-500 ease-in-out"
                        role="alert">
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
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1 transition-colors duration-500 ease-in-out">Alamat
                            Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                            autofocus autocomplete="email"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all duration-500 ease-in-out"
                            placeholder="name@example.com">
                    </div>

                    <div>
                        <label for="password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1 transition-colors duration-500 ease-in-out">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required
                                autocomplete="current-password"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 pr-10 transition-all duration-500 ease-in-out"
                                placeholder="********">
                            <button type="button" id="toggle-password"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                            Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Skrip untuk toggle visibilitas password
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            // Toggle ikon mata
            togglePassword.querySelector('i').classList.toggle('fa-eye');
            togglePassword.querySelector('i').classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
