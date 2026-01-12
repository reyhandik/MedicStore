@extends('layouts.app')

@section('title', 'Manajemen Obat - MedicStore')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Inventaris Obat</h1>
            <p class="text-gray-600">Kelola semua obat dalam sistem</p>
        </div>
        <a href="{{ route('medicines.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition">
            + Tambah Obat
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form action="{{ route('medicines.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" name="search" placeholder="Cari berdasarkan nama..." value="{{ request('search') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="w-full md:w-48">
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                Cari
            </button>
        </form>
    </div>

    <!-- Medicines Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Gambar</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Harga</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Stok</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Resep Diperlukan</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicines as $medicine)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <!-- Image -->
                            <td class="px-6 py-4">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                    @if($medicine->image)
                                        <img src="{{ asset('storage/' . $medicine->image) }}" alt="{{ $medicine->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-lg">💊</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Name & Description -->
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $medicine->name }}</p>
                                <p class="text-sm text-gray-600">{{ Str::limit($medicine->description, 50) }}</p>
                            </td>

                            <!-- Category -->
                            <td class="px-6 py-4">
                                <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $medicine->category->name }}
                                </span>
                            </td>

                            <!-- Price -->
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900">Rp {{ number_format($medicine->price, 0, ',', '.') }}</p>
                            </td>

                            <!-- Stock -->
                            <td class="px-6 py-4 text-center">
                                @if($medicine->stock < 10)
                                    <span class="inline-block text-red-700 bg-red-100 px-3 py-1 rounded-full text-sm font-bold">
                                        {{ $medicine->stock }}
                                    </span>
                                @elseif($medicine->stock < 30)
                                    <span class="inline-block text-yellow-700 bg-yellow-100 px-3 py-1 rounded-full text-sm font-bold">
                                        {{ $medicine->stock }}
                                    </span>
                                @else
                                    <span class="inline-block text-green-700 bg-green-100 px-3 py-1 rounded-full text-sm font-bold">
                                        {{ $medicine->stock }}
                                    </span>
                                @endif
                            </td>

                            <!-- Prescription Required -->
                            <td class="px-6 py-4 text-center">
                                @if($medicine->needs_recipe)
                                    <span class="inline-block text-orange-700 bg-orange-100 px-3 py-1 rounded-full text-xs font-semibold">
                                        Ya ✓
                                    </span>
                                @else
                                    <span class="inline-block text-gray-700 bg-gray-100 px-3 py-1 rounded-full text-xs font-semibold">
                                        Tidak
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('catalog.show', $medicine) }}"
                                        class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                        Lihat
                                    </a>
                                    <a href="{{ route('medicines.edit', $medicine) }}"
                                        class="text-yellow-600 hover:text-yellow-800 font-semibold text-sm">
                                        Edit
                                    </a>
                                    <form action="{{ route('medicines.destroy', $medicine) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus obat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <p class="text-gray-600">Obat tidak ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $medicines->links() }}
    </div>
</div>
@endsection
