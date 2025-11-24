<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id', 
        'store_id', 
        'order_number', 
        'total_price', 
        'status', 
        'payment_status', 
        'shipping_address'
    ];

    // Relasi ke User Pembeli
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Toko Penjual
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    // Relasi ke Detail Item Pesanan
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}