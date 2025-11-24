<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('product')->where('user_id', Auth::id())->get();
        return view('wishlist.index', compact('wishlists'));
    }

    public function toggle($productId)
    {
        $user = Auth::user();
        
        $exists = Wishlist::where('user_id', $user->id)->where('product_id', $productId)->first();

        if ($exists) {
            $exists->delete();
            $message = 'Produk dihapus dari Wishlist';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            $message = 'Produk ditambahkan ke Wishlist';
        }

        return back()->with('success', $message);
    }
}