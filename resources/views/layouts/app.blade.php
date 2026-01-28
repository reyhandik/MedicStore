<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MedicStore - Apotek Online Terpercaya')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav x-data="{ open: false, scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="{ 'bg-white shadow-md': scrolled, 'bg-white/90 backdrop-blur-md border-b border-gray-100': !scrolled }"
         class="fixed w-full z-50 transition-all duration-300 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                        <div class="bg-emerald-500 rounded-lg p-2 group-hover:bg-emerald-600 transition duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold text-gray-900 tracking-tight">MedicStore</span>
                            <span class="text-xs text-emerald-600 font-medium tracking-wider uppercase">Solusi Sehat Anda</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-emerald-600 font-medium transition py-2 border-b-2 border-transparent hover:border-emerald-500">Beranda</a>
                    
                    @auth
                        @if(!auth()->user()->isAdmin() && !auth()->user()->isPharmacist())
                            <a href="{{ route('cart.index') }}" class="relative group p-2">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-emerald-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                @if(session('cart') && count(session('cart')) > 0)
                                    <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center border-2 border-white shadow-sm transform scale-100 group-hover:scale-110 transition">
                                        {{ count(session('cart')) }}
                                    </span>
                                @endif
                            </a>
                        @endif
                    @else
                        <a href="{{ route('cart.index') }}" class="relative group p-2">
                            <svg class="w-6 h-6 text-gray-600 group-hover:text-emerald-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center border-2 border-white shadow-sm transform scale-100 group-hover:scale-110 transition">
                                    {{ count(session('cart')) }}
                                </span>
                            @endif
                        </a>
                    @endauth

                    @auth
                        <div class="relative" x-data="{ dropdownOpen: false }">
                            <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="flex items-center space-x-2 text-gray-700 hover:text-emerald-600 font-medium focus:outline-none transition">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold border border-emerald-200">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span>{{ auth()->user()->name }}</span>
                                <svg :class="{'rotate-180': dropdownOpen}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="dropdownOpen" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 py-1 z-50 overflow-hidden" style="display: none;">
                                
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm text-gray-500">Masuk sebagai</p>
                                    <p class="text-sm font-semibold truncate">{{ auth()->user()->email }}</p>
                                </div>

                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('dashboard.admin') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700">Admin Dashboard</a>
                                @elseif(auth()->user()->isPharmacist())
                                    <a href="{{ route('dashboard.pharmacist') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700">Pharmacist Area</a>
                                @else
                                    <a href="{{ route('dashboard.patient') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700">Dashboard Saya</a>
                                @endif
                                
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700">Pengaturan Profil</a>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-emerald-600 font-medium px-4 py-2 transition">Masuk</a>
                            <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-full font-medium shadow-md shadow-emerald-200 transition transform hover:-translate-y-0.5">Daftar Sekarang</a>
                        </div>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button @click="open = !open" class="text-gray-600 hover:text-emerald-600 focus:outline-none">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" class="md:hidden bg-white border-t border-gray-100" style="display: none;">
            <div class="px-4 pt-2 pb-4 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50">Beranda</a>
                <a href="{{ route('cart.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 flex justify-between">
                    <span>Keranjang</span>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="bg-red-500 text-white text-xs font-bold rounded-full px-2 py-1">{{ count(session('cart')) }}</span>
                    @endif
                </a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('dashboard.admin') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50">Admin Dashboard</a>
                    @elseif(auth()->user()->isPharmacist())
                        <a href="{{ route('dashboard.pharmacist') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50">Pharmacist Dashboard</a>
                    @else
                        <a href="{{ route('dashboard.patient') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50">Dashboard</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">Keluar</button>
                    </form>
                @else
                    <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-3">
                        <a href="{{ route('login') }}" class="text-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">Masuk</a>
                        <a href="{{ route('register') }}" class="text-center px-4 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Spacer for fixed navbar -->
    <div class="h-20"></div>

    <!-- Flash Messages -->
    <div class="fixed top-24 right-4 z-50 flex flex-col gap-2 pointer-events-none">
        @if($message = Session::get('success'))
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 x-init="setTimeout(() => show = false, 5000)" 
                 class="pointer-events-auto w-80 overflow-hidden bg-white rounded-lg shadow-xl border-l-4 border-emerald-500 hover:shadow-2xl transition-shadow duration-300">
                <div class="p-4 flex items-center">
                    <div class="flex-shrink-0 text-emerald-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-emerald-900">Berhasil!</p>
                        <p class="text-sm text-gray-500">{{ $message }}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Tutup</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if($message = Session::get('error'))
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 x-init="setTimeout(() => show = false, 5000)" 
                 class="pointer-events-auto w-80 overflow-hidden bg-white rounded-lg shadow-xl border-l-4 border-red-500 hover:shadow-2xl transition-shadow duration-300">
                <div class="p-4 flex items-center">
                    <div class="flex-shrink-0 text-red-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-red-900">Kesalahan!</p>
                        <p class="text-sm text-gray-500">{{ $message }}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Tutup</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- AJAX Notifications --}}
        <div x-data="{ notifications: [] }" 
             @notify.window="notifications.push({
                id: Date.now(),
                type: $event.detail.type || 'success',
                message: $event.detail.message
             }); 
             setTimeout(() => { notifications.shift() }, 5000)"
             class="flex flex-col gap-2 pointer-events-none"
        >
            <template x-for="notif in notifications" :key="notif.id">
                <div x-data="{ show: true }"
                     x-show="show"
                     x-transition:enter="transform ease-out duration-300 transition"
                     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="pointer-events-auto w-80 overflow-hidden bg-white rounded-lg shadow-xl border-l-4 hover:shadow-2xl transition-shadow duration-300 mb-2"
                     :class="notif.type === 'success' ? 'border-emerald-500' : 'border-red-500'"
                >
                    <div class="p-4 flex items-center">
                        <!-- Icon Success -->
                        <div x-show="notif.type === 'success'" class="flex-shrink-0 text-emerald-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <!-- Icon Error -->
                        <div x-show="notif.type === 'error'" class="flex-shrink-0 text-red-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>

                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium" :class="notif.type === 'success' ? 'text-emerald-900' : 'text-red-900'" x-text="notif.type === 'success' ? 'Berhasil!' : 'Kesalahan!'"></p>
                            <p class="text-sm text-gray-500" x-text="notif.message"></p>
                        </div>
                        
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="show = false" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                                <span class="sr-only">Tutup</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="bg-emerald-500 rounded p-1">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                        <span class="text-xl font-bold">MedicStore</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">
                        Penyedia layanan kesehatan digital terpercaya untuk kebutuhan obat dan konsultasi kesehatan Anda. Cepat, Aman, dan Praktis.
                    </p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-emerald-400">Layanan</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Beli Obat</a></li>
                        <li><a href="#" class="hover:text-white transition">Resep Dokter</a></li>
                        <li><a href="#" class="hover:text-white transition">Konsultasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Cek Lab</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4 text-emerald-400">Perusahaan</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition">Karir</a></li>
                        <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4 text-emerald-400">Hubungi Kami</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 mt-0.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Jl. Kesehatan No. 123,<br>Jakarta Selatan, 12345</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>(021) 555-0123</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>tanya@medicstore.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 mt-8 text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} MedicStore. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Chat Widget -->
    @include('components.chat-widget')

    <script>
        function addToCart(form) {
            let url = form.action;
            let token = form.querySelector('input[name="_token"]').value;
            let formData = new FormData(form);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    window.dispatchEvent(new CustomEvent('notify', { 
                        detail: { type: 'success', message: 'Item berhasil ditambahkan ke keranjang.' } 
                    }));
                } else {
                    response.json().then(data => {
                         window.dispatchEvent(new CustomEvent('notify', { 
                            detail: { type: 'error', message: data.message || 'Gagal menambahkan item.' } 
                        }));
                    }).catch(() => {
                        window.dispatchEvent(new CustomEvent('notify', { 
                            detail: { type: 'error', message: 'Terjadi kesalahan sistem.' } 
                        }));
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.dispatchEvent(new CustomEvent('notify', { 
                    detail: { type: 'error', message: 'Terjadi kesalahan jaringan.' } 
                }));
            });
        }
    </script>
</body>
</html>
