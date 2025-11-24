<?php

namespace App\Filament\Resources\StoreResource\Pages;

use App\Filament\Resources\StoreResource;
use App\Models\Store;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class CreateStore extends CreateRecord
{
    protected static string $resource = StoreResource::class;

    // Cek lagi saat mau masuk halaman create
    public function mount(): void
    {
        $user = Auth::user();

        // Jika bukan admin dan sudah punya toko, tendang ke edit
        if ($user->role !== 'admin' && Store::where('user_id', $user->id)->exists()) {
            
            $store = Store::where('user_id', $user->id)->first();
            
            Notification::make()
                ->title('Anda sudah memiliki toko')
                ->warning()
                ->send();

            $this->redirect(StoreResource::getUrl('edit', ['record' => $store]));
            return;
        }

        parent::mount();
    }

    // Isi user_id otomatis sebelum simpan
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        // Setelah buat, arahkan ke index (yang nanti akan auto-redirect ke edit)
        return $this->getResource()::getUrl('index');
    }
}