@extends('layouts.app')

@section('title', 'Checkout - MedicStore')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

    <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data" class="grid md:grid-cols-3 gap-8">
        @csrf

        <!-- Order Review -->
        <div class="md:col-span-2 space-y-8">
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="font-bold text-lg text-gray-900 mb-4">Ringkasan Pesanan</h2>
                <div class="space-y-3">
                    @php $total = 0; $hasRecipeRequired = false; @endphp
                    @foreach($cart as $item)
                        @php 
                            $total += $item['subtotal'];
                            $medicine = \App\Models\Medicine::find($item['medicine_id']);
                            if($medicine && $medicine->needs_recipe) $hasRecipeRequired = true;
                        @endphp
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $item['name'] }}</p>
                                <p class="text-sm text-gray-600">Qty: {{ $item['qty'] }}</p>
                            </div>
                            <p class="font-semibold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Prescription Upload (if needed) -->
            @if($requiresRecipe)
                <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-6">
                    <h2 class="font-bold text-lg text-gray-900 mb-4 flex items-center space-x-2">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Resep Diperlukan</span>
                    </h2>
                    <p class="text-gray-700 mb-4">Pesanan Anda berisi obat resep. Harap unggah file resep yang sah.</p>
                    
                    <label class="block">
                        <span class="sr-only">Pilih file resep</span>
                        <input type="file" name="recipe_file" accept=".pdf,.jpg,.jpeg,.png" required
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-yellow-600 file:text-white hover:file:bg-yellow-700">
                    </label>
                    
                    <p class="text-xs text-gray-600 mt-2">Format yang diterima: PDF, JPG, PNG (Maks 5MB)</p>
                    
                    @error('recipe_file')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- Delivery Address -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="font-bold text-lg text-gray-900 mb-4">Informasi Pengiriman</h2>
                <p class="text-gray-700 p-4 bg-blue-50 rounded-lg">
                    <strong>Catatan:</strong> Kami akan mengirim pesanan Anda ke alamat email yang terkait dengan akun Anda.
                </p>
            </div>
        </div>

        <!-- Summary Sidebar -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-8 space-y-6">
                <h2 class="font-bold text-lg text-gray-900">Total Pesanan</h2>

                @php
                    $subtotal = $total;
                    $tax = $subtotal * 0.1;
                    $grandTotal = $subtotal + $tax;
                @endphp

                <div class="space-y-3 border-b border-gray-200 pb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pajak (10%)</span>
                        <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pengiriman</span>
                        <span class="text-green-600">Gratis</span>
                    </div>
                </div>

                <div class="flex justify-between mb-6 pt-4">
                    <span class="font-bold text-gray-900">Total</span>
                    <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>

                <!-- Terms -->
                <div class="bg-gray-50 p-3 rounded-lg">
                    <label class="flex items-start space-x-2">
                        <input type="checkbox" required class="mt-1">
                        <span class="text-sm text-gray-700">Saya setuju dengan syarat dan ketentuan</span>
                    </label>
                </div>

                <!-- Place Order Button -->
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition">
                    Buat Pesanan
                </button>

                <!-- Back to Cart -->
                <a href="{{ route('cart.index') }}" class="block text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 rounded-lg transition">
                    Kembali ke Keranjang
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
