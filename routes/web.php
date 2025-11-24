<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use App\Models\Product;
use App\Models\Order;

/*
|--------------------------------------------------------------------------
| Web Routes - Hiyoucan E-Commerce Complete
|--------------------------------------------------------------------------
*/

// --- 1. Public Routes ---

// Homepage
Route::get('/', function () {
    $query = Product::with('category');
    
    if (Auth::check() && Auth::user()->role === 'buyer') {
        $products = $query->inRandomOrder()->take(8)->get(); // Rekomendasi Buyer
    } else {
        $products = $query->latest()->take(8)->get(); // Guest
    }
    return view('welcome', compact('products'));
});

// Shop Page (All Products)
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');

// Product Detail (Public can view detail)
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('shop.show');


// --- 2. Authenticated Routes (Buyer & Seller) ---
Route::middleware(['auth'])->group(function () {
    
    // ==============================
    // BUYER FEATURES
    // ==============================

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{item}', [CartController::class, 'destroy'])->name('cart.destroy');
    
    // Checkout
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

    // Review
    Route::get('/reviews/create/{product}/{order}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews/store/{product}/{order}', [ReviewController::class, 'store'])->name('reviews.store');

    // Dashboard Buyer
    Route::get('/dashboard', function () {
        // Redirect Seller/Admin to Filament
        if (in_array(Auth::user()->role, ['seller', 'admin'])) {
            if (Auth::user()->role === 'seller' && Auth::user()->seller_status !== 'approved') {
                return redirect()->route('seller.pending');
            }
            return redirect('/admin');
        }

        // Buyer Data
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
            
        return view('dashboard', compact('orders'));
    })->name('dashboard');


    // ==============================
    // SELLER VERIFICATION
    // ==============================
    Route::get('/seller/status', function () {
        $user = Auth::user();
        if ($user->role !== 'seller') return redirect('/dashboard');
        if ($user->seller_status === 'approved') return redirect('/admin');
        return view('auth.pending-seller', compact('user'));
    })->name('seller.pending');

    Route::delete('/seller/delete-account', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role === 'seller' && $user->seller_status === 'rejected') {
            $user->delete();
            Auth::logout();
            return redirect('/')->with('success', 'Akun dihapus.');
        }
        return back();
    })->name('seller.delete');

});

// --- 3. Auth Routes ---
require __DIR__.'/auth.php';