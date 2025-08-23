<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Monitor Keuangan Proyek')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdn.jsdelivr.net/npm/@heroicons/vue@2.1.3/dist/heroicons.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.tailwindcss.css">
    <!-- jQuery dulu -->
    <!-- jQuery (untuk Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.tailwindcss.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
    $user = Auth::user();
    $role = $user->role;
@endphp

<body class="font-sans antialiased text-gray-900 bg-gray-50 dark:bg-gray-900 dark:text-gray-100">

    <div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" class="flex h-screen overflow-hidden">
        <div x-show="sidebarOpen" x-transition.opacity.duration.300ms @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"></div>

        <aside
            class="flex-shrink-0 w-64 flex flex-col bg-white dark:bg-gray-800 transition-all duration-300 ease-in-out transform fixed inset-y-0 left-0 z-50 overflow-y-auto lg:static lg:transform-none lg:shadow-none shadow-xl"
            :class="{ '-translate-x-full': !sidebarOpen }">

            <div
                class="h-16 flex items-center justify-center flex-shrink-0 px-6 bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                    Bantubangun.id
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 pt-6">
                <ul class="space-y-2">

                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center p-3 text-sm font-semibold rounded-lg transition-all duration-200
                            {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                    </li>


                    {{-- Master Data (Dropdown) --}}
                    @if ($role === 'super admin' || $role === 'admin keuangan' || $role === 'bod')
                        {{-- Menu Master Data --}}
                        <li x-data="{ open: {{ request()->routeIs('projects.*') || request()->routeIs('vendors.*') ? 'true' : 'false' }} }" class="relative">
                            <button @click="open = !open"
                                class="flex items-center justify-between w-full p-3 text-sm font-semibold rounded-lg transition-colors duration-200
            {{ request()->routeIs('projects.*') || request()->routeIs('vendors.*')
                ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2h-3m-4 0H6a2 2 0 00-2 2v7a2 2 0 002 2h12a2 2 0 002-2z" />
                                    </svg>
                                    Master Data
                                </div>
                                <svg class="w-4 h-4 ml-2 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            {{-- Dropdown (nested list) --}}
                            <ul x-show="open" x-transition class="ml-8 mt-1 space-y-1">
                                <li>
                                    <a href="{{ route('projects.index') }}"
                                        class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                    {{ request()->routeIs('projects.*')
                        ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200 font-semibold'
                        : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        Management Proyek
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('vendors.index') }}"
                                        class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                    {{ request()->routeIs('vendors.*')
                        ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200 font-semibold'
                        : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        Management Vendor
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif




                    {{-- Monitor Pemasukan Proyek --}}
                    @if ($role === 'super admin' || $role === 'admin keuangan' || $role === 'bod')
                        <li>
                            <a href="{{ route('pemasukan.index') }}"
                                class="flex items-center p-3 text-sm font-semibold rounded-lg transition-colors duration-200
                            {{ request()->routeIs('pemasukan.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h2l1-2h6l1 2h2M3 14h18M3 18h18"></path>
                                </svg>
                                Monitor Pemasukan
                            </a>
                        </li>
                    @endif
                    {{-- Monitor Pengeluaran Proyek --}}
                    <li>
                        <a href="{{ route('pengeluaran.index') }}"
                            class="flex items-center p-3 text-sm font-semibold rounded-lg transition-colors duration-200
                            {{ request()->routeIs('pengeluaran.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z">
                                </path>
                            </svg>
                            Monitor Pengeluaran
                        </a>
                    </li>

                    @if ($role === 'super admin')
                        {{-- Management Users --}}
                        <li>
                            <a href="{{ route('users.index') }}"
                                class="flex items-center p-3 text-sm font-semibold rounded-lg transition-colors duration-200
                            {{ request()->routeIs('users.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>
                                </svg>
                                Management Users
                            </a>
                        </li>
                    @endif


                    @if ($role === 'super admin' || $role === 'bod')
                        {{-- Menu Laporan --}}
                        <li x-data="{ open: {{ request()->routeIs('laporan.*') ? 'true' : 'false' }} }" class="relative">
                            <button @click="open = !open"
                                class="flex items-center justify-between w-full p-3 text-sm font-semibold rounded-lg transition-colors duration-200
            {{ request()->routeIs('laporan.*')
                ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2H8l-4 4v10a2 2 0 002 2z" />
                                    </svg>
                                    Laporan Proyek
                                </div>
                                <svg class="w-4 h-4 ml-2 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            {{-- Dropdown (nested list) --}}
                            <ul x-show="open" x-transition class="ml-8 mt-1 space-y-1">
                                <li>
                                    <a href="{{ route('laporan.index') }}"
                                        class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                    {{ request()->routeIs('laporan.index')
                        ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200 font-semibold'
                        : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        Rekap Laporan
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('laporan.keuangan') }}"
                                        class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                    {{ request()->routeIs('laporan.keuangan')
                        ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200 font-semibold'
                        : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        Laporan Keuangan Proyek
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif



                </ul>
            </nav>

            {{-- Bagian Akun & Logout --}}
            <div class="p-4 mt-auto border-t dark:border-gray-700">
                <a href="#" class="flex items-center space-x-3">
                    <img class="h-10 w-10 rounded-full object-cover"
                        src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->nama_lengkap ?? 'User') . '&background=2563eb&color=ffffff&bold=true' }}"
                        alt="{{ Auth::user()->nama_lengkap }}">
                    <div class="flex flex-col">
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ Auth::user()->nama_lengkap ?? 'Pengguna' }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{-- Pengaturan Akun --}}
                        </div>
                    </div>
                </a>





                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center p-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Konten Utama --}}
        <main class="flex-1 flex flex-col overflow-x-hidden overflow-y-auto">
            <header class="bg-white dark:bg-gray-800 shadow-sm flex-shrink-0">
                <div
                    class="max-w-full mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center justify-between lg:justify-start">

                    {{-- Tombol Hamburger untuk mobile --}}
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-500 dark:text-gray-400 focus:outline-none focus:ring md:hidden">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>

                    <h1 class="text-2xl font-bold ml-4 text-gray-800 dark:text-white">@yield('title', 'Dashboard')</h1>

                    {{-- Tempat untuk menu tambahan di header (jika diperlukan) --}}
                </div>
            </header>

            <div class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                @yield('content')
            </div>

        </main>
    </div>

    @stack('scripts')


</body>

</html>
