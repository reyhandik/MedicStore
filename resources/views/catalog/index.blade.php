@extends('layouts.app')

@section('title', 'Katalog Obat - MedicStore')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="relative bg-emerald-600 overflow-hidden">
        <div class="absolute inset-0">
            <svg class="absolute bottom-0 left-0 transform -translate-x-1/2 translate-y-1/2 w-64 h-64 text-emerald-500 opacity-30" fill="currentColor" viewBox="0 0 200 200"><circle cx="100" cy="100" r="100"/></svg>
            <svg class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 w-96 h-96 text-emerald-400 opacity-20" fill="currentColor" viewBox="0 0 200 200"><circle cx="100" cy="100" r="100"/></svg>
        </div>
        <div class="relative max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 text-center sm:text-left flex flex-col sm:flex-row items-center justify-between">
            <div class="sm:max-w-xl mb-8 sm:mb-0">
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                    <span class="block">Kesehatan Anda</span>
                    <span class="block text-emerald-200">Prioritas Kami</span>
                </h1>
                <p class="mt-4 text-xl text-emerald-100 italic">
                    Temukan ribuan produk kesehatan asli dan terpercaya dengan pengiriman cepat ke seluruh Indonesia.
                </p>
                <div class="mt-8 flex justify-center sm:justify-start gap-4">
                    <a href="#katalog" class="px-8 py-3 border border-transparent text-base font-medium rounded-full text-emerald-700 bg-white hover:bg-emerald-50 md:text-lg md:px-10 shadow-lg transform transition hover:-translate-y-1">
                        Belanja Sekarang
                    </a>
                    <a href="#" class="px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-emerald-800 hover:bg-emerald-900 md:text-lg md:px-10 shadow-lg transform transition hover:-translate-y-1">
                        Upload Resep
                    </a>
                </div>
            </div>
            <div class="hidden sm:block">
                 <!-- Illustration placeholder -->
                 <div class="w-64 h-64 bg-white/20 backdrop-blur-lg rounded-full flex items-center justify-center border-4 border-white/30 shadow-2xl">
                    <svg class="w-32 h-32 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                 </div>
            </div>
        </div>
    </div>

    <div id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Search & Filter Card -->
        <div class="bg-white rounded-2xl shadow-xl p-6 -mt-24 relative z-10 border border-gray-100">
            <form method="GET" action="{{ route('catalog.index') }}">
                <div class="grid md:grid-cols-12 gap-4 items-end">
                    <!-- Search -->
                    <div class="md:col-span-7">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Cari Produk</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition" 
                                placeholder="Nama obat, vitamin, atau keluhan...">
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                        <div class="relative">
                            <select name="category_id" class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent appearance-none transition">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="md:col-span-2">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-emerald-200 transition transform active:scale-95 flex items-center justify-center gap-2">
                            <span>Temukan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Medicine Grid -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-8 border-l-4 border-emerald-500 pl-4">Produk Tersedia</h2>
            
            @if($medicines->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($medicines as $medicine)
                        <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 flex flex-col h-full">
                            <!-- Image Area -->
                            <div class="relative bg-gray-50 h-56 flex items-center justify-center overflow-hidden rounded-t-2xl">
                                @if($medicine->image)
                                    <img src="{{ asset('storage/' . $medicine->image) }}" alt="{{ $medicine->name }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                                @else
                                    <svg class="w-20 h-20 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7.707 2.293a1 1 0 00-1.414 1.414L4.414 6H2a1 1 0 00-1 1v10a1 1 0 001 1h16a1 1 0 001-1V7a1 1 0 00-1-1h-2.414l-1.879-1.879A1 1 0 0011 4h-2a1 1 0 00-1.293.293l-.414.414-1.586-1.586zM10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                    </svg>
                                @endif
                                
                                <!-- Category Badge -->
                                <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-emerald-700 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                    {{ $medicine->category->name }}
                                </span>

                                @if($medicine->needs_recipe)
                                    <span class="absolute top-3 right-3 bg-amber-100 text-amber-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Resep
                                    </span>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-5 flex-grow flex flex-col">
                                <h3 class="font-bold text-lg text-gray-900 mb-1 group-hover:text-emerald-600 transition">{{ $medicine->name }}</h3>
                                <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $medicine->description ?? 'Deskripsi tidak tersedia.' }}</p>

                                <div class="mt-auto pt-4 border-t border-gray-50">
                                    <div class="flex justify-between items-end mb-4">
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-400">Harga per unit</span>
                                            <span class="text-xl font-bold text-emerald-600">Rp {{ number_format($medicine->price, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="text-right">
                                            @if($medicine->stock > 10)
                                                <span class="inline-block w-2.5 h-2.5 bg-green-500 rounded-full"></span>
                                                <span class="text-xs text-gray-500">Stok banyak</span>
                                            @elseif($medicine->stock > 0)
                                                <span class="inline-block w-2.5 h-2.5 bg-yellow-500 rounded-full"></span>
                                                <span class="text-xs text-yellow-600">Sisa {{ $medicine->stock }}</span>
                                            @else
                                                <span class="inline-block w-2.5 h-2.5 bg-red-500 rounded-full"></span>
                                                <span class="text-xs text-red-600">Habis</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('catalog.show', $medicine) }}" class="px-4 py-2 border border-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-50 hover:text-emerald-600 hover:border-emerald-200 transition text-center text-sm">
                                            Detail
                                        </a>
                                        @if($medicine->stock > 0)
                                            <form @submit.prevent="addToCart($el)" action="{{ route('cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="medicine_id" value="{{ $medicine->id }}">
                                                <input type="hidden" name="qty" value="1">
                                                <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-200 transition text-sm flex items-center justify-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                    Beli
                                                </button>
                                            </form>
                                        @else
                                            <button disabled class="w-full px-4 py-2 bg-gray-100 text-gray-400 font-medium rounded-lg cursor-not-allowed text-sm">
                                                Habis
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Pagination -->
                <div class="mt-12">
                    {{ $medicines->links() }}
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <div class="bg-gray-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Tidak ada obat ditemukan</h3>
                    <p class="text-gray-500 mt-1">Coba kata kunci lain atau ubah filter kategori Anda.</p>
                    <a href="{{ route('catalog.index') }}" class="mt-6 inline-block text-emerald-600 font-medium hover:text-emerald-700">
                        Reset Filter
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
