<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser; // <--- IMPORT INI
use Filament\Panel; // <--- IMPORT INI
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Tambahkan "implements FilamentUser"
class User extends Authenticatable implements FilamentUser 
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'seller_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // --- LOGIKA KEAMANAN UTAMA ---
    public function canAccessPanel(Panel $panel): bool
    {
        // Hanya berlaku untuk panel dengan ID 'admin' (default Filament)
        if ($panel->getId() === 'admin') {
            // 1. Admin: Boleh masuk
            if ($this->role === 'admin') {
                return true;
            }

            // 2. Seller: Boleh masuk HANYA JIKA status approved
            if ($this->role === 'seller' && $this->seller_status === 'approved') {
                return true;
            }

            // 3. Buyer / Seller Pending / Seller Rejected: DILARANG MASUK
            return false;
        }

        return false;
    }
    // -----------------------------

    public function store(): HasOne
    {
        return $this->hasOne(Store::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function addresses()
{
    return $this->hasMany(Address::class);
}
}