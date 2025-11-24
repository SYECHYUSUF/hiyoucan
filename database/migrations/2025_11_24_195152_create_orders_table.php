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
        // Cek dulu biar tidak error "Table already exists"
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Pembeli
                
                // WAJIB ADA untuk Multi-Vendor (Seller Dashboard)
                $table->foreignId('store_id')->constrained()->cascadeOnDelete(); 
                
                $table->string('order_number')->unique(); // No Resi/Order
                $table->decimal('total_price', 15, 2);
                
                // Status Pengiriman
                $table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'cancelled'])
                      ->default('pending');
                
                // Status Pembayaran
                $table->enum('payment_status', ['unpaid', 'paid'])
                      ->default('unpaid');
                      
                $table->text('shipping_address'); // Alamat lengkap
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};