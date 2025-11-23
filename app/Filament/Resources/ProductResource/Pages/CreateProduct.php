<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Store;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        // 1. Set User ID (Seller)
        $data['user_id'] = $user->id;

        // 2. Set Store ID
        // Kita cari toko milik user ini.
        // Asumsi: User hasOne Store.
        $store = Store::where('user_id', $user->id)->first();

        if ($store) {
            $data['store_id'] = $store->id;
        } else {
            // Jika user belum punya toko tapi mencoba buat produk (sebagai Admin mungkin, atau Seller baru)
            // Kita bisa handle error atau set null jika database membolehkan, 
            // TAPI karena migrasi 'store_id' constrained (wajib), kita harus pastikan toko ada.
            
            if ($user->role === 'admin') {
                // Jika admin, mungkin kita butuh field store_id di form (tapi disembunyikan utk seller)
                // Untuk kesederhanaan sekarang, kita ambil toko pertama atau toko admin jika ada.
                // Atau biarkan error dulu agar flow "Seller Wajib Bikin Toko" terjaga.
            } else {
                Notification::make()
                    ->title('Error')
                    ->body('Anda harus membuat Toko terlebih dahulu sebelum menambah produk.')
                    ->danger()
                    ->send();
                
                $this->halt(); // Stop proses save
            }
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}