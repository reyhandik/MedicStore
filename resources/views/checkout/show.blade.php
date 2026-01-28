@extends('layouts.app')

@section('title', 'Checkout - MedicStore')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Title -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">🛒 Checkout</h1>
            <p class="text-gray-600">Lengkapi informasi di bawah untuk menyelesaikan pesanan Anda</p>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid lg:grid-cols-12 gap-8">
                <!-- Left Column: Forms -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- 1. DETAIL PESANAN -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 px-6 py-4 border-b border-emerald-200">
                            <h2 class="font-bold text-lg text-emerald-900 flex items-center gap-3">
                                <span class="bg-emerald-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm">1</span>
                                Detail Pesanan
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            @php $subtotal = 0; @endphp
                            @foreach($cart as $item)
                                @php $subtotal += $item['subtotal']; @endphp
                                <div class="flex items-center justify-between py-3 border-b border-gray-200 last:border-0">
                                    <div class="flex items-center gap-4">
                                        <div class="text-center font-bold text-emerald-600">{{ $item['qty'] }}x</div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $item['name'] }}</p>
                                            <p class="text-sm text-gray-500">Rp {{ number_format($item['price'], 0, ',', '.') }} per unit</p>
                                        </div>
                                    </div>
                                    <p class="font-bold text-gray-800">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- 2. KONFIRMASI ALAMAT -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                            <h2 class="font-bold text-lg text-blue-900 flex items-center gap-3">
                                <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm">2</span>
                                Alamat Pengiriman
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Penerima *</label>
                                    <input type="text" name="customer_name" value="{{ auth()->user()->name }}" placeholder="Masukkan nama lengkap" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    @error('customer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor HP *</label>
                                    <input type="tel" name="customer_phone" placeholder="Contoh: 081234567890" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    @error('customer_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap *</label>
                                <textarea name="delivery_address" placeholder="Jl. ... No. ..., Kota/Kab. ..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" rows="3" required></textarea>
                                @error('delivery_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kota/Kabupaten *</label>
                                    <input type="text" name="delivery_city" placeholder="Contoh: Jakarta" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    @error('delivery_city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Pos *</label>
                                    <input type="text" name="delivery_postal_code" placeholder="Contoh: 12345" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    @error('delivery_postal_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. METODE PENGIRIMAN -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-purple-200">
                            <h2 class="font-bold text-lg text-purple-900 flex items-center gap-3">
                                <span class="bg-purple-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm">3</span>
                                Metode Pengiriman
                            </h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <!-- Kurir Option -->
                            <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-300 hover:bg-purple-50 transition">
                                <input type="radio" name="shipping_method" value="kurir" class="mt-1" onchange="toggleShippingCity()" checked>
                                <div class="ml-4 flex-1">
                                    <p class="font-bold text-gray-900">🚚 Kurir Pengiriman</p>
                                    <p class="text-sm text-gray-600 mt-1">Pengiriman 1-3 hari kerja ke seluruh Indonesia</p>
                                </div>
                            </label>

                            <!-- Shipping City Selection (for Kurir) -->
                            <div id="shipping-city-div" class="pl-10 space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Wilayah Pengiriman *</label>
                                <select name="shipping_city" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" id="shipping_city">
                                    <option value="jakarta">Jakarta - Rp 10.000</option>
                                    <option value="jawa">Jawa (Bandung, Surabaya, dll) - Rp 25.000</option>
                                    <option value="sumatra">Sumatra - Rp 40.000</option>
                                    <option value="kalimantan">Kalimantan - Rp 50.000</option>
                                    <option value="sulawesi">Sulawesi - Rp 55.000</option>
                                    <option value="nusa_tenggara">Nusa Tenggara - Rp 60.000</option>
                                    <option value="maluku">Maluku - Rp 70.000</option>
                                    <option value="papua">Papua - Rp 80.000</option>
                                </select>
                                @error('shipping_city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Pickup Option -->
                            <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-300 hover:bg-purple-50 transition">
                                <input type="radio" name="shipping_method" value="pickup" class="mt-1" onchange="toggleShippingCity()">
                                <div class="ml-4 flex-1">
                                    <p class="font-bold text-gray-900">📍 Ambil di Apotek</p>
                                    <p class="text-sm text-gray-600 mt-1">MedicStore, Jl. Kesehatan No. 123, Jakarta (GRATIS)</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- 4. METODE PEMBAYARAN -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-4 border-b border-orange-200">
                            <h2 class="font-bold text-lg text-orange-900 flex items-center gap-3">
                                <span class="bg-orange-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm">4</span>
                                Metode Pembayaran
                            </h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-orange-300 hover:bg-orange-50 transition">
                                <input type="radio" name="payment_method" value="transfer" class="mt-1" checked>
                                <div class="ml-4 flex-1">
                                    <p class="font-bold text-gray-900">🏦 Transfer Bank</p>
                                    <p class="text-sm text-gray-600 mt-1">BCA, Mandiri, BNI, Maybank</p>
                                </div>
                            </label>

                            <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-orange-300 hover:bg-orange-50 transition">
                                <input type="radio" name="payment_method" value="ewallet" class="mt-1">
                                <div class="ml-4 flex-1">
                                    <p class="font-bold text-gray-900">📱 E-Wallet</p>
                                    <p class="text-sm text-gray-600 mt-1">GoPay, OVO, DANA, LinkAja</p>
                                </div>
                            </label>

                            <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-orange-300 hover:bg-orange-50 transition">
                                <input type="radio" name="payment_method" value="cod" class="mt-1">
                                <div class="ml-4 flex-1">
                                    <p class="font-bold text-gray-900">💵 Bayar di Tempat (COD)</p>
                                    <p class="text-sm text-gray-600 mt-1">Bayar saat barang diterima (tambahan Rp 5.000)</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- 5. UPLOAD RESEP (Jika Ada Obat Keras) -->
                    @if($requiresRecipe)
                    <div class="bg-white rounded-2xl shadow-sm border-2 border-amber-300 overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-50 to-amber-100 px-6 py-4 border-b border-amber-200">
                            <h2 class="font-bold text-lg text-amber-900 flex items-center gap-3">
                                <span class="bg-amber-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm">⚠️</span>
                                Upload Resep Dokter
                            </h2>
                        </div>
                        <div class="p-6">
                            <p class="text-amber-800 mb-4 font-semibold">⚠️ Pesanan Anda mengandung obat keras yang memerlukan resep dokter.</p>
                            
                            <div class="bg-amber-50 border-2 border-dashed border-amber-300 rounded-lg p-8 text-center">
                                <svg class="w-12 h-12 text-amber-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                </svg>
                                <label class="cursor-pointer">
                                    <p class="font-bold text-gray-900 mb-1">Klik untuk upload resep</p>
                                    <p class="text-sm text-gray-600 mb-3">atau drag & drop di sini</p>
                                    <p class="text-xs text-gray-500">Format: JPG, PNG, PDF • Maksimal 5MB</p>
                                    <input type="file" name="recipe_file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" id="recipe_file" required>
                                </label>
                            </div>
                            @error('recipe_file') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    @endif

                    <!-- Catatan -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="font-bold text-lg text-gray-900 flex items-center gap-3">📝 Catatan (Opsional)</h2>
                        </div>
                        <div class="p-6">
                            <textarea name="notes" placeholder="Tambahkan catatan khusus untuk pesanan Anda (misal: jangan bunyi bel, dll)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent" rows="3"></textarea>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Summary -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 sticky top-20 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                            <h3 class="font-bold text-lg text-white">💰 Ringkasan Pesanan</h3>
                        </div>

                        <div class="p-6 space-y-4">
                            <!-- Subtotal -->
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <span class="text-gray-700">Subtotal Obat:</span>
                                <span class="font-bold text-gray-900" id="subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>

                            <!-- Shipping Cost -->
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <span class="text-gray-700">Biaya Pengiriman:</span>
                                <span class="font-bold text-gray-900" id="shipping-cost">Rp 10.000</span>
                            </div>

                            <!-- Total -->
                            <div class="flex justify-between items-center pt-4 border-t-2 border-gray-300">
                                <span class="font-bold text-lg text-gray-900">Total Pembayaran:</span>
                                <span class="font-bold text-2xl text-emerald-600" id="total-price">Rp {{ number_format($subtotal + 10000, 0, ',', '.') }}</span>
                            </div>

                            <!-- Promo Info -->
                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3 mt-4">
                                <p class="text-sm text-emerald-800 font-semibold">✨ Info: Gratis ongkir jika ambil di apotek!</p>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-lg transition transform active:scale-95 mt-6 flex items-center justify-center gap-2">
                                <span>✓ Lanjutkan ke Pembayaran</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>

                            <!-- Additional Info -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4 text-xs text-blue-700 space-y-1">
                                <p>✅ Data Anda aman dan terenkripsi</p>
                                <p>✅ Konfirmasi akan dikirim ke email Anda</p>
                                <p>✅ Apoteker akan verifikasi resep jika ada</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const subtotal = {{ $subtotal }};
const shippingCosts = {
    'jakarta': 10000,
    'jawa': 25000,
    'sumatra': 40000,
    'kalimantan': 50000,
    'sulawesi': 55000,
    'nusa_tenggara': 60000,
    'maluku': 70000,
    'papua': 80000,
};

function updateTotal() {
    const shippingMethod = document.querySelector('input[name="shipping_method"]:checked').value;
    let shippingCost = 0;

    if (shippingMethod === 'kurir') {
        const shippingCity = document.getElementById('shipping_city').value;
        shippingCost = shippingCosts[shippingCity] || 10000;
    }

    const total = subtotal + shippingCost;
    
    document.getElementById('shipping-cost').textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');
    document.getElementById('total-price').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function toggleShippingCity() {
    const shippingMethod = document.querySelector('input[name="shipping_method"]:checked').value;
    const shippingCityDiv = document.getElementById('shipping-city-div');
    const shippingCity = document.getElementById('shipping_city');
    
    if (shippingMethod === 'kurir') {
        shippingCityDiv.style.display = 'block';
        shippingCity.setAttribute('required', 'required');
    } else {
        shippingCityDiv.style.display = 'none';
        shippingCity.removeAttribute('required');
    }
    
    updateTotal();
}

// Event listeners
document.querySelectorAll('input[name="shipping_method"]').forEach(input => {
    input.addEventListener('change', toggleShippingCity);
});

document.getElementById('shipping_city').addEventListener('change', updateTotal);

// Initialize
toggleShippingCity();
</script>
@endsection
