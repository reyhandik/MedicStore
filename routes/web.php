<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

// Public routes
Route::get('/', [CatalogController::class, 'index'])->name('home')->middleware('web');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{medicine}', [CatalogController::class, 'show'])->name('catalog.show');

// Cart routes (public, but session-based)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{medicineId}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{medicineId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Authenticated patient routes
Route::middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/my-orders', [OrderController::class, 'patientOrders'])->name('orders.patient');
    Route::get('/dashboard', [DashboardController::class, 'patient'])->name('dashboard.patient');
});

// Authenticated pharmacist routes
Route::middleware(['auth', 'role:pharmacist'])->group(function () {
    Route::get('/pharmacist/dashboard', [DashboardController::class, 'pharmacist'])->name('dashboard.pharmacist');
    Route::get('/pharmacist/pending-orders', [OrderController::class, 'pendingOrders'])->name('orders.pending');
    Route::post('/pharmacist/orders/{order}/verify', [OrderController::class, 'verify'])->name('orders.verify');
    Route::post('/pharmacist/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/pharmacist/low-stock', [OrderController::class, 'lowStockAlerts'])->name('orders.low-stock');
    Route::resource('medicines', MedicineController::class)->except(['show']);
    Route::delete('/medicines/{medicine}/image', [MedicineController::class, 'deleteImage'])->name('medicines.delete-image');
});

// Authenticated admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::resource('medicines', MedicineController::class);
});

// Auth routes (from Laravel Breeze)
require __DIR__.'/auth.php';

// Authenticated user profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
