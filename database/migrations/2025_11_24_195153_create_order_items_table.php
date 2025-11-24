<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Dalam file migrasi OrderItem:

public function up(): void
{
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Hapus item jika Order dihapus
        
        // Product ID: Gunakan onDelete('set null') untuk menghindari Error 1451
        $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null'); 
        
        $table->string('product_name'); // Simpan nama dan harga saat checkout (snapshot)
        $table->decimal('price', 10, 2);
        $table->integer('quantity');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
