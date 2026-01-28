<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MedicStore - Online Pharmacy')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H6a1 1 0 100 2H4v10a2 2 0 002 2h8a2 2 0 002-2V9a1 1 0 100-2h-2a1 1 0 000-2h2a2 2 0 012 2v10a4 4 0 01-4 4H6a4 4 0 01-4-4V5z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-xl font-bold">MedicStore</span>
                </div>

                <!-- Menu -->
                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="hover:text-blue-100 transition">Beranda</a>
                    @auth
                        @if(!auth()->user()->isAdmin() && !auth()->user()->isPharmacist())
                            <a href="{{ route('cart.index') }}" class="hover:text-blue-100 transition flex items-center space-x-1">
                                <span>Keranjang</span>
                                @if(count(session('cart', [])) > 0)
                                    <span class="bg-red-500 rounded-full px-2 py-1 text-xs">
                                        {{ count(session('cart', [])) }}
                                    </span>
                                @endif
                            </a>
                        @endif
                    @else
                        <a href="{{ route('cart.index') }}" class="hover:text-blue-100 transition flex items-center space-x-1">
                            <span>Keranjang</span>
                            @if(count(session('cart', [])) > 0)
                                <span class="bg-red-500 rounded-full px-2 py-1 text-xs">
                                    {{ count(session('cart', [])) }}
                                </span>
                            @endif
                        </a>
                    @endauth
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('dashboard.admin') }}" class="hover:text-blue-100 transition">Admin</a>
                        @elseif(auth()->user()->isPharmacist())
                            <a href="{{ route('dashboard.pharmacist') }}" class="hover:text-blue-100 transition">Pharmacist</a>
                        @else
                            <a href="{{ route('dashboard.patient') }}" class="hover:text-blue-100 transition">Dashboard</a>
                        @endif
                    @endauth
                </div>

                <!-- Auth -->
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="relative group">
                            <button class="hover:text-blue-100 transition">{{ auth()->user()->name }}</button>
                            <div class="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-lg shadow-xl hidden group-hover:block z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-blue-50 border-b">Profil</a>
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-red-50 text-red-600 font-semibold">Keluar</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-blue-100 transition">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-400 px-4 py-2 rounded-lg transition">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if($message = Session::get('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mx-4 mt-4 rounded">
            <p class="font-semibold">Berhasil!</p>
            <p>{{ $message }}</p>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mx-4 mt-4 rounded">
            <p class="font-semibold">Kesalahan!</p>
            <p>{{ $message }}</p>
        </div>
    @endif

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-bold text-lg mb-4">MedicStore</h3>
                    <p class="text-gray-400">Your trusted online pharmacy for quality medications.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white">Medicines</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white">Cart</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <p class="text-gray-400 text-sm">Email: support@medicstore.com</p>
                    <p class="text-gray-400 text-sm">Phone: 1-800-MEDS-NOW</p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; 2026 MedicStore. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
