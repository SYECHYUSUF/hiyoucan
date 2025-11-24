<?php

namespace App\Filament\Resources\StoreResource\Pages;

use App\Filament\Resources\StoreResource;
use App\Models\Store;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListStores extends ListRecords
{
    protected static string $resource = StoreResource::class;

    public function mount(): void
    {
        $user = Auth::user();

        // Jika user adalah SELLER (bukan Admin)
        if ($user->role !== 'admin') {
            // Cek apakah dia sudah punya toko?
            $store = Store::where('user_id', $user->id)->first();

            if ($store) {
                // Jika sudah, paksa redirect ke halaman EDIT
                $this->redirect(StoreResource::getUrl('edit', ['record' => $store]));
            } else {
                // Jika belum, paksa redirect ke halaman CREATE
                $this->redirect(StoreResource::getUrl('create'));
            }
        }
        
        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        return [
            // Admin masih butuh tombol create manual
            Actions\CreateAction::make(),
        ];
    }
}