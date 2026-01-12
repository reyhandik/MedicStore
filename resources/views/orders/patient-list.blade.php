@extends('layouts.app')

@section('title', 'Pesanan Saya - MedicStore')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Pesanan Saya</h1>
        <p class="text-gray-600">Lihat dan lacak semua pesanan Anda</p>
    </div>

    @if($orders->count() > 0)
        <!-- Orders List -->
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
                    <div class="p-6">
                        <div class="grid md:grid-cols-5 gap-4 items-center">
                            <!-- Order ID -->
                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase">ID Pesanan</p>
                                <p class="text-lg font-bold text-blue-600">#{{ $order->id }}</p>
                            </div>

                            <!-- Date -->
                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase">Tanggal</p>
                                <p class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>

                            <!-- Total -->
                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase">Total</p>
                                <p class="text-lg font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>

                            <!-- Status -->
                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase">Status</p>
                                <p>
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="inline-block text-yellow-700 bg-yellow-100 px-3 py-1 rounded-full text-xs font-semibold">Tertunda</span>
                                        @break
                                        @case('verified')
                                            <span class="inline-block text-green-700 bg-green-100 px-3 py-1 rounded-full text-xs font-semibold">Terverifikasi</span>
                                        @break
                                        @case('shipped')
                                            <span class="inline-block text-blue-700 bg-blue-100 px-3 py-1 rounded-full text-xs font-semibold">Dikirim</span>
                                        @break
                                        @case('completed')
                                            <span class="inline-block text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full text-xs font-semibold">Selesai</span>
                                        @break
                                    @endswitch
                                </p>
                            </div>

                            <!-- Action -->
                            <div class="text-right">
                                <a href="{{ route('orders.show', $order) }}"
                                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>

                        <!-- Items Preview -->
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-xs font-semibold text-gray-600 uppercase mb-2">Produk ({{ $order->orderDetails->count() }})</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($order->orderDetails->take(3) as $detail)
                                    <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                        {{ $detail->medicine->name }} x{{ $detail->qty }}
                                    </span>
                                @endforeach
                                @if($order->orderDetails->count() > 3)
                                    <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                        +{{ $order->orderDetails->count() - 3 }} lainnya
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="text-6xl mb-4">📦</div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Pesanan</h3>
            <p class="text-gray-600 mb-6">Anda belum melakukan pesanan. Mulai berbelanja sekarang!</p>
            <a href="{{ route('catalog.index') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                Jelajahi Obat
            </a>
        </div>
    @endif
</div>
@endsection
