@extends('layouts.app')

@section('title', 'Tambah Obat - MedicStore')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Tambah Obat Baru</h1>
        <p class="text-gray-600">Buat entri obat baru dalam sistem</p>
    </div>

    <!-- Form -->
    <form action="{{ route('medicines.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-8">
        @csrf

        <!-- Basic Information -->
        <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b">Informasi Dasar</h2>

        <!-- Name -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Obat *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Contoh: Paracetamol 500mg">
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi *</label>
            <textarea id="description" name="description" rows="4" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Berikan detail tentang obat...">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Category -->
        <div class="mb-6">
            <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Kategori *</label>
            <select id="category_id" name="category_id" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Pilih kategori...</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Pricing & Stock -->
        <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b">Harga & Inventaris</h2>

        <div class="grid md:grid-cols-3 gap-6 mb-6">
            <!-- Price -->
            <div>
                <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Harga (Rp) *</label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" step="1000" min="0" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="50000">
                @error('price')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Stock -->
            <div>
                <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">Stok Awal *</label>
                <input type="number" id="stock" name="stock" value="{{ old('stock', 50) }}" min="0" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="50">
                @error('stock')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Needs Prescription -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Resep Diperlukan</label>
                <div class="flex items-center mt-2">
                    <input type="checkbox" id="needs_recipe" name="needs_recipe" value="1" {{ old('needs_recipe') ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                    <label for="needs_recipe" class="ml-3 text-gray-700">Obat ini memerlukan resep</label>
                </div>
                @error('needs_recipe')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Image Upload -->
        <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b">Gambar Obat</h2>

        <div class="mb-6">
            <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">Unggah Gambar (JPG, PNG, maks 5MB)</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition"
                onclick="document.getElementById('image').click()">
                <div class="text-4xl mb-2">📷</div>
                <p class="text-gray-600 font-semibold">Klik untuk unggah atau seret dan lepaskan</p>
                <p class="text-sm text-gray-500">SVG, PNG, JPG atau GIF (maks. 5MB)</p>
            </div>
            <input type="file" id="image" name="image" accept="image/*" style="display:none;"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            @error('image')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Form Actions -->
        <div class="flex gap-4 pt-6 border-t">
            <a href="{{ route('medicines.index') }}"
                class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 rounded-lg transition">
                Batal
            </a>
            <button type="submit"
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                Buat Obat
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.createElement('img');
                preview.src = event.target.result;
                preview.className = 'w-32 h-32 object-cover rounded-lg mx-auto mt-4';
                const container = document.querySelector('[onclick="document.getElementById(\'image\').click()"]');
                const existing = container.querySelector('img');
                if (existing) existing.remove();
                container.appendChild(preview);
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
