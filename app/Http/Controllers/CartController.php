<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Menampilkan halaman keranjang
    public function index()
    {
        $cart = Cart::with(['items.product'])->where('user_id', Auth::id())->first();
        return view('cart.index', compact('cart'));
    }

    // Tambah produk ke keranjang
    public function addToCart(Request $request, $productId)
    {
        // 1. Cek User Login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. Cek Stok Produk
        $product = Product::findOrFail($productId);
        if ($product->stock < 1) {
            return back()->with('error', 'Stok produk habis!');
        }

        // 3. Ambil atau Buat Keranjang User
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // 4. Cek apakah item sudah ada di keranjang
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Jika ada, tambah quantity
            $cartItem->increment('quantity');
        } else {
            // Jika belum, buat item baru
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk masuk keranjang!');
    }

    // Hapus item dari keranjang
    public function destroy($itemId)
    {
        CartItem::destroy($itemId);
        return back()->with('success', 'Item dihapus.');
    }

    // Proses Checkout
    public function checkout()
    {
        $user = Auth::user();
        $cart = Cart::with('items.product.store')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Keranjang kosong.');
        }

        // Database Transaction agar data aman
        DB::transaction(function () use ($user, $cart) {
            
            // 1. Kelompokkan item berdasarkan Toko (Store)
            // Karena satu order harus per satu toko sesuai logika marketplace
            $itemsByStore = $cart->items->groupBy(function($item) {
                return $item->product->store_id;
            });

            foreach ($itemsByStore as $storeId => $items) {
                $totalPrice = 0;
                
                // Hitung total harga per toko
                foreach ($items as $item) {
                    $totalPrice += $item->product->price * $item->quantity;
                }

                // 2. Buat Order Baru
                $order = Order::create([
                    'user_id' => $user->id,
                    'store_id' => $storeId, // Penting: Order terikat ke Store ID
                    'order_number' => 'ORD-' . strtoupper(uniqid()),
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                    'payment_status' => 'unpaid', // Simulasi belum bayar
                    'shipping_address' => 'Alamat Default User (Simulasi)', 
                ]);

                // 3. Pindahkan Item ke OrderItem & Kurangi Stok
                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                    ]);

                    // Kurangi stok produk
                    $item->product->decrement('stock', $item->quantity);
                }
            }

            // 4. Kosongkan Keranjang setelah sukses
            $cart->items()->delete();
        });

        return redirect()->route('dashboard')->with('success', 'Pesanan berhasil dibuat!');
    }
}