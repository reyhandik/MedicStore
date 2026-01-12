@extends('layouts.app')

@section('title', 'Peringatan Stok Rendah - MedicStore')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Obat Stok Rendah</h1>
        <p class="text-gray-600">Obat dengan inventaris di bawah 10 unit memerlukan restok segera</p>
    </div>

    @if($lowStockMedicines->count() > 0)
        <!-- Alert Banner -->
        <div class="bg-red-50 border-l-4 border-red-600 text-red-700 p-4 rounded mb-8">
            <p class="font-bold">⚠️ {{ $lowStockMedicines->count() }} item stok rendah</p>
            <p class="text-sm">Silakan atur restok untuk menghindari kehabisan barang.</p>
        </div>

        <!-- Medicines Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($lowStockMedicines as $medicine)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition border-t-4 border-red-500">
                    <!-- Image -->
                    <div class="bg-gray-100 h-40 flex items-center justify-center overflow-hidden">
                        @if($medicine->image)
                            <img src="{{ asset('storage/' . $medicine->image) }}" alt="{{ $medicine->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-4xl">💊</span>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <!-- Stock Alert -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-red-600 uppercase">Tingkat Stok</span>
                                <span class="text-2xl font-bold text-red-600">{{ $medicine->stock }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ min($medicine->stock * 10, 100) }}%"></div>
                            </div>
                        </div>

                        <!-- Medicine Info -->
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $medicine->name }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ $medicine->category->name }}</p>

                        <!-- Details -->
                        <div class="space-y-2 mb-4 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Harga:</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($medicine->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Memerlukan Resep:</span>
                                <span class="font-semibold text-gray-900">{{ $medicine->needs_recipe ? 'Ya' : 'Tidak' }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="space-y-2">
                            <a href="{{ route('medicines.edit', $medicine) }}"
                                class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                                Edit Stok
                            </a>
                            <a href="{{ route('catalog.show', $medicine) }}"
                                class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 rounded-lg transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="text-6xl mb-4">📦</div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Semua Tingkat Stok Baik</h3>
            <p class="text-gray-600 mb-6">Semua obat memiliki inventaris yang cukup.</p>
            <a href="{{ route('catalog.index') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                Lihat Semua Obat
            </a>
        </div>
    @endif
</div>
@endsection
