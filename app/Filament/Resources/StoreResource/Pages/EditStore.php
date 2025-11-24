<?php

namespace App\Filament\Resources\StoreResource\Pages;

use App\Filament\Resources\StoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStore extends EditRecord
{
    protected static string $resource = StoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Seller tidak boleh menghapus tokonya sendiri sembarangan, 
            // tapi Admin boleh. Kita sembunyikan delete jika bukan admin.
            Actions\DeleteAction::make()
                ->visible(fn () => \Illuminate\Support\Facades\Auth::user()->role === 'admin'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}