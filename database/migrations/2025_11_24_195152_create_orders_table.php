<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Dalam file migrasi Order:

public function up(): void
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained(); // Buyer yang membuat pesanan
        $table->decimal('total_price', 15, 2);
        $table->string('status')->default('pending'); // pending, processing, shipped, completed, cancelled
        $table->text('shipping_address'); // Jika Anda mengimplementasikan alamat terpisah, ini bisa menjadi foreignId('address_id')
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
