<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    // Pastikan user_id dan image ada disini
    protected $fillable = [
        'user_id',     // <--- INI WAJIB ADA
        'name',
        'description',
        'image',       // Sesuaikan dengan nama kolom di DB (bisa 'image' atau 'logo_path')
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}