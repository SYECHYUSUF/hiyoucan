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
     Schema::create('stores', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->unique(); // Relasi 1-ke-1 dengan User (Seller)
        $table->string('name')->unique();
        $table->text('description')->nullable();
        $table->string('logo_path')->nullable(); // Untuk gambar toko
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
