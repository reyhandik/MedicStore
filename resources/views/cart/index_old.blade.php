@extends('layouts.app')

@section('title', 'Keranjang Belanja - MedicStore')

@section('content')
<div class="max-w-6xl">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Keranjang Belanja</h1>

    @if(count($cart) > 0)
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="md:col-span-2 space-y-4">
                @foreach($cart as $medicineId => $item)
                    <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between hover:shadow-lg transition">
                        <div class="flex-1">
                            <h3 class="font-bold text-lg text-gray-900">{{ $item['name'] }}</h3>
                            <p class="text-gray-600">Rp {{ number_format($item['price'], 0, ',', '.') }} × {{ $item['qty'] }}</p>
                        </div>

                        <div class="flex items-center space-x-4">
                            <!-- Quantity Input -->
                            <form action="{{ route('cart.update', $medicineId) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" max="999"
                                    class="w-16 px-2 py-1 border border-gray-300 rounded text-center">
                                <button type="submit" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">Perbarui</button>
                            </form>

                            <!-- Subtotal -->
                            <div class="text-right">
                                <p class="text-xl font-bold text-green-600">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                            </div>

                            <!-- Remove -->
                            <form action="{{ route('cart.remove', $medicineId) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-700 font-semibold text-sm ml-4">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach

                <!-- Clear Cart -->
                <div class="text-center">
                    <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-red-600 font-semibold text-sm">
                            Kosongkan Keranjang
                        </button>
                    </form>
                </div>
            </div>

            <!-- Summary -->
            <div class="bg-white rounded-lg shadow-md p-6 h-fit sticky top-8">
                <h2 class="font-bold text-lg text-gray-900 mb-6">Ringkasan Pesanan</h2>

                <!-- Recipe Warning -->
                @if($hasRecipeRequired)
                    <div class="bg-yellow-50 border border-yellow-200 rounded px-3 py-2 mb-4 text-sm">
                        <p class="text-yellow-800"><strong>📄 Catatan:</strong> Keranjang Anda berisi obat yang memerlukan resep.</p>
                        <p class="text-yellow-700 text-xs mt-1">Anda perlu mengunggah resep saat checkout.</p>
                    </div>
                @endif

                <!-- Totals -->
                <div class="space-y-3 border-t border-b border-gray-200 py-4 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pengiriman</span>
                        <span class="font-semibold text-green-600">Gratis</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pajak</span>
                        <span class="font-semibold">Rp {{ number_format($total * 0.1, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Total -->
                <div class="flex justify-between mb-6">
                    <span class="text-lg font-bold text-gray-900">Total</span>
                    <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($total * 1.1, 0, ',', '.') }}</span>
                </div>

                <!-- Checkout Button -->
                <a href="{{ route('checkout.show') }}" class="block text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition mb-3">
                    Lanjut ke Checkout
                </a>

                <!-- Continue Shopping -->
                <a href="{{ route('catalog.index') }}" class="block text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 rounded-lg transition">
                    Lanjutkan Belanja
                </a>
            </div>
        </div>
    @else
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-12 text-center max-w-2xl mx-auto">
            <svg class="w-24 h-24 text-blue-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <h2 class="text-2xl font-bold text-blue-900 mb-2">Keranjang Anda kosong</h2>
            <p class="text-blue-700 mb-6">Mulai berbelanja untuk menambahkan item ke keranjang Anda.</p>
            <a href="{{ route('catalog.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition">
                Jelajahi Obat
            </a>
        </div>
    @endif
</div>
@endsection
