@extends('layouts.app')

@section('title', 'Dasbor - MedicStore')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 to-slate-100">
    <!-- Header Section -->
    <div class="bg-white border-b border-slate-200 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Selamat Datang</h1>
                    <p class="text-slate-600 mt-1">{{ auth()->user()->name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-slate-600">{{ now()->format('l, d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Stat Card 1 -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Pesanan</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $totalOrders }}</p>
                        <p class="text-xs text-slate-500 mt-2">{{ $pendingOrders }} menunggu verifikasi</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Pesanan Selesai</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $completedOrders }}</p>
                        <p class="text-xs text-slate-500 mt-2">Berhasil dikirim</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Pengeluaran</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">Rp {{ number_format($totalSpent / 1000000, 1, ',', '.') }}M</p>
                        <p class="text-xs text-slate-500 mt-2">Seumur hidup</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Rata-rata Pesanan</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-500 mt-2">Per transaksi</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid lg:grid-cols-3 gap-8 mb-8">
            <!-- Pesanan Terbaru -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-slate-900">Pesanan Terbaru</h2>
                            @if($recentOrders->count() > 0)
                                <a href="{{ route('orders.patient') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua →</a>
                            @endif
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="divide-y divide-slate-200">
                        @if($recentOrders->count() > 0)
                            @foreach($recentOrders as $order)
                                <a href="{{ route('orders.show', $order) }}" class="block px-6 py-4 hover:bg-slate-50 transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-sm font-semibold text-slate-600">#{{ $order->id }}</span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-slate-900">Pesanan #{{ $order->id }}</p>
                                                    <p class="text-xs text-slate-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                            <div class="mt-2">
                                                @switch($order->status)
                                                    @case('pending')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            ⏳ Menunggu
                                                        </span>
                                                    @break
                                                    @case('verified')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            ✓ Terverifikasi
                                                        </span>
                                                    @break
                                                    @case('shipped')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            📦 Dikirim
                                                        </span>
                                                    @break
                                                    @case('completed')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                            ✓ Selesai
                                                        </span>
                                                    @break
                                                @endswitch
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <p class="text-slate-600 font-medium mb-4">Belum ada pesanan</p>
                                <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                                    Mulai Berbelanja
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Profile Card -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4">Profil Saya</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Nama Lengkap</p>
                            <p class="text-slate-900 font-medium mt-1">{{ auth()->user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Email</p>
                            <p class="text-slate-900 font-medium mt-1 break-all text-sm">{{ auth()->user()->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Member Sejak</p>
                            <p class="text-slate-900 font-medium mt-1">{{ auth()->user()->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4">Menu Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('catalog.index') }}" class="w-full flex items-center gap-3 px-4 py-3 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                            Belanja Obat
                        </a>
                        <a href="{{ route('cart.index') }}" class="w-full flex items-center gap-3 px-4 py-3 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            Keranjang
                        </a>
                        <a href="{{ route('profile.edit') }}" class="w-full flex items-center gap-3 px-4 py-3 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Pengaturan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Health Information Section -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-8">
            <h2 class="text-lg font-semibold text-slate-900 mb-6">ℹ️ Tips Kesehatan Penting</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="p-4 border-l-4 border-blue-500 bg-blue-50 rounded">
                    <h3 class="font-semibold text-slate-900 text-sm mb-2">Ikuti Petunjuk Apoteker</h3>
                    <p class="text-sm text-slate-600">Selalu ikuti instruksi dari apoteker mengenai dosis dan cara penggunaan obat untuk hasil yang optimal.</p>
                </div>
                <div class="p-4 border-l-4 border-green-500 bg-green-50 rounded">
                    <h3 class="font-semibold text-slate-900 text-sm mb-2">Penyimpanan yang Tepat</h3>
                    <p class="text-sm text-slate-600">Simpan obat di tempat yang sejuk, kering, dan terlindung dari cahaya matahari langsung.</p>
                </div>
                <div class="p-4 border-l-4 border-orange-500 bg-orange-50 rounded">
                    <h3 class="font-semibold text-slate-900 text-sm mb-2">Periksa Kadaluarsa</h3>
                    <p class="text-sm text-slate-600">Selalu cek tanggal kadaluarsa sebelum menggunakan obat dan jangan gunakan obat yang sudah kadaluarsa.</p>
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="grid md:grid-cols-2 gap-4">
            <a href="{{ route('profile.edit') }}" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 px-6 rounded-lg text-center transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                Edit Profil
            </a>
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection


