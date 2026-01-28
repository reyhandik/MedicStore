# 🛒 ALUR PEMBELIAN OBAT YANG BARU

## ✨ Alur Pembelian Customer yang Telah Diperbarui

Berikut adalah alur pembelian obat online di MedicStore yang sesuai dengan standar apotek online profesional:

---

## **LANGKAH 1: PENCARIAN PRODUK** 🔍
- Customer membuka halaman Katalog (`/catalog`)
- Browse obat dengan filter kategori dan search
- Lihat harga, stok, dan deskripsi lengkap
- **File terkait**: `resources/views/catalog/index.blade.php`

---

## **LANGKAH 2: MEMASUKKAN KE KERANJANG** 🛒
- Klik tombol "Tambah ke Keranjang"
- Tentukan jumlah obat yang diinginkan
- Keranjang disimpan di session
- **File terkait**: `CartController.php`, `resources/views/cart/index.blade.php`

---

## **LANGKAH 3: KONFIRMASI ALAMAT PENGIRIMAN** 📍
**NEW!** Form pengiriman yang lebih lengkap:
- Nama penerima
- Nomor HP
- Alamat lengkap (jalan, RT/RW, dll)
- Kota/Kabupaten
- Kode pos

**Database**: Order table sekarang punya kolom:
- `customer_name`
- `customer_phone`
- `delivery_address`
- `delivery_city`
- `delivery_postal_code`

---

## **LANGKAH 4: MEMILIH METODE PENGIRIMAN** 🚚

### **Option A: Kurir Pengiriman (Berbayar)**
- Estimasi: 1-3 hari kerja
- Biaya berbeda per wilayah:
  - Jakarta: Rp 10.000
  - Jawa: Rp 25.000
  - Sumatra: Rp 40.000
  - Kalimantan: Rp 50.000
  - Sulawesi: Rp 55.000
  - Nusa Tenggara: Rp 60.000
  - Maluku: Rp 70.000
  - Papua: Rp 80.000

### **Option B: Ambil di Apotek (GRATIS)**
- Lokasi: MedicStore, Jl. Kesehatan No. 123, Jakarta
- Estimasi: Siap diambil 1-2 hari kerja
- Biaya pengiriman: Rp 0

**Database**: Order table punya kolom:
- `shipping_method` (enum: kurir, pickup)
- `shipping_cost`

---

## **LANGKAH 5: MEMILIH METODE PEMBAYARAN** 💳

### **Option 1: Transfer Bank** 🏦
- BCA, Mandiri, BNI, Maybank
- Customer transfer ke rekening MedicStore
- Verifikasi manual oleh admin

### **Option 2: E-Wallet** 📱
- GoPay, OVO, DANA, LinkAja
- Pembayaran instan
- Otomatis terverifikasi

### **Option 3: Bayar di Tempat (COD)** 💵
- Customer bayar saat barang diterima
- Tambahan biaya: Rp 5.000
- Hanya tersedia untuk kurir pengiriman

**Database**: Order table punya kolom:
- `payment_method` (enum: transfer, ewallet, cod)
- `payment_status` (enum: pending, paid, failed)

---

## **LANGKAH 6: KONFIRMASI PESANAN** ✅

Sebelum final submit, customer bisa melihat:
- ✅ Daftar obat + harga
- ✅ Subtotal
- ✅ Biaya pengiriman (real-time update)
- ✅ Total pembayaran (otomatis update)
- ✅ Alamat pengiriman
- ✅ Metode pengiriman & pembayaran

---

## **LANGKAH 7: UPLOAD RESEP (JIKA ADA OBAT KERAS)** 📄

**HANYA MUNCUL JIKA** ada obat dengan `needs_recipe = true`

### Validasi Resep:
- ✅ Format: JPG, PNG, PDF
- ✅ Ukuran maksimal: 5MB
- ✅ Harus jelas dan lengkap

### Proses:
1. Customer upload resep
2. Sistem menyimpan file di `storage/prescriptions/`
3. Apoteker verifikasi resep
4. Jika valid → pesanan diproses
5. Jika invalid → customer diminta upload ulang

**Database**: Order table punya kolom:
- `recipe_file` (path ke file)

---

## **LANGKAH 8: VERIFIKASI APOTEKER** 👨‍⚕️

Setelah pesanan dibuat dengan status `pending`:

