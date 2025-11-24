<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Tampilkan Form Review
    public function create($productId, $orderId)
    {
        $user = Auth::user();
        
        // Validasi: Pastikan Order milik User dan Statusnya 'completed'
        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->firstOrFail();

        $product = Product::findOrFail($productId);

        // Validasi: Cek apakah user sudah pernah review produk ini di order ini
        $existingReview = Review::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('order_id', $orderId)
            ->first();

        if ($existingReview) {
            return redirect()->route('dashboard')->with('error', 'Kamu sudah mereview produk ini.');
        }

        return view('reviews.create', compact('product', 'order'));
    }

    // Simpan Review ke Database
    public function store(Request $request, $productId, $orderId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'order_id' => $orderId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('dashboard')->with('success', 'Terima kasih atas ulasanmu! ⭐');
    }
}