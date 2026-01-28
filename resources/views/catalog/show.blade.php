@extends('layouts.app')

@section('title', $medicine->name . ' - MedicStore')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-emerald-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="{{ route('catalog.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-emerald-600 md:ml-2">Katalog</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $medicine->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="grid md:grid-cols-2 gap-0">
                <!-- Left: Image -->
                <div class="bg-gray-50 p-8 md:p-12 flex items-center justify-center relative">
                    <div class="relative w-full aspect-square max-w-sm mx-auto">
                        @if($medicine->image)
                            <img src="{{ asset('storage/' . $medicine->image) }}" alt="{{ $medicine->name }}" class="w-full h-full object-contain mix-blend-multiply transition duration-500 hover:scale-105">
                        @else
                            <div class="w-full h-full bg-emerald-50 rounded-2xl flex items-center justify-center shadow-inner">
                                <svg class="w-32 h-32 text-emerald-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M7.707 2.293a1 1 0 00-1.414 1.414L4.414 6H2a1 1 0 00-1 1v10a1 1 0 001 1h16a1 1 0 001-1V7a1 1 0 00-1-1h-2.414l-1.879-1.879A1 1 0 0011 4h-2a1 1 0 00-1.293.293l-.414.414-1.586-1.586zM10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    @if($medicine->needs_recipe)
                        <div class="absolute top-6 right-6">
                            <span class="bg-amber-100 text-amber-800 text-xs font-bold px-4 py-2 rounded-full shadow-sm flex items-center gap-2 border border-amber-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Resep Dokter
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Right: Content -->
                <div class="p-8 md:p-12 flex flex-col justify-between">
                    <div>
                        <div class="mb-6">
                            <span class="text-emerald-600 font-bold tracking-wider text-xs uppercase bg-emerald-50 px-3 py-1 rounded-full">{{ $medicine->category->name }}</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $medicine->name }}</h1>
                        
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-4xl font-extrabold text-emerald-600">Rp {{ number_format($medicine->price, 0, ',', '.') }}</h2>
                            <span class="text-sm text-gray-400">/ per unit</span>
                        </div>

                        <div class="prose prose-emerald text-gray-600 mb-8 max-w-none">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi Produk</h3>
                            <p class="leading-relaxed">{{ $medicine->description ?? 'Deskripsi produk belum tersedia.' }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-8 mt-auto">
                         @if($medicine->stock > 0)
                            <form @submit.prevent="addToCart($el)" action="{{ route('cart.add') }}" method="POST" class="flex flex-col sm:flex-row gap-4">
                                @csrf
                                <input type="hidden" name="medicine_id" value="{{ $medicine->id }}">
                                
                                <div class="w-full sm:w-32">
                                    <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Jumlah</label>
                                    <div class="relative rounded-xl shadow-sm">
                                        <input type="number" name="qty" value="1" min="1" max="{{ $medicine->stock }}" 
                                            class="w-full text-center font-bold text-gray-900 border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 py-3">
                                    </div>
                                </div>

                                <div class="flex-grow">
                                    <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider invisible">Action</label>
                                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-emerald-200 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            </form>
                            
                            <div class="mt-4 flex items-center gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Stok tersedia: <strong>{{ $medicine->stock }}</strong> unit</span>
                            </div>
                        @else
                            <div class="bg-red-50 text-red-700 px-6 py-4 rounded-xl border border-red-100 flex items-center gap-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <p class="font-bold">Stok Habis</p>
                                    <p class="text-sm">Maaf, produk ini sedang tidak tersedia saat ini.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
