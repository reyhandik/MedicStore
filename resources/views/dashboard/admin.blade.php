@extends('layouts.app')

@section('title', 'Admin Dashboard - MedicStore')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 to-slate-100">
    <!-- Header -->
    <div class="bg-white border-b border-slate-200 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Admin Dashboard</h1>
                    <p class="text-slate-600 mt-1">Kelola sistem MedicStore</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-slate-600">{{ now()->format('l, d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Revenue & Orders Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Revenue -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Pendapatan</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">Rp {{ number_format($totalRevenue / 1000000, 1, ',', '.') }}M</p>
                        <p class="text-xs text-slate-500 mt-2">{{ $totalOrders }} transaksi</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Pesanan Tertunda</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $pendingCount }}</p>
                        <p class="text-xs text-slate-500 mt-2">Menunggu verifikasi</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total User</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $totalUsers }}</p>
                        <p class="text-xs text-slate-500 mt-2">{{ $patientCount }} pasien</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10h1.5M9 20H4v-2a6 6 0 0112 0v2m0 0h5v-2a6 6 0 00-9-5.656V9a2 2 0 11-4 0V7.5a4 4 0 118 0v2.5m0 0V9" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Medicines -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Obat</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $totalMedicines }}</p>
                        <p class="text-xs text-slate-500 mt-2">{{ $categoriesCount }} kategori</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.414-.586h-.028a2 2 0 00-1.414.586m5.656 1.414a9 9 0 11-12.728 0m5.656-5.656a9 9 0 010 12.728m0-12.728L9.172 9.172" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Distribution -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-6">Distribusi User</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <span class="text-slate-600 font-medium">👨‍⚕️ Admin</span>
                        <span class="text-slate-900 font-bold">{{ $adminCount }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <span class="text-slate-600 font-medium">💊 Apoteker</span>
                        <span class="text-slate-900 font-bold">{{ $pharmacistCount }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <span class="text-slate-600 font-medium">👤 Pasien</span>
                        <span class="text-slate-900 font-bold">{{ $patientCount }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-6">Status Pesanan</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border-l-2 border-yellow-400">
                        <span class="text-slate-600 font-medium">⏳ Tertunda</span>
                        <span class="text-slate-900 font-bold">{{ $pendingCount }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border-l-2 border-green-400">
                        <span class="text-slate-600 font-medium">✓ Terverifikasi</span>
                        <span class="text-slate-900 font-bold">{{ $verifiedOrders }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border-l-2 border-blue-400">
                        <span class="text-slate-600 font-medium">📦 Dikirim</span>
                        <span class="text-slate-900 font-bold">{{ $shippedCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="text-lg font-semibold text-slate-900">Pesanan Terbaru</h3>
            </div>
            <div class="divide-y divide-slate-200">
                @if($recentOrders->count() > 0)
                    @foreach($recentOrders->take(5) as $order)
                        <div class="px-6 py-4 hover:bg-slate-50 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">#{{ $order->id }}</p>
                                    <p class="text-sm text-slate-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="inline-block mt-1 px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Tertunda</span>
                                        @break
                                        @case('verified')
                                            <span class="inline-block mt-1 px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Terverifikasi</span>
                                        @break
                                        @case('shipped')
                                            <span class="inline-block mt-1 px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Dikirim</span>
                                        @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Top Medicines -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="text-lg font-semibold text-slate-900">Obat Terlaris</h3>
            </div>
            <div class="divide-y divide-slate-200">
                @if($topMedicines->count() > 0)
                    @foreach($topMedicines->take(5) as $medicine)
                        <div class="px-6 py-4 hover:bg-slate-50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="font-semibold text-slate-900">{{ $medicine->name }}</p>
                                    <p class="text-sm text-slate-500">{{ $medicine->dosage }} - Stok: {{ $medicine->stock }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-slate-900">Rp {{ number_format($medicine->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="mt-8 flex gap-4">
            <a href="{{ route('profile.edit') }}" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 px-6 rounded-lg text-center transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                Pengaturan
            </a>
            <form action="{{ route('logout') }}" method="POST" class="flex-1">
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
