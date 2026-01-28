<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $hasRecipeRequired = false;

        foreach ($cart as $item) {
            $medicine = Medicine::find($item['medicine_id']);
            if ($medicine) {
                $total += $item['subtotal'];
                if ($medicine->needs_recipe) {
                    $hasRecipeRequired = true;
                }
            }
        }

        return view('cart.index', compact('cart', 'total', 'hasRecipeRequired'));
    }

    /**
     * Add item to cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'qty' => 'required|integer|min:1',
        ]);

        $medicine = Medicine::find($request->medicine_id);

        if (!$medicine || $medicine->stock < $request->qty) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Stok tidak mencukupi.'], 422);
            }
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $cart = session()->get('cart', []);
        $medicineId = $request->medicine_id;

        if (isset($cart[$medicineId])) {
            // Update quantity if item already in cart
            $newQty = $cart[$medicineId]['qty'] + $request->qty;
            if ($medicine->stock < $newQty) {
                if ($request->wantsJson()) {
                    return response()->json(['message' => 'Stok tidak mencukupi.'], 422);
                }
                return back()->with('error', 'Stok tidak mencukupi.');
            }
            $cart[$medicineId]['qty'] = $newQty;
        } else {
            // Add new item to cart
            $cart[$medicineId] = [
                'medicine_id' => $medicineId,
                'name' => $medicine->name,
                'price' => $medicine->price,
                'qty' => $request->qty,
                'subtotal' => $medicine->price * $request->qty,
            ];
        }

        // Recalculate subtotal
        $cart[$medicineId]['subtotal'] = $cart[$medicineId]['price'] * $cart[$medicineId]['qty'];

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Item berhasil ditambahkan ke keranjang.']);
        }

        return back()->with('success', 'Item berhasil ditambahkan ke keranjang.');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, $medicineId)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $medicine = Medicine::find($medicineId);

        if (!$medicine || $medicine->stock < $request->qty) {
            return back()->with('error', 'Insufficient stock available.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$medicineId])) {
            $cart[$medicineId]['qty'] = $request->qty;
            $cart[$medicineId]['subtotal'] = $medicine->price * $request->qty;
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Cart updated.');
    }

    /**
     * Remove item from cart.
     */
    public function remove($medicineId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$medicineId])) {
            unset($cart[$medicineId]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Clear entire cart.
     */
    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared.');
    }
}
