<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // 1. Halaman Shop (List All Products + Search + Filter)
    public function index(Request $request)
    {
        $query = Product::with('category')->where('stock', '>', 0);

        // Filter Pencarian
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter Kategori
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Sorting Harga
        if ($request->has('sort')) {
            if ($request->sort == 'low_high') $query->orderBy('price', 'asc');
            if ($request->sort == 'high_low') $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('shop.index', compact('products', 'categories'));
    }

    // 2. Halaman Detail Produk
    public function show($slug)
    {
        // Ambil produk beserta review dan usernya
        $product = Product::where('slug', $slug)
            ->with(['category', 'store', 'reviews.user'])
            ->firstOrFail();

        // Rekomendasi produk lain di kategori sama
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }
}