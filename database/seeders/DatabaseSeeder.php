<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Kategori Skincare
        $categories = [
            'Serum' => 'serum',
            'Toner' => 'toner',
            'Moisturizer' => 'moisturizer',
            'Sunscreen' => 'sunscreen',
            'Facial Wash' => 'facial-wash',
            'Paket Glowing' => 'paket-glowing',
        ];

        foreach ($categories as $name => $slug) {
            Category::create([
                'name' => $name,
                'slug' => $slug,
            ]);
        }

        // 2. Buat Akun ADMIN
        User::create([
            'name' => 'Admin Hiyoucan',
            'email' => 'admin@hiyoucan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'seller_status' => null,
        ]);

        // 3. Buat Akun SELLER 1 (Hiyoucan Official)
        $seller1 = User::create([
            'name' => 'Hiyoucan Official',
            'email' => 'official@hiyoucan.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'seller_status' => 'approved', // Langsung disetujui
        ]);

        // Buat Toko untuk Seller 1
        $store1 = Store::create([
            'user_id' => $seller1->id,
            'name' => 'Hiyoucan Official Store',
            'description' => 'Toko resmi Hiyoucan Skincare. Dijamin ori 100%.',
            'logo_path' => null, // Bisa diisi path gambar jika ada
        ]);

        // Buat Produk untuk Toko 1
        Product::create([
            'store_id' => $store1->id,
            'user_id' => $seller1->id,
            'category_id' => Category::where('slug', 'serum')->first()->id,
            'name' => 'Brightening Gold Serum',
            'slug' => 'brightening-gold-serum',
            'description' => '<p>Serum emas yang mencerahkan wajah dalam 7 hari. Mengandung Niacinamide 10%.</p>',
            'price' => 150000,
            'stock' => 100,
            'image_path' => null,
            'is_published' => true,
        ]);

        Product::create([
            'store_id' => $store1->id,
            'user_id' => $seller1->id,
            'category_id' => Category::where('slug', 'paket-glowing')->first()->id,
            'name' => 'Paket Lengkap Glowing',
            'slug' => 'paket-lengkap-glowing',
            'description' => '<p>Satu paket terdiri dari Facial Wash, Toner, Serum, Day Cream, dan Night Cream.</p>',
            'price' => 450000,
            'stock' => 50,
            'image_path' => null,
            'is_published' => true,
        ]);

        // 4. Buat Akun SELLER 2 (Reseller)
        $seller2 = User::create([
            'name' => 'Cantik Skincare',
            'email' => 'reseller@hiyoucan.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'seller_status' => 'approved',
        ]);

        $store2 = Store::create([
            'user_id' => $seller2->id,
            'name' => 'Cantik Skincare Jakarta',
            'description' => 'Distributor resmi area Jakarta Selatan.',
            'logo_path' => null,
        ]);

        Product::create([
            'store_id' => $store2->id,
            'user_id' => $seller2->id,
            'category_id' => Category::where('slug', 'sunscreen')->first()->id,
            'name' => 'UV Shield Sunscreen SPF 50',
            'slug' => 'uv-shield-sunscreen',
            'description' => '<p>Melindungi kulit dari sinar UV A dan UV B tanpa rasa lengket.</p>',
            'price' => 85000,
            'stock' => 200,
            'image_path' => null,
            'is_published' => true,
        ]);

        // 5. Buat Akun BUYER (Pembeli)
        User::create([
            'name' => 'Siti Pembeli',
            'email' => 'buyer@hiyoucan.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'seller_status' => null,
        ]);
    }
}