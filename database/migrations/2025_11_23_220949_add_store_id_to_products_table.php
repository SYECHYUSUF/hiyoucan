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
        Schema::table('products', function (Blueprint $table) {
            // Menambahkan kolom store_id setelah user_id
            // Kita buat nullable dulu untuk jaga-jaga jika ada data lama
            $table->foreignId('store_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('stores')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Hapus foreign key dan kolom jika di-rollback
            $table->dropForeign(['store_id']);
            $table->dropColumn('store_id');
        });
    }
};