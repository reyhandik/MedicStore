@extends('layouts.app')

@section('title', 'Order #' . $order->id . ' - MedicStore')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-lg shadow-md p-8 mb-8">
        <h1 class="text-3xl font-bold mb-2">Order #{{ $order->id }}</h1>
        <p class="text-blue-100">Placed on {{ $order->created_at->format('F d, Y H:i') }}</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8 mb-8">
        <!-- Status -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-sm font-semibold text-gray-600 mb-2">Status Pesanan</h2>
            <p class="text-2xl font-bold mb-4">
                @switch($order->status)
                    @case('pending')
                        <span class="text-yellow-600 bg-yellow-50 px-3 py-1 rounded">Tertunda</span>
                    @break
                    @case('verified')
                        <span class="text-green-600 bg-green-50 px-3 py-1 rounded">Terverifikasi</span>
                    @break
                    @case('shipped')
                        <span class="text-blue-600 bg-blue-50 px-3 py-1 rounded">Dikirim</span>
                    @break
                    @case('completed')
                        <span class="text-emerald-600 bg-emerald-50 px-3 py-1 rounded">Selesai</span>
                    @break
                @endswitch
            </p>
            <p class="text-xs text-gray-600">
                @if($order->status === 'pending')
                    Menunggu verifikasi apoteker...
                @elseif($order->status === 'verified')
                    Pesanan telah diverifikasi dan sedang disiapkan.
                @elseif($order->status === 'shipped')
                    Pesanan Anda sedang dalam perjalanan!
                @else
                    Pesanan berhasil dikirimkan.
                @endif
            </p>
        </div>

        <!-- Customer -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-sm font-semibold text-gray-600 mb-2">Pelanggan</h2>
            <p class="text-lg font-bold text-gray-900">{{ $order->user->name }}</p>
            <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
        </div>

        <!-- Total -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-sm font-semibold text-gray-600 mb-2">Total Pesanan</h2>
            <p class="text-2xl font-bold text-blue-600 mb-2">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-600">Termasuk pajak dan pengiriman</p>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-lg shadow-md p-8 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Item Pesanan</h2>
        <div class="space-y-4">
            @foreach($order->orderDetails as $detail)
                <div class="flex items-center justify-between p-4 border-l-4 border-blue-500 bg-blue-50 rounded">
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900">{{ $detail->medicine->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $detail->medicine->category->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">Qty: {{ $detail->qty }}</p>
                        <p class="text-sm text-gray-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Prescription (if applicable) -->
    @if($order->recipe_file)
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Resep</h2>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-3">Resep yang tersimpan:</p>
                <a href="{{ asset('storage/' . $order->recipe_file) }}" target="_blank" download
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    📄 Unduh Resep
                </a>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="space-y-3 mb-8">
        <a href="{{ route('orders.patient') }}" class="block text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 rounded-lg transition">
            Kembali ke Pesanan
        </a>
        <a href="{{ route('catalog.index') }}" class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
            Lanjutkan Belanja
        </a>
    </div>
</div>
@endsection
