<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display order for a specific user.
     */
    public function show(Order $order)
    {
        // Only allow user to view their own order or pharmacist/admin to view any
        if (auth()->id() !== $order->user_id && !auth()->user()->isPharmacist() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $order->load('user', 'orderDetails.medicine');
        return view('orders.show', compact('order'));
    }

    /**
     * Display list of orders for patients.
     */
    public function patientOrders()
    {
        $orders = auth()->user()->orders()->with('orderDetails')->latest()->paginate(10);
        return view('orders.patient-list', compact('orders'));
    }

    /**
     * Display orders pending verification (Pharmacist view).
     */
    public function pendingOrders()
    {
        $pending = Order::with('user', 'orderDetails.medicine')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('orders.pending-list', compact('pending'));
    }

    /**
     * Verify and process an order (Pharmacist action).
     */
    public function verify(Order $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Order cannot be verified at this stage.');
        }

        // Check stock availability
        foreach ($order->orderDetails as $detail) {
            if ($detail->medicine->stock < $detail->qty) {
                return back()->with('error', "Insufficient stock for {$detail->medicine->name}.");
            }
        }

        // Update order status
        $order->update(['status' => 'verified']);

        // Decrease stock for each item
        foreach ($order->orderDetails as $detail) {
            $detail->medicine->decrement('stock', $detail->qty);
        }

        return back()->with('success', 'Order verified and stock updated.');
    }

    /**
     * Update order status (Mark as shipped or completed).
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:verified,shipped,completed',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated.');
    }

    /**
     * Display low stock alerts for pharmacist.
     */
    public function lowStockAlerts()
    {
        $lowStockMedicines = \App\Models\Medicine::where('stock', '<', 10)->get();
        return view('orders.low-stock', compact('lowStockMedicines'));
    }
}
