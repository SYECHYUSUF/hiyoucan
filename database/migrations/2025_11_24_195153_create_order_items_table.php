<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cek apakah tabel sudah ada sebelum membuatnya
        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                
                // Relasi ke tabel Orders (Cascade: Hapus order -> hapus itemnya juga)
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                
                // Relasi ke tabel Products
                $table->foreignId('product_id')->constrained();
                
                $table->integer('quantity');
                $table->decimal('price', 12, 2); // Harga saat dibeli (snapshot harga)
                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};