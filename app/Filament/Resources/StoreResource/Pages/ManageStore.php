<?php

namespace App\Filament\Resources\StoreResource\Pages;

use App\Filament\Resources\StoreResource;
use App\Models\Store;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ManageStore extends Page
{
    // Gunakan StoreResource
    protected static string $resource = StoreResource::class;
    
    // Nonaktifkan view, karena kita hanya ingin redirect
    protected static bool $shouldRegisterNavigation = false;

    // Tambahkan ini agar Filament tahu View mana yang digunakan (walaupun kita redirect)
    protected static string $view = 'filament.pages.manage-store'; 
    
    // Ini adalah logic utamanya:
    public function mount(): void
    {
        // Cari Store milik user yang sedang login
        $store = Store::query()->where('user_id', Auth::id())->first();

        if ($store) {
            // Jika Store sudah ada, redirect ke halaman Edit
            $this->redirect(StoreResource::getUrl('edit', ['record' => $store]));
        } else {
            // Jika Store belum ada, redirect ke halaman Create
            $this->redirect(StoreResource::getUrl('create'));
        }
    }
}