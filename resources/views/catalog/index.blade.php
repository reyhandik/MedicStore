@extends('layouts.app')

@section('title', 'Katalog Obat - MedicStore')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Katalog Obat</h1>
        <p class="text-xl text-gray-600">Jelajahi pilihan obat berkualitas kami</p>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('catalog.index') }}" class="grid md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Obat</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama obat..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter berdasarkan Kategori</label>
                <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search Button -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Medicines Grid -->
    <div>
        @if($medicines->count() > 0)
            <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($medicines as $medicine)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
                        <!-- Image -->
                        <div class="bg-gradient-to-br from-blue-100 to-green-100 h-48 flex items-center justify-center">
                            @if($medicine->image)
                                <img src="{{ asset('storage/' . $medicine->image) }}" alt="{{ $medicine->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-16 h-16 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M7.707 2.293a1 1 0 00-1.414 1.414L4.414 6H2a1 1 0 00-1 1v10a1 1 0 001 1h16a1 1 0 001-1V7a1 1 0 00-1-1h-2.414l-1.879-1.879A1 1 0 0011 4h-2a1 1 0 00-1.293.293l-.414.414-1.586-1.586zM10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                </svg>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $medicine->name }}</h3>
                            <p class="text-sm text-gray-600 mb-2">{{ $medicine->category->name }}</p>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $medicine->description ?? 'No description' }}</p>

                            <!-- Price & Stock -->
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($medicine->price, 0, ',', '.') }}</span>
                                <span class="text-sm {{ $medicine->stock > 10 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                    {{ $medicine->stock }} tersedia
                                </span>
                            </div>

                            <!-- Recipe Badge -->
                            @if($medicine->needs_recipe)
                                <div class="bg-yellow-50 border border-yellow-200 rounded px-2 py-1 mb-4 text-center">
                                    <span class="text-xs font-semibold text-yellow-800">📄 Resep Diperlukan</span>
                                </div>
                            @endif

                            <!-- Buttons -->
                            <div class="space-y-2">
                                <a href="{{ route('catalog.show', $medicine) }}" class="block text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 rounded-lg transition">
                                    Lihat Detail
                                </a>
                                @if($medicine->stock > 0)
                                    <form action="{{ route('cart.add') }}" method="POST" class="flex gap-2">
                                        @csrf
                                        <input type="hidden" name="medicine_id" value="{{ $medicine->id }}">
                                        <input type="number" name="qty" value="1" min="1" max="{{ $medicine->stock }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-center">
                                        <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition">
                                            Tambah ke Keranjang
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full bg-gray-300 text-gray-600 font-semibold py-2 rounded-lg cursor-not-allowed">
                                        Habis Terjual
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $medicines->links() }}
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
                <svg class="w-16 h-16 text-blue-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-blue-900 mb-2">Obat tidak ditemukan</h3>
                <p class="text-blue-700">Coba sesuaikan pencarian atau filter Anda.</p>
            </div>
        @endif
    </div>
</div>
@endsection
