<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal (Mass Assignment).
     * Penting: store_id dan user_id harus ada agar logika CreateProduct berjalan.
     */
    protected $fillable = [
        'store_id',      // ID Toko pemilik produk
        'user_id',       // ID User (Seller) pemilik produk
        'category_id',   // Kategori produk
        'name',          // Nama Produk
        'slug',          // URL ramah SEO
        'description',   // Deskripsi
        'price',         // Harga
        'stock',         // Stok
        'image_path',    // Lokasi gambar
        'is_published',  // Status tayang (opsional)
    ];

    /**
     * Konversi tipe data otomatis.
     */
    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_published' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI (Relationships)
    |--------------------------------------------------------------------------
    */

    /**
     * Produk dimiliki oleh satu Toko (Store).
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Produk dimiliki oleh satu User (Seller).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Produk termasuk dalam satu Kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Produk memiliki banyak Review dari pembeli.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Produk bisa ada di banyak Keranjang (CartItem).
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Produk bisa ada di banyak Pesanan (OrderItem).
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}   