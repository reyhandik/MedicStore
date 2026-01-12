<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Medicine;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard with sales reports and user management.
     */
    public function admin()
    {
        $totalRevenue = Order::where('status', 'verified')->sum('total_price');
        $totalOrders = Order::count();
        $pendingCount = Order::where('status', 'pending')->count();
        $verifiedOrders = Order::where('status', 'verified')->count();
        $shippedCount = Order::where('status', 'shipped')->count();
        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $pharmacistCount = User::where('role', 'pharmacist')->count();
        $patientCount = User::where('role', 'patient')->count();
        $totalMedicines = Medicine::count();
        $categoriesCount = Category::count();

        // Recent orders
        $recentOrders = Order::with('user')->latest()->limit(5)->get();

        // Top selling medicines
        $topMedicines = Medicine::join('order_details', 'medicines.id', '=', 'order_details.medicine_id')
            ->select('medicines.*', \DB::raw('SUM(order_details.qty) as total_sold'))
            ->groupBy('medicines.id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact(
            'totalRevenue',
            'totalOrders',
            'pendingCount',
            'verifiedOrders',
            'shippedCount',
            'totalUsers',
            'adminCount',
            'pharmacistCount',
            'patientCount',
            'totalMedicines',
            'categoriesCount',
            'recentOrders',
            'topMedicines'
        ));
    }

    /**
     * Show pharmacist dashboard with order management.
     */
    public function pharmacist()
    {
        $pendingCount = Order::where('status', 'pending')->count();
        $pendingOrders = Order::where('status', 'pending')
            ->with('user', 'orderDetails.medicine')
            ->latest()
            ->get();
        
        $verifiedOrders = Order::where('status', 'verified')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $verifiedToday = Order::where('status', 'verified')
            ->whereDate('updated_at', today())
            ->count();

        // Low stock medicines
        $lowStockMedicines = Medicine::where('stock', '<', 10)->get();
        $lowStockCount = $lowStockMedicines->count();
        
        // Critical low stock (< 5 units)
        $criticalLowStock = Medicine::where('stock', '<', 5)->get();
        
        // Needs restock (10-20 units)
        $needsRestock = Medicine::whereBetween('stock', [5, 20])->get();
        
        // Total medicines for calculating normal stock
        $totalMedicines = Medicine::count();

        // Prescription pending orders
        $rxPendingCount = Order::where('status', 'pending')->count();

        // Recently verified orders
        $recentlyVerified = Order::where('status', 'verified')
            ->with('user', 'orderDetails.medicine')
            ->latest('updated_at')
            ->limit(5)
            ->get();

        return view('dashboard.pharmacist', compact(
            'pendingCount',
            'pendingOrders',
            'verifiedOrders',
            'shippedOrders',
            'completedOrders',
            'verifiedToday',
            'lowStockMedicines',
            'lowStockCount',
            'criticalLowStock',
            'needsRestock',
            'totalMedicines',
            'rxPendingCount',
            'recentlyVerified'
        ));
    }

    /**
     * Show patient dashboard with order history.
     */
    public function patient()
    {
        $recentOrders = auth()->user()->orders()->latest()->limit(10)->get();
        $totalOrders = auth()->user()->orders()->count();
        $totalSpent = Order::where('user_id', auth()->id())->where('status', 'completed')->sum('total_price');
        $pendingOrders = auth()->user()->orders()->where('status', 'pending')->count();
        $completedOrders = auth()->user()->orders()->where('status', 'completed')->count();
        $avgOrderValue = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;

        return view('dashboard.patient', compact('recentOrders', 'totalOrders', 'totalSpent', 'pendingOrders', 'completedOrders', 'avgOrderValue'));
    }
}
