@extends('layouts.app')

@section('title', 'Pharmacist Dashboard - MedicStore')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 to-slate-100">
    <!-- Header -->
    <div class="bg-white border-b border-slate-200 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Pharmacist Dashboard</h1>
                    <p class="text-slate-600 mt-1">Verifikasi pesanan dan manajemen inventaris</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-slate-600">{{ now()->format('l, d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Pending Verification -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Menunggu Verifikasi</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $pendingCount }}</p>
                        <p class="text-xs text-slate-500 mt-2">Pesanan baru</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Verified Today -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Diverifikasi Hari Ini</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $verifiedToday }}</p>
                        <p class="text-xs text-slate-500 mt-2">Sudah diproses</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Low Stock Items -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Stok Rendah</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $lowStockCount }}</p>
                        <p class="text-xs text-slate-500 mt-2">Perlu restock</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2m0-12v-2m8.5 0a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Critical Stock -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Stok Kritis</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $criticalLowStock->count() }}</p>
                        <p class="text-xs text-slate-500 mt-2">Stok < 5 unit</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v2m0 4v2m0-12v-2" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status Overview -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">
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
                        <span class="text-slate-900 font-bold">{{ $shippedOrders }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg border-l-2 border-purple-400">
                        <span class="text-slate-600 font-medium">✓ Selesai</span>
                        <span class="text-slate-900 font-bold">{{ $completedOrders }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-6">Kategori Stok</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border-l-2 border-red-400">
                        <span class="text-slate-600 font-medium">🔴 Kritis (< 5)</span>
                        <span class="text-slate-900 font-bold">{{ $criticalLowStock->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border-l-2 border-orange-400">
                        <span class="text-slate-600 font-medium">🟠 Rendah (5-20)</span>
                        <span class="text-slate-900 font-bold">{{ $needsRestock->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border-l-2 border-green-400">
                        <span class="text-slate-600 font-medium">🟢 Normal (>20)</span>
                        <span class="text-slate-900 font-bold">{{ $totalMedicines - $criticalLowStock->count() - $needsRestock->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="text-lg font-semibold text-slate-900">Pesanan Menunggu Verifikasi</h3>
            </div>
            <div class="divide-y divide-slate-200">
                @if($pendingOrders->count() > 0)
                    @foreach($pendingOrders->take(5) as $order)
                        <a href="{{ route('orders.show', $order) }}" class="block px-6 py-4 hover:bg-slate-50 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">#{{ $order->id }}</p>
                                    <p class="text-sm text-slate-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                    <span class="inline-block mt-1 px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Verifikasi</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="px-6 py-8 text-center">
                        <p class="text-slate-600">Tidak ada pesanan yang menunggu verifikasi</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Low Stock Medicines -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="text-lg font-semibold text-slate-900">Obat Stok Rendah</h3>
            </div>
            <div class="divide-y divide-slate-200">
                @if($lowStockMedicines->count() > 0)
                    @foreach($lowStockMedicines->take(5) as $medicine)
                        <div class="px-6 py-4 hover:bg-slate-50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="font-semibold text-slate-900">{{ $medicine->name }}</p>
                                    <p class="text-sm text-slate-500">{{ $medicine->dosage }}</p>
                                </div>
                                <div class="text-right">
                                    @if($medicine->stock < 5)
                                        <span class="inline-block px-3 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                            🔴 {{ $medicine->stock }} unit
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">
                                            🟠 {{ $medicine->stock }} unit
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="px-6 py-8 text-center">
                        <p class="text-slate-600">Semua stok obat normal</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="mt-8 flex gap-4">
            <a href="{{ route('orders.pending') }}" class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-3 px-6 rounded-lg text-center transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                Verifikasi Pesanan
            </a>
            <a href="{{ route('orders.low-stock') }}" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg text-center transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2m0-12v-2" /></svg>
                Stok Rendah
            </a>
            <a href="{{ route('profile.edit') }}" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 px-6 rounded-lg text-center transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                Pengaturan
            </a>
            <form action="{{ route('logout') }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-slate-600 hover:bg-slate-700 text-white font-semibold py-3 px-6 rounded-lg transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
