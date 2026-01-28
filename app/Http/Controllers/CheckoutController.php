<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    /**
     * Show checkout form with address, shipping, and payment options.
     */
    public function show()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        // Check if any item requires recipe
        $requiresRecipe = false;
        foreach ($cart as $item) {
            $medicine = Medicine::find($item['medicine_id']);
            if ($medicine && $medicine->needs_recipe) {
                $requiresRecipe = true;
                break;
            }
        }

        $total = collect($cart)->sum('subtotal');
        $user = auth()->user();
        
        // Shipping options
        $shippingOptions = [
            'kurir' => [
                'name' => 'Kurir (1-3 hari kerja)',
                'costs' => [
                    'jakarta' => 10000,
                    'jawa' => 25000,
                    'sumatra' => 40000,
                    'kalimantan' => 50000,
                    'sulawesi' => 55000,
                    'nusa_tenggara' => 60000,
                    'maluku' => 70000,
                    'papua' => 80000,
                ]
            ],
            'pickup' => [
                'name' => 'Ambil di Apotek (Gratis)',
                'cost' => 0,
                'address' => 'MedicStore, Jl. Kesehatan No. 123, Jakarta'
            ]
        ];
        
        // Payment methods
        $paymentMethods = [
            'transfer' => 'Transfer Bank (BCA, Mandiri, BNI)',
            'ewallet' => 'E-Wallet (GoPay, OVO, DANA)',
            'cod' => 'Bayar di Tempat (COD)',
        ];

        return view('checkout.show', compact(
            'cart',
            'total',
            'requiresRecipe',
            'user',
            'shippingOptions',
            'paymentMethods'
        ));
    }

    /**
     * Process checkout and create order with full details.
     */
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        // Check if any item requires recipe
        $requiresRecipe = false;
        foreach ($cart as $item) {
            $medicine = Medicine::find($item['medicine_id']);
            if ($medicine && $medicine->needs_recipe) {
                $requiresRecipe = true;
                break;
            }
        }

        // Validate all required fields
        $validated = $request->validate([
            // Customer Info
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            
            // Address
            'delivery_address' => 'required|string|max:500',
            'delivery_city' => 'required|string|max:100',
            'delivery_postal_code' => 'required|string|max:10',
            
            // Shipping
            'shipping_method' => 'required|in:kurir,pickup',
            'shipping_city' => 'required_if:shipping_method,kurir|nullable|string',
            
            // Payment
            'payment_method' => 'required|in:transfer,ewallet,cod',
            
            // Recipe if needed
            'recipe_file' => $requiresRecipe ? 'required|file|mimes:pdf,jpeg,png,jpg|max:5120' : 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            
            // Notes
            'notes' => 'nullable|string|max:500',
        ]);

        // Validate stock availability
        foreach ($cart as $item) {
            $medicine = Medicine::find($item['medicine_id']);
            if (!$medicine || $medicine->stock < $item['qty']) {
                return back()->with('error', "Stok tidak mencukupi untuk {$item['name']}.");
            }
        }

        // Calculate shipping cost
        $shippingCost = 0;
        if ($validated['shipping_method'] === 'kurir') {
            $shippingCosts = [
                'jakarta' => 10000,
                'jawa' => 25000,
                'sumatra' => 40000,
                'kalimantan' => 50000,
                'sulawesi' => 55000,
                'nusa_tenggara' => 60000,
                'maluku' => 70000,
                'papua' => 80000,
            ];
            $shippingCost = $shippingCosts[$validated['shipping_city'] ?? 'jakarta'] ?? 10000;
        }

        // Calculate total with shipping
        $subtotal = collect($cart)->sum('subtotal');
        $total = $subtotal + $shippingCost;

        // Create order
        $orderData = [
            'user_id' => auth()->id(),
            'order_date' => now(),
            'total_price' => $total,
            'status' => 'pending',
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'delivery_address' => $validated['delivery_address'],
            'delivery_city' => $validated['delivery_city'],
            'delivery_postal_code' => $validated['delivery_postal_code'],
            'shipping_method' => $validated['shipping_method'],
            'shipping_cost' => $shippingCost,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ];

        // Handle prescription file upload
        if ($request->hasFile('recipe_file')) {
            $recipePath = $request->file('recipe_file')->store('prescriptions', 'public');
            $orderData['recipe_file'] = $recipePath;
        }

        $order = Order::create($orderData);

        // Create order details
        foreach ($cart as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'medicine_id' => $item['medicine_id'],
                'qty' => $item['qty'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        // Clear cart
        session()->forget('cart');

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }
}
