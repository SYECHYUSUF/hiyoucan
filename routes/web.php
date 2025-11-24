<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AuthController;
use App\Models\Product;
use App\Models\Order;

/*
|--------------------------------------------------------------------------
| Web Routes - Hiyoucan E-Commerce Complete
|--------------------------------------------------------------------------
*/

// --- 1. PUBLIC ROUTES (Bisa diakses siapa saja) ---

// Homepage
Route::get('/', function () {
    $query = Product::with('category');
    
    // Jika Buyer login: Tampilkan rekomendasi acak
    if (Auth::check() && Auth::user()->role === 'buyer') {
        $products = $query->inRandomOrder()->take(8)->get(); 
    } else {
        // Jika Guest: Tampilkan produk terbaru
        $products = $query->latest()->take(8)->get();
    }
    return view('welcome', compact('products'));
});

// Shop Page (List Semua Produk + Filter)
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');

// Product Detail
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('shop.show');


// --- 2. AUTHENTICATED ROUTES (Harus Login: Buyer, Seller, Admin) ---
Route::middleware(['auth'])->group(function () {
    
    // ==============================
    // FITUR PROFIL (Edit Akun)
    // ==============================
    Route::get('/profile', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');


    // ==============================
    // FITUR WISHLIST (Favorit)
    // ==============================
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');


    // ==============================
    // FITUR KERANJANG & BELANJA
    // ==============================
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{item}', [CartController::class, 'destroy'])->name('cart.destroy');
    
    // Buy Now (Langsung ke Checkout)
    Route::post('/buy-now/{product}', [CartController::class, 'buyNow'])->name('cart.buyNow');

    // Halaman Checkout (Pilih Alamat)
    Route::get('/checkout', [CartController::class, 'checkoutPage'])->name('checkout.page');
    
    // Proses Checkout (Simpan Order)
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');
    // Route cadangan jika ada form lama yang mengarah ke 'checkout'
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('checkout');


    // ==============================
    // FITUR REVIEW (Ulasan)
    // ==============================
    Route::get('/reviews/create/{product}/{order}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews/store/{product}/{order}', [ReviewController::class, 'store'])->name('reviews.store');


    // ==============================
    // DASHBOARD REDIRECTOR
    // ==============================
    Route::get('/dashboard', function () {
        // 1. Logic untuk Seller & Admin
        if (in_array(Auth::user()->role, ['seller', 'admin'])) {
            // Jika Seller belum approved -> Halaman Pending
            if (Auth::user()->role === 'seller' && Auth::user()->seller_status !== 'approved') {
                return redirect()->route('seller.pending');
            }
            // Jika Admin atau Seller Approved -> Masuk Filament
            return redirect('/admin');
        }

        // 2. Logic untuk Buyer (Tampilkan Pesanan)
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
            
        return view('dashboard', compact('orders'));
    })->name('dashboard');


    // ==============================
    // SELLER VERIFICATION (Status Akun)
    // ==============================
    
    // Halaman Status Peninjauan
    Route::get('/seller/status', function () {
        $user = Auth::user();
        
        if ($user->role !== 'seller') {
            return redirect('/dashboard');
        }
        if ($user->seller_status === 'approved') {
            return redirect('/admin');
        }

        return view('auth.pending-seller', compact('user'));
    })->name('seller.pending');

    // Hapus Akun (Jika Rejected)
    Route::delete('/seller/delete-account', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($user->role === 'seller' && $user->seller_status === 'rejected') {
            $user->delete();
            Auth::logout();
            return redirect('/')->with('success', 'Akun Anda telah dihapus.');
        }
        
        return back();
    })->name('seller.delete');

});

// --- 3. AUTH ROUTES (Login/Register) ---
require __DIR__.'/auth.php';