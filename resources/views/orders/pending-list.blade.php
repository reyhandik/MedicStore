@extends('layouts.app')

@section('title', 'Pesanan Tertunda - MedicStore')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Pesanan Tertunda</h1>
        <p class="text-gray-600">Tinjau dan verifikasi pesanan pelanggan</p>
    </div>

    <!-- Stats -->
    <div class="grid md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm font-semibold text-gray-600 uppercase">Pesanan Tertunda</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $pending->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm font-semibold text-gray-600 uppercase">Total Nilai</p>
            <p class="text-3xl font-bold text-green-600">Rp {{ number_format($pending->sum('total_price'), 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm font-semibold text-gray-600 uppercase">Rata-rata Pesanan</p>
            <p class="text-3xl font-bold text-blue-600">Rp {{ number_format($pending->avg('total_price'), 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm font-semibold text-gray-600 uppercase">Produk Diperlukan</p>
            <p class="text-3xl font-bold text-purple-600">{{ $pending->sum(fn($o) => $o->orderDetails->sum('qty')) }}</p>
        </div>
    </div>

    @if($pending->count() > 0)
        <!-- Orders Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Memerlukan Rx</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending as $order)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 font-bold">
                                        #{{ $order->id }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $order->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        @foreach($order->orderDetails as $detail)
                                            <span class="block text-sm text-gray-700">
                                                {{ $detail->medicine->name }} <span class="text-gray-600">x{{ $detail->qty }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($order->requiresRecipe())
                                        <span class="inline-block text-orange-700 bg-orange-100 px-3 py-1 rounded-full text-xs font-semibold">
                                            Ya ✓
                                        </span>
                                    @else
                                        <span class="inline-block text-gray-700 bg-gray-100 px-3 py-1 rounded-full text-xs font-semibold">
                                            Tidak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <p class="font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y') }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('orders.verify', $order) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                                            ✓ Verifikasi
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="text-6xl mb-4">✓</div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Semuanya Selesai!</h3>
            <p class="text-gray-600">Tidak ada pesanan tertunda untuk diverifikasi.</p>
        </div>
    @endif
</div>
@endsection
