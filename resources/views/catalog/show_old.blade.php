@extends('layouts.app')

@section('title', $medicine->name . ' - MedicStore')

@section('content')
<div class="grid md:grid-cols-2 gap-8 max-w-4xl">
    <!-- Left: Image -->
    <div class="bg-white rounded-lg shadow-md p-8">
        @if($medicine->image)
            <img src="{{ asset('storage/' . $medicine->image) }}" alt="{{ $medicine->name }}" class="w-full rounded-lg">
        @else
            <div class="bg-gradient-to-br from-blue-100 to-green-100 w-full h-96 flex items-center justify-center rounded-lg">
                <svg class="w-24 h-24 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M7.707 2.293a1 1 0 00-1.414 1.414L4.414 6H2a1 1 0 00-1 1v10a1 1 0 001 1h16a1 1 0 001-1V7a1 1 0 00-1-1h-2.414l-1.879-1.879A1 1 0 0011 4h-2a1 1 0 00-1.293.293l-.414.414-1.586-1.586zM10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                </svg>
            </div>
        @endif
    </div>

    <!-- Right: Details -->
    <div class="bg-white rounded-lg shadow-md p-8 space-y-6">
        <!-- Header -->
        <div>
            <a href="{{ route('catalog.index') }}" class="text-blue-600 hover:text-blue-700 mb-2 inline-block">← Kembali ke Katalog</a>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $medicine->name }}</h1>
            <p class="text-gray-600">{{ $medicine->category->name }}</p>
        </div>

        <!-- Price & Stock -->
        <div class="border-t border-b border-gray-200 py-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Harga</p>
                    <p class="text-3xl font-bold text-blue-600">Rp {{ number_format($medicine->price, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Tersedia</p>
                    <p class="text-3xl font-bold {{ $medicine->stock > 10 ? 'text-green-600' : ($medicine->stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ $medicine->stock }} {{ $medicine->stock === 1 ? 'unit' : 'unit' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Prescription Badge -->
        @if($medicine->needs_recipe)
            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-yellow-900">Resep Diperlukan</h3>
                        <p class="text-sm text-yellow-800">Anda perlu mengunggah resep yang sah saat checkout.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Description -->
        <div>
            <h2 class="font-bold text-lg text-gray-900 mb-2">Deskripsi</h2>
            <p class="text-gray-700 leading-relaxed">{{ $medicine->description ?? 'Tidak ada deskripsi yang tersedia.' }}</p>
        </div>

        <!-- Add to Cart -->
        @if($medicine->stock > 0)
            <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="medicine_id" value="{{ $medicine->id }}">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah</label>
                    <input type="number" name="qty" value="1" min="1" max="{{ $medicine->stock }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition">
                    Tambah ke Keranjang
                </button>
            </form>
        @else
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                <p class="text-red-700 font-semibold">Habis Terjual</p>
                <p class="text-red-600 text-sm">Obat ini saat ini tidak tersedia.</p>
            </div>
        @endif

        <!-- Continue Shopping -->
        <a href="{{ route('catalog.index') }}" class="block text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 rounded-lg transition">
            Lanjutkan Belanja
        </a>
    </div>
</div>
@endsection
