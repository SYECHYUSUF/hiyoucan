<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use Illuminate\Support\Facades\Auth; // <--- INI YANG KURANG TADI

// Halaman Depan
Route::get('/', function () {
    // Ambil produk dari database untuk ditampilkan di homepage
    $products = Product::with('category')->take(8)->get();
    return view('welcome', compact('products'));
});

// Route Belanja (Hanya untuk yang sudah login)
Route::middleware(['auth'])->group(function () {
    
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{item}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

    // Dashboard Buyer Sederhana
    Route::get('/dashboard', function () {
        $orders = \App\Models\Order::with('items.product')->where('user_id', Auth::id())->latest()->get();
        return view('dashboard', compact('orders'));
    })->name('dashboard');
});

require __DIR__.'/auth.php'; // Pastikan route auth bawaan breeze/bawaan laravel tetap ada di bawah