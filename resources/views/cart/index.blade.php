@extends('layouts.app')

@section('title', 'Keranjang Belanja - MedicStore')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 border-l-4 border-emerald-500 pl-4">Keranjang Belanja</h1>

        @if(count($cart) > 0)
            <div class="grid md:grid-cols-12 gap-8">
                <!-- Cart Items -->
                <div class="md:col-span-8 space-y-6">
                    @foreach($cart as $medicineId => $item)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col sm:flex-row items-center justify-between gap-6 transition hover:shadow-md">
                            <div class="flex-1 flex items-center gap-4">
                                <div class="w-16 h-16 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-500">
                                     <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg text-gray-900">{{ $item['name'] }}</h3>
                                    <p class="text-gray-500 text-sm">Satuan: Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-center gap-6 w-full sm:w-auto">
                                <!-- Quantity Input -->
                                <form action="{{ route('cart.update', $medicineId) }}" method="POST" class="flex items-center bg-gray-50 rounded-lg border border-gray-200 p-1">
                                    @csrf
                                    <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown(); this.parentNode.submit();" class="p-2 text-gray-500 hover:text-emerald-600 focus:outline-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    </button>
                                    <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" max="999" onchange="this.form.submit()"
                                        class="w-12 bg-transparent text-center text-gray-700 font-semibold focus:outline-none border-none p-0">
                                    <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp(); this.parentNode.submit();" class="p-2 text-gray-500 hover:text-emerald-600 focus:outline-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </form>

                                <!-- Subtotal -->
                                <div class="text-right min-w-[100px]">
                                    <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                </div>

                                <!-- Remove -->
                                <form action="{{ route('cart.remove', $medicineId) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 text-red-100 hover:text-red-500 hover:bg-red-50 rounded-full transition" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    <!-- Clear Cart -->
                    <div class="text-right">
                        <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-700 hover:underline">
                                Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Summary -->
                <div class="md:col-span-4">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-24">
                        <h2 class="font-bold text-xl text-gray-900 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Ringkasan Pesanan
                        </h2>

                        <!-- Recipe Warning -->
                        @if($hasRecipeRequired)
                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                                <div class="flex items-start gap-3">
                                    <span class="text-amber-500 mt-0.5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </span>
                                    <div>
                                        <p class="text-amber-800 text-sm font-bold">Resep Diperlukan</p>
                                        <p class="text-amber-700 text-xs mt-1 leading-relaxed">Beberapa item di keranjang Anda memerlukan resep dokter. Siapkan foto resep Anda saat checkout.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Totals -->
                        <div class="space-y-4 border-t border-gray-100 py-6 mb-2">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Subtotal</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Pengiriman</span>
                                <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded text-sm">Gratis</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Pajak (10%)</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($total * 0.1, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="border-t border-dashed border-gray-200 my-4"></div>

                        <!-- Total -->
                        <div class="flex justify-between items-end mb-8">
                            <span class="text-lg font-bold text-gray-900">Total Tagihan</span>
                            <span class="text-3xl font-extrabold text-emerald-600">Rp {{ number_format($total * 1.1, 0, ',', '.') }}</span>
                        </div>

                        <!-- Checkout Button -->
                        <a href="{{ route('checkout.show') }}" class="block w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-200 transition transform hover:-translate-y-0.5 mb-4">
                            Lanjut ke Checkout
                        </a>

                        <!-- Continue Shopping -->
                        <a href="{{ route('catalog.index') }}" class="block w-full text-center text-gray-500 hover:text-emerald-600 font-medium py-2 transition text-sm">
                            Kembali Belanja
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-16 text-center max-w-2xl mx-auto">
                <div class="bg-emerald-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Keranjang Anda kosong</h2>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Sepertinya Anda belum menambahkan obat atau produk kesehatan apapun.</p>
                <a href="{{ route('catalog.index') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-10 rounded-full shadow-lg shadow-emerald-200 transition transform hover:-translate-y-1">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
