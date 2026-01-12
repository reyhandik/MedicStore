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
     * Show checkout form.
     */
    public function show()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty.');
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

        return view('checkout.show', compact('cart', 'total', 'requiresRecipe'));
    }

    /**
     * Process checkout and create order.
     */
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty.');
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

        // Validate prescription file if required
        if ($requiresRecipe) {
            $request->validate([
                'recipe_file' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
            ]);
        }

        // Validate stock availability before creating order
        foreach ($cart as $item) {
            $medicine = Medicine::find($item['medicine_id']);
            if (!$medicine || $medicine->stock < $item['qty']) {
                return back()->with('error', "Insufficient stock for {$item['name']}.");
            }
        }

        // Create order
        $total = collect($cart)->sum('subtotal');
        
        $orderData = [
            'user_id' => auth()->id(),
            'total_price' => $total,
            'status' => 'pending',
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
            ->with('success', 'Order created successfully. Waiting for pharmacist verification.');
    }
}
