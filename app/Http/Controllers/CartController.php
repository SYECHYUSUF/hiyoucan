<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with(['items.product'])->where('user_id', Auth::id())->first();
        return view('cart.index', compact('cart'));
    }

    // FITUR 1: ADD TO CART (AJAX SUPPORT)
    public function addToCart(Request $request, $productId)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Silakan login terlebih dahulu'], 401);
        }

        $user = Auth::user();
        $product = Product::findOrFail($productId);

        if ($product->stock < 1) {
            return response()->json(['status' => 'error', 'message' => 'Stok habis!'], 400);
        }

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1]);
        }

        // Jika request dari AJAX (fetch), return JSON
        if ($request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Berhasil masuk keranjang! 🛒']);
        }

        return back()->with('success', 'Produk masuk keranjang!');
    }

    // FITUR 2: BELI LANGSUNG (SKIP CART)
    public function buyNow(Request $request, $productId)
    {
        // Masukkan ke keranjang dulu
        $this->addToCart($request, $productId);
        // Langsung lempar ke halaman checkout
        return redirect()->route('checkout.page');
    }

    public function destroy($itemId)
    {
        CartItem::destroy($itemId);
        return back()->with('success', 'Item dihapus.');
    }

    // FITUR 3: HALAMAN CHECKOUT (PILIH ALAMAT)
    public function checkoutPage()
{
    $user = Auth::user();
    $cart = Cart::with('items.product')->where('user_id', $user->id)->first();
    
    // Cek keranjang kosong
    if (!$cart || $cart->items->isEmpty()) {
        return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
    }

    // Ambil alamat user
    $addresses = \App\Models\Address::where('user_id', $user->id)->get();

    return view('cart.checkout', compact('cart', 'addresses'));
}

    // FITUR 4: PROSES ORDER (FINAL)
    public function processCheckout(Request $request)
{
    // Validasi Alamat Wajib Ada
    $request->validate([
        'address_id' => 'nullable|exists:addresses,id',
        'new_label' => 'required_without:address_id',
        'new_recipient' => 'required_without:address_id',
        'new_phone' => 'required_without:address_id',
        'new_address' => 'required_without:address_id',
    ]);

    $user = Auth::user();
    
    // 1. Tentukan Alamat Pengiriman
    if ($request->filled('address_id')) {
        $addr = \App\Models\Address::find($request->address_id);
        $shippingAddress = "{$addr->recipient_name} ({$addr->phone_number})\n{$addr->full_address}";
    } else {
        // Simpan Alamat Baru ke Database
        $newAddr = \App\Models\Address::create([
            'user_id' => $user->id,
            'label' => $request->new_label,
            'recipient_name' => $request->new_recipient,
            'phone_number' => $request->new_phone,
            'full_address' => $request->new_address,
            'is_default' => true
        ]);
        $shippingAddress = "{$newAddr->recipient_name} ({$newAddr->phone_number})\n{$newAddr->full_address}";
    }

    // 2. Proses Keranjang -> Order
    $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

    DB::transaction(function () use ($user, $cart, $shippingAddress) {
        // Grouping per Toko (Multi-Vendor)
        $itemsByStore = $cart->items->groupBy(fn($item) => $item->product->store_id);

        foreach ($itemsByStore as $storeId => $items) {
            $total = 0;
            foreach ($items as $item) $total += $item->product->price * $item->quantity;

            $order = Order::create([
                'user_id' => $user->id,
                'store_id' => $storeId,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'total_price' => $total,
                'status' => 'pending', // Sesuai syarat: Pending dulu
                'payment_status' => 'unpaid',
                'shipping_address' => $shippingAddress,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
                $item->product->decrement('stock', $item->quantity);
            }
        }
        $cart->items()->delete();
    });

    return redirect()->route('dashboard')->with('success', 'Pesanan berhasil dibuat! Menunggu pembayaran.');
}
}