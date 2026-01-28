@extends('layouts.app')

@section('title', 'Checkout - MedicStore')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 border-l-4 border-emerald-500 pl-4">Checkout</h1>

        <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data" class="grid lg:grid-cols-12 gap-8">
            @csrf

            <!-- Left Column: Details -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Order Items -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                            <div class="bg-emerald-100 p-1.5 rounded-lg text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            Detail Pesanan
                        </h2>
                    </div>
                    <div class="px-6 py-2">
                        @php $total = 0; $hasRecipeRequired = false; @endphp
                        @foreach($cart as $item)
                            @php 
                                $total += $item['subtotal'];
                                $medicine = \App\Models\Medicine::find($item['medicine_id']);
                                if($medicine && $medicine->needs_recipe) $hasRecipeRequired = true;
                            @endphp
                            <div class="flex items-center justify-between py-4 border-b border-gray-100 last:border-0">
                                <div class="flex items-center gap-4">
                                    <div class="hidden sm:block w-12 h-12 bg-gray-100 rounded-lg flex-shrink-0"></div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $item['name'] }}</p>
                                        <p class="text-sm text-gray-500">Qty: {{ $item['qty'] }} &times; Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <p class="font-bold text-gray-800">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Prescription Upload -->
                @if($requiresRecipe)
                    <div class="bg-white rounded-2xl shadow-sm border border-amber-200 overflow-hidden">
                         <div class="bg-amber-50 px-6 py-4 border-b border-amber-100">
                            <h2 class="font-bold text-lg text-amber-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Unggah Resep Dokter
                            </h2>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 mb-6">Pesanan Anda mengandung obat keras yang memerlukan resep dokter. Silakan unggah foto resep Anda yang masih berlaku.</p>
                            
                            <div class="w-full">
                                <label class="flex justify-center w-full h-32 px-4 transition bg-white border-2 border-gray-300 border-dashed rounded-xl appearance-none cursor-pointer hover:border-emerald-400 focus:outline-none">
                                    <span class="flex items-center space-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <span class="font-medium text-gray-600">
                                            Klik untuk upload resep (PDF/JPG/PNG)
                                        </span>
                                    </span>
                                    <input type="file" name="recipe_file" class="hidden" accept=".pdf,.jpg,.jpeg,.png" required>
                                </label>
                                @error('recipe_file')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Delivery Address -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                         <h2 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                            <div class="bg-blue-100 p-1.5 rounded-lg text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            Informasi Pengiriman
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-4">
                            <svg class="w-6 h-6 text-blue-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <p class="text-blue-900 font-semibold mb-1">Pengiriman Digital</p>
                                <p class="text-blue-700 text-sm">
                                    Resi dan konfirmasi pesanan akan dikirimkan ke alamat email <strong>{{ auth()->user()->email }}</strong>.
                                    Untuk pengiriman fisik, kami menggunakan alamat yang terdaftar di profil Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-24">
                    <h2 class="font-bold text-lg text-gray-900 mb-6">Ringkasan Pembayaran</h2>

                    @php
                        $subtotal = $total;
                        $tax = $subtotal * 0.1;
                        $grandTotal = $subtotal + $tax;
                    @endphp

                    <div class="space-y-4 border-b border-gray-100 pb-6 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Subtotal Produk</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Pajak PPN (10%)</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Biaya Pengiriman</span>
                            <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded text-sm">Gratis</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-end mb-8">
                        <div>
                            <span class="text-gray-500 text-sm block mb-1">Total Tagihan</span>
                            <span class="text-3xl font-extrabold text-emerald-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="bg-gray-50 p-4 rounded-xl mb-6">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <div class="flex items-center h-5">
                                <input type="checkbox" required class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                            </div>
                            <span class="text-sm text-gray-600">
                                Saya telah membaca dan menyetujui <a href="#" class="text-emerald-600 hover:underline">Syarat & Ketentuan</a> yang berlaku.
                            </span>
                        </label>
                    </div>

                    <!-- Place Order Button -->
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-200 transition transform hover:-translate-y-0.5 mb-4 flex items-center justify-center gap-2">
                        <span>Konfirmasi Pesanan</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>

                    <!-- Back to Cart -->
                    <a href="{{ route('cart.index') }}" class="block w-full text-center text-gray-500 hover:text-emerald-600 font-medium py-2 transition text-sm">
                        Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
