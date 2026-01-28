<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    private $geminiApiKey;
    private $geminiApiUrl = 'https://generativelanguage.googleapis.com/v1beta1/models/gemini-2.5-pro:generateContent';

    public function __construct()
    {
        $this->geminiApiKey = config('services.gemini.api_key') ?? env('GEMINI_API_KEY');
    }

    /**
     * Handle incoming chat messages and return appropriate responses
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $message = $request->message;
        $messageLower = trim(strtolower($message));
        
        // Try rule-based system FIRST
        $ruleBasedResponse = $this->processMessage($messageLower, $request->user_id);
        
        // If rule-based response is default response, try Gemini API
        if ($this->isDefaultResponse($ruleBasedResponse) && $this->geminiApiKey && $this->geminiApiKey !== 'your_gemini_api_key_here') {
            $response = $this->getGeminiResponse($message, $request->user_id);
            if ($response) {
                return response()->json([
                    'success' => true,
                    'reply' => $response,
                    'timestamp' => now()->format('H:i'),
                    'source' => 'gemini'
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'reply' => $ruleBasedResponse,
            'timestamp' => now()->format('H:i'),
            'source' => 'rule_based'
        ]);
    }

    /**
     * Check if response is the default response
     */
    private function isDefaultResponse($response)
    {
        return strpos($response, 'Maaf, saya kurang memahami pertanyaan Anda') !== false;
    }

    /**
     * Get response from Gemini API with system prompt
     */
    private function getGeminiResponse($message, $userId = null)
    {
        try {
            // System prompt for Gemini
            $systemPrompt = "Kamu adalah Health Assistant untuk MedicStore, apotek online terpercaya Indonesia. " .
                "Kamu membantu pelanggan dengan:\n" .
                "1. Rekomendasi obat berdasarkan gejala\n" .
                "2. Informasi tentang pengiriman dan pembayaran\n" .
                "3. Panduan cara upload resep dokter\n" .
                "4. Menjawab pertanyaan umum tentang apotek\n" .
                "5. Status pesanan pelanggan\n\n" .
                "PENTING:\n" .
                "- Selalu ingatkan untuk konsultasi dokter untuk kondisi serius\n" .
                "- Gunakan emoji untuk membuat respons lebih menarik\n" .
                "- Respons HARUS dalam Bahasa Indonesia\n" .
                "- Jika ditanya tentang obat, selalu berikan info tentang efek samping\n" .
                "- Respons singkat, informatif, dan ramah\n" .
                "- Jangan memberikan diagnosa medis, hanya rekomendasi umum";

            // Add user context if available
            $userContext = "";
            if ($userId) {
                $latestOrder = Order::where('user_id', $userId)->orderByDesc('order_date')->first();
                if ($latestOrder) {
                    $userContext = "\nKonteks: User punya pesanan terakhir #" . $latestOrder->id . " dengan status: " . $latestOrder->status;
                }
            }

            $payload = [
                'system' => [
                    [
                        'text' => $systemPrompt
                    ]
                ],
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            [
                                'text' => $message . $userContext
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 1024,
                ]
            ];

            $response = Http::timeout(30)
                ->post($this->geminiApiUrl . '?key=' . $this->geminiApiKey, $payload);

            \Log::info('Gemini API Response Status: ' . $response->status());
            \Log::info('Gemini API Response: ' . $response->body());

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    \Log::info('Gemini API Success: Got response from Gemini');
                    return $data['candidates'][0]['content']['parts'][0]['text'];
                }
            } else {
                \Log::warning('Gemini API Error: HTTP ' . $response->status());
            }

            return null;

        } catch (\Exception $e) {
            \Log::error('Gemini API Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Process the user message and return appropriate response (Fallback/Rule-based system)
     */
    private function processMessage($message, $userId = null)
    {
        // Check for greetings
        if ($this->matchesKeyword($message, ['halo', 'hi', 'hello', 'assalamualaikum', 'pagi', 'siang', 'sore', 'malam'])) {
            return $this->greetingResponse();
        }

        // Check for order tracking
        if ($this->matchesKeyword($message, ['order', 'pesanan', 'status', 'dimana', 'mana pesanan', 'track order', 'cek pesanan'])) {
            return $this->orderTrackingResponse($userId);
        }

        // Check for prescription upload guide
        if ($this->matchesKeyword($message, ['resep', 'upload', 'unggah', 'bagaimana', 'cara', 'how to upload', 'gimana'])) {
            return $this->prescriptionGuideResponse($message);
        }

        // Check for symptom-based recommendations
        if ($this->matchesKeyword($message, ['demam', 'fever', 'panas', 'sakit', 'sick', 'migrain', 'headache', 'batuk', 'cough', 'flu', 'masuk angin', 'perut', 'stomach', 'diare', 'diarrhea', 'pusing', 'dizziness', 'nyeri', 'pain', 'alergi', 'allergy', 'obat', 'apa obat'])) {
            return $this->recommendMedicineResponse($message);
        }

        // Check for FAQ
        if ($this->matchesKeyword($message, ['faq', 'tanya', 'pertanyaan', 'sering ditanya', 'help', 'bantuan', 'apa itu', 'fungsi', 'benefit', 'manfaat', 'harga', 'price', 'jam buka', 'jam operasional', 'jam kerja'])) {
            return $this->faqResponse($message);
        }

        // Default response
        return $this->defaultResponse();
    }

    /**
     * Match user message against keywords
     */
    private function matchesKeyword($message, $keywords)
    {
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Greeting response
     */
    private function greetingResponse()
    {
        $greetings = [
            "👋 **Halo! Selamat datang di MedicStore!** 🏥\n\n" .
            "Saya adalah Health Assistant siap membantu Anda 24/7\n\n" .
            "📋 **Yang bisa saya bantu:**\n" .
            "💊 Rekomendasi obat berdasarkan gejala\n" .
            "📦 Cek status pesanan Anda\n" .
            "📄 Panduan upload resep dokter\n" .
            "❓ Tanya jawab seputar apotek\n\n" .
            "Silakan tanyakan gejala atau kebutuhan Anda! 😊",
            
            "😊 **Halo! Saya asisten kesehatan MedicStore** 🏥\n\n" .
            "Senang bertemu dengan Anda! Saya siap membantu.\n\n" .
            "🤝 **Apa yang bisa saya lakukan?**\n" .
            "💊 Merekomendasikan obat untuk gejala Anda\n" .
            "📦 Melacak status pesanan\n" .
            "📋 Menjelaskan cara upload resep\n" .
            "💬 Menjawab pertanyaan umum\n\n" .
            "Apa yang sedang mengganggu? Ceritakan gejala Anda! 💙",
        ];
        return $greetings[array_rand($greetings)];
    }

    /**
     * Order tracking response
     */
    private function orderTrackingResponse($userId)
    {
        if (!$userId) {
            return "🔐 **Perlu Login!**\n\n" .
                "Untuk mengecek status pesanan, silakan login ke akun Anda terlebih dahulu.\n\n" .
                "Sudah punya akun? Klik tombol Login di halaman utama! 😊";
        }

        // Get the latest order for the user
        $order = Order::where('user_id', $userId)
            ->orderByDesc('order_date')
            ->first();

        if (!$order) {
            return "📭 **Belum Ada Pesanan**\n\n" .
                "Anda belum melakukan pembelian.\n\n" .
                "🛒 Jelajahi katalog obat kami yang lengkap dan terpercaya!\n" .
                "Dapatkan obat berkualitas dengan harga terbaik. 💊";
        }

        $statusMessages = [
            'pending' => '⏳ Menunggu Konfirmasi Apoteker',
            'verified' => '✅ Pesanan Dikonfirmasi',
            'shipped' => '🚚 Dalam Pengiriman',
            'completed' => '🎉 Pesanan Diterima',
        ];

        $status = $statusMessages[$order->status] ?? $order->status;

        return "📋 **STATUS PESANAN ANDA**\n" .
            "═══════════════════════════\n\n" .
            "🔖 Order ID: **#" . $order->id . "**\n" .
            "📅 Tanggal: " . $order->order_date->format('d F Y • H:i') . "\n" .
            "💰 Total: **Rp " . number_format($order->total_price, 0, ',', '.') . "**\n" .
            "📍 Status: " . $status . "\n\n" .
            "═══════════════════════════\n\n" .
            "Ada pertanyaan lain? Tanya saja! 😊";
    }

    /**
     * Prescription upload guide
     */
    private function prescriptionGuideResponse($message)
    {
        if ($this->matchesKeyword($message, ['obat yang butuh resep', 'cara upload', 'bagaimana upload', 'gimana upload', 'unggah resep'])) {
            return "📄 **PANDUAN UPLOAD RESEP DOKTER**\n" .
                "═════════════════════════════\n\n" .
                "Anda ingin membeli obat yang memerlukan resep? Ikuti langkah berikut:\n\n" .
                "**📝 LANGKAH-LANGKAH:**\n" .
                "1️⃣  Pilih obat yang diinginkan\n" .
                "2️⃣  Tambahkan ke keranjang (🛒)\n" .
                "3️⃣  Klik Checkout\n" .
                "4️⃣  Upload foto/scan resep dokter\n" .
                "5️⃣  Pastikan resep **jelas & lengkap**\n" .
                "6️⃣  Klik 'Pesan Sekarang'\n" .
                "7️⃣  Apoteker kami akan verifikasi\n" .
                "8️⃣  Pesanan diproses jika disetujui ✅\n\n" .
                "**📋 SPESIFIKASI RESEP:**\n" .
                "📸 Format: JPG, PNG, atau PDF\n" .
                "📐 Ukuran Maks: 5 MB\n" .
                "✨ Harus jelas & tidak terpotong\n\n" .
                "⏱️ Proses verifikasi: 1-2 jam\n\n" .
                "Pertanyaan lain? Tanya saja! 😊";
        }

        return "📄 **PANDUAN UPLOAD RESEP**\n" .
            "═════════════════════════════\n\n" .
            "Kami menerima resep dokter untuk obat-obatan tertentu:\n\n" .
            "📋 **Kapan Perlu Upload Resep?**\n" .
            "• Membeli obat keras/obat resep\n" .
            "• Sesuai dengan regulasi kesehatan\n\n" .
            "📸 **Format Resep yang Diterima:**\n" .
            "✅ JPG / JPEG\n" .
            "✅ PNG\n" .
            "✅ PDF\n\n" .
            "⚠️ **Persyaratan:**\n" .
            "• Maks 5MB per file\n" .
            "• Resep harus jelas & lengkap\n" .
            "• Verifikasi oleh apoteker profesional\n\n" .
            "Butuh bantuan lebih lanjut? Chat saya! 💬";
    }

    /**
     * Recommend medicines based on symptoms
     */
    private function recommendMedicineResponse($message)
    {
        $recommendations = [];

        // Fever/Demam
        if ($this->matchesKeyword($message, ['demam', 'fever', 'panas', 'panas tinggi'])) {
            $recommendations = [
                'symptom' => 'Demam',
                'searchTerms' => ['demam', 'fever', 'paracetamol', 'ibuprofen', 'aspirin'],
            ];
        }
        // Headache/Migrain
        elseif ($this->matchesKeyword($message, ['migrain', 'headache', 'pusing', 'sakit kepala'])) {
            $recommendations = [
                'symptom' => 'Sakit Kepala/Migrain',
                'searchTerms' => ['sakit kepala', 'headache', 'paracetamol', 'ibuprofen'],
            ];
        }
        // Cough/Batuk
        elseif ($this->matchesKeyword($message, ['batuk', 'cough', 'batuk kering', 'batuk berdahak'])) {
            $recommendations = [
                'symptom' => 'Batuk',
                'searchTerms' => ['cough', 'batuk', 'syrup'],
            ];
        }
        // Flu
        elseif ($this->matchesKeyword($message, ['flu', 'masuk angin', 'flu ringan'])) {
            $recommendations = [
                'symptom' => 'Flu/Masuk Angin',
                'searchTerms' => ['flu', 'vitamin', 'paracetamol', 'immune'],
            ];
        }
        // Stomach/Perut
        elseif ($this->matchesKeyword($message, ['perut', 'stomach', 'sakit perut', 'mual'])) {
            $recommendations = [
                'symptom' => 'Sakit Perut',
                'searchTerms' => ['stomach', 'antacid', 'perut', 'heartburn'],
            ];
        }
        // Diarrhea/Diare
        elseif ($this->matchesKeyword($message, ['diare', 'diarrhea', 'mencret', 'buang air'])) {
            $recommendations = [
                'symptom' => 'Diare',
                'searchTerms' => ['diare', 'diarrhea', 'antibiotic'],
            ];
        }
        // Allergy/Alergi
        elseif ($this->matchesKeyword($message, ['alergi', 'allergy', 'gatal', 'bentol'])) {
            $recommendations = [
                'symptom' => 'Alergi',
                'searchTerms' => ['allergy', 'alergi', 'skin', 'hydrocortisone', 'cream'],
            ];
        }
        else {
            return "🤔 Saya tidak sepenuhnya memahami gejala Anda. Bisa jelaskan lebih detail?\n\n"
                . "Gejala yang saya bisa bantu:\n"
                . "• Demam/Panas\n"
                . "• Sakit Kepala/Migrain\n"
                . "• Batuk\n"
                . "• Flu/Masuk Angin\n"
                . "• Sakit Perut\n"
                . "• Diare\n"
                . "• Alergi\n\n"
                . "Tanyakan gejala Anda dengan lebih spesifik! 😊";
        }

        // Search for medicines using OR conditions on name and description
        $medicines = Medicine::where(function ($query) use ($recommendations) {
            foreach ($recommendations['searchTerms'] as $term) {
                $query->orWhere('name', 'like', '%' . $term . '%')
                      ->orWhere('description', 'like', '%' . $term . '%');
            }
        })
        ->where('stock', '>', 0) // Only available medicines
        ->limit(5)
        ->get(['id', 'name', 'price', 'stock']);

        $response = "💊 **REKOMENDASI UNTUK " . strtoupper($recommendations['symptom']) . "**\n" .
            "═════════════════════════════\n\n";

        if ($medicines->isNotEmpty()) {
            $response .= "✨ **Obat-obatan yang Tersedia:**\n\n";
            
            foreach ($medicines as $index => $medicine) {
                $response .= ($index + 1) . ". **" . $medicine->name . "**\n";
                $response .= "   💰 Harga: Rp " . number_format($medicine->price, 0, ',', '.') . "\n";
                $response .= "   📦 Stok: " . $medicine->stock . " unit tersedia ✓\n\n";
            }
        } else {
            $response .= "😔 **Maaf, Stok Sedang Kosong**\n\n" .
                "Obat yang direkomendasikan sementara tidak tersedia.\n\n" .
                "💡 Silakan:\n" .
                "• Cek kembali nanti\n" .
                "• Konsultasikan dengan apoteker kami\n" .
                "• Cari obat sejenis di katalog\n\n";
        }

        $response .= "═════════════════════════════\n\n" .
            "⚠️ **DISCLAIMER PENTING:**\n" .
            "Rekomendasi ini hanya untuk informasi umum dan bukan pengganti konsultasi medis profesional.\n\n" .
            "🏥 Jika gejala:\n" .
            "• Tidak membaik dalam 3 hari\n" .
            "• Semakin parah\n" .
            "• Muncul gejala baru\n\n" .
            "⚡ **SEGERA KONSULTASI DENGAN DOKTER!**\n\n" .
            "🛒 Ingin membeli? Buka katalog lengkap kami sekarang! 🏥";

        return $response;
    }

    /**
     * FAQ response
     */
    private function faqResponse($message)
    {
        $faqs = [
            'jam' => "⏰ **JAM OPERASIONAL MEDICSTORE**\n" .
                "═══════════════════════════\n\n" .
                "📅 **Hari Kerja:**\n" .
                "Senin - Jumat: 08:00 - 21:00 WIB\n\n" .
                "📅 **Akhir Pekan:**\n" .
                "Sabtu: 09:00 - 20:00 WIB\n" .
                "Minggu: 10:00 - 18:00 WIB\n\n" .
                "📞 **Hubungi Kami:**\n" .
                "📱 Customer Service: (021) 555-0123\n" .
                "📧 Email: tanya@medicstore.com\n\n" .
                "🤝 Tim kami siap membantu Anda! 😊",

            'pembayaran' => "💳 **METODE PEMBAYARAN KAMI**\n" .
                "═══════════════════════════\n\n" .
                "✅ **Metode yang Kami Terima:**\n\n" .
                "🏦 **Transfer Bank**\n" .
                "BCA, Mandiri, BRI, Maybank\n\n" .
                "📱 **E-Wallet**\n" .
                "GoPay • OVO • DANA • LinkAja\n\n" .
                "💳 **Kartu Kredit**\n" .
                "Cicilan tanpa bunga (tersedia)\n\n" .
                "🔒 Proses pembayaran 100% aman & terenkripsi\n\n" .
                "Ada pertanyaan? Hubungi CS kami! 😊",

            'pengiriman' => "📦 **INFO PENGIRIMAN KAMI**\n" .
                "═══════════════════════════\n\n" .
                "✈️ **Wilayah Pengiriman:**\n\n" .
                "🚚 **Jabodetabek:**\n" .
                "Estimasi 1-2 hari kerja\n\n" .
                "🚚 **Luar Jabodetabek:**\n" .
                "Estimasi 2-5 hari kerja\n\n" .
                "💰 **Ongkir Gratis:**\n" .
                "Pembelian > Rp 100.000 ✓\n\n" .
                "📍 **Lacak Pesanan:**\n" .
                "Real-time tracking tersedia di akun Anda\n\n" .
                "Butuh bantuan? Chat kami! 💬",

            'retur' => "🔄 **KEBIJAKAN PENGEMBALIAN**\n" .
                "═══════════════════════════\n\n" .
                "✅ **Bisa Retur Dalam 7 Hari Jika:**\n\n" .
                "📋 Alasan Retur:\n" .
                "✓ Produk cacat atau rusak\n" .
                "✓ Produk tidak sesuai deskripsi\n" .
                "✓ Kemasan dalam kondisi original\n\n" .
                "⚠️ **Syarat & Ketentuan:**\n" .
                "• Pengemasan asli & tidak terbuka\n" .
                "• Ada bukti pembelian\n" .
                "• Hubungi CS untuk proses retur\n\n" .
                "📞 Hubungi kami: tanya@medicstore.com\n" .
                "Kami siap membantu! 😊",

            'resep' => "📋 **OBAT BERRESEP DOKTER**\n" .
                "═══════════════════════════\n\n" .
                "⚕️ **Produk Tertentu Memerlukan Resep:**\n\n" .
                "📋 **Proses Pembelian:**\n" .
                "1️⃣ Pilih obat berresep\n" .
                "2️⃣ Lanjut ke checkout\n" .
                "3️⃣ Upload resep dokter\n" .
                "4️⃣ Apoteker kami verifikasi\n" .
                "5️⃣ Pesanan dikirim setelah disetujui\n\n" .
                "🔒 **Privasi Terjamin:**\n" .
                "Data resep Anda sangat pribadi & aman\n\n" .
                "⏱️ Verifikasi: maksimal 2 jam\n\n" .
                "Pertanyaan? Hubungi kami! 😊",

            'harga' => "💰 **HARGA KOMPETITIF KAMI**\n" .
                "═══════════════════════════\n\n" .
                "🏆 **Komitmen Kami:**\n" .
                "✅ Harga terbaik dengan kualitas terjamin\n" .
                "✅ Produk 100% original & berlisensi\n" .
                "✅ Bandingkan dengan apotek lain!\n\n" .
                "🎁 **Penawaran Spesial:**\n" .
                "🎉 Promo & diskon rutin\n" .
                "💎 Member loyalty program\n" .
                "📲 Follow media sosial untuk update terbaru\n\n" .
                "💡 Cari obat tertentu? Chat asisten saya! 😊",
        ];

        // Default FAQ
        $defaultFAQ = "❓ **FREQUENTLY ASKED QUESTIONS**\n" .
            "═══════════════════════════\n\n" .
            "Saya bisa menjawab pertanyaan Anda tentang:\n\n" .
            "⏰ Jam operasional (tanya 'jam buka')\n" .
            "💳 Metode pembayaran (tanya 'pembayaran')\n" .
            "📦 Pengiriman (tanya 'pengiriman')\n" .
            "🔄 Retur produk (tanya 'retur')\n" .
            "📋 Obat berresep (tanya 'resep')\n" .
            "💰 Harga (tanya 'harga')\n\n" .
            "🤔 Topik apa yang ingin Anda tahu?\n" .
            "Tanyakan sekarang! 😊";

        foreach ($faqs as $key => $answer) {
            if (strpos($message, $key) !== false) {
                return $answer;
            }
        }

        return $defaultFAQ;
    }

    /**
     * Default response
     */
    private function defaultResponse()
    {
        return "😊 **Maaf, saya kurang memahami pertanyaan Anda**\n\n" .
            "Silakan coba tanyakan tentang:\n\n" .
            "💊 **REKOMENDASI OBAT**\n" .
            "Tanya gejala Anda: 'batuk', 'demam', 'sakit kepala', dll\n\n" .
            "📦 **STATUS PESANAN**\n" .
            "Cek pesanan Anda: 'cek pesanan', 'order', 'status'\n\n" .
            "📄 **PANDUAN RESEP**\n" .
            "Cara upload resep: 'resep', 'upload resep', 'gimana upload'\n\n" .
            "❓ **PERTANYAAN UMUM**\n" .
            "FAQ: 'jam buka', 'pembayaran', 'pengiriman', 'harga'\n\n" .
            "👋 **SAPAAN**\n" .
            "Atau cukup bilang: 'halo', 'hai'\n\n" .
            "💬 **Ada yang bisa saya bantu? Tanya sekarang!** 🤔";
    }
}
