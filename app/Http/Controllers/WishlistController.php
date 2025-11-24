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

    public function toggle(Request $request, $productId)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Login dulu ya!'], 401);
        }

        $user = Auth::user();
        $exists = Wishlist::where('user_id', $user->id)->where('product_id', $productId)->first();

        if ($exists) {
            $exists->delete();
            $message = 'Dihapus dari Wishlist 💔';
            $action = 'removed';
        } else {
            Wishlist::create(['user_id' => $user->id, 'product_id' => $productId]);
            $message = 'Masuk Wishlist ❤️';
            $action = 'added';
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => $message, 'action' => $action]);
        }

        return back()->with('success', $message);
    }
}