### Apoteker melihat:
- Tab "Pesanan Tertunda"
- Daftar semua pesanan baru
- Detail customer & alamat
- Resep dokter (jika ada)

### Apoteker melakukan:
1. **Cek stok** obat di database
2. **Verifikasi resep** (jika ada obat keras)
3. **Approve pesanan** → Status berubah jadi `verified`
4. **Stock berkurang otomatis** saat pesanan diverifikasi

**File terkait**: `OrderController.php`, `resources/views/orders/pending-list.blade.php`

---

## **LANGKAH 9: PENGIRIMAN** 📦

### Jika Kurir:
- Status berubah → `shipped`
- Resi pengiriman dikirim ke email customer
- Customer bisa tracking di aplikasi

### Jika Pickup:
- Status berubah → `ready_for_pickup`
- Customer dapat notifikasi
- Customer ambil ke apotek dengan bukti order

---

## **LANGKAH 10: PESANAN SELESAI** 🎉

- Status berubah → `completed`
- Customer menerima obat
- Pesanan ditutup
- Customer bisa rate & review

---

## 📊 PERUBAHAN DATABASE

### Migration Baru: `2026_01_28_000500_add_checkout_fields_to_orders_table.php`

Kolom yang ditambahkan:
```sql
-- Customer Info
customer_name         VARCHAR(100)
customer_phone        VARCHAR(20)

-- Delivery
delivery_address      TEXT
delivery_city         VARCHAR(100)
delivery_postal_code  VARCHAR(10)

-- Shipping
shipping_method       ENUM('kurir', 'pickup')
shipping_cost         DECIMAL(10,2)

-- Payment
payment_method        ENUM('transfer', 'ewallet', 'cod')
payment_status        ENUM('pending', 'paid', 'failed')

-- Notes
notes                 TEXT
```

---

## 🔄 PERUBAHAN CHECKOUT FLOW

### Sebelumnya:
```
Cart → Checkout → Order (Resep opsional) ❌
```

### Sekarang:
```
1. Cart 🛒
   ↓
2. Detail Pesanan ✓
   ↓
3. Konfirmasi Alamat ✓
   ↓
4. Metode Pengiriman (Real-time price calc) ✓
   ↓
5. Metode Pembayaran ✓
   ↓
6. Upload Resep (jika ada obat keras) ✓
   ↓
7. Review Total & Submit ✓
   ↓
8. Order Created (Status: pending) ✓
   ↓
9. Apoteker Verifikasi ✓
   ↓
10. Pengiriman/Pickup ✓
   ↓
11. Order Complete 🎉
```

---

## 🎯 FITUR BARU

### 1️⃣ **Dynamic Pricing**
- Harga pengiriman berubah sesuai wilayah
- Total otomatis update tanpa refresh halaman

### 2️⃣ **Address Management**
- Simpan lengkap: jalan, kota, kode pos
- Validasi format

### 3️⃣ **Flexible Delivery**
- Pilih kurir atau pickup
- Gratis jika pickup

### 4️⃣ **Multiple Payment Options**
- Transfer, E-wallet, COD
- Tracking pembayaran

### 5️⃣ **Order Notes**
- Customer bisa tambah catatan khusus
- Misal: "Jangan bunyi bel", dll

---

## 📁 FILE YANG BERUBAH

```
✅ app/Http/Controllers/CheckoutController.php (UPDATED)
✅ app/Models/Order.php (UPDATED - tambah fillable fields)
✅ resources/views/checkout/show.blade.php (REPLACED)
✅ database/migrations/ (NEW - add checkout fields)
```

---

## 🚀 CARA RUN

1. **Jalankan Migration**
   ```bash
   php artisan migrate
   ```

2. **Clear Cache**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

3. **Test**
   - Go to `/catalog`
   - Add item to cart
   - Go to `/checkout`
   - Fill semua form sesuai alur baru

---

## ✨ HASILNYA

Customer sekarang bisa:
- ✅ Melengkapi alamat pengiriman lengkap
- ✅ Memilih metode pengiriman (kurir/pickup)
- ✅ Memilih metode pembayaran
- ✅ Lihat total harga real-time
- ✅ Tambah catatan khusus
- ✅ Upload resep jika diperlukan
- ✅ Confirm pesanan sebelum submit

**Alur lebih terstruktur, user-friendly, dan sesuai standar apotek online! 🎉**
