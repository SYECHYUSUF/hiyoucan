<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role: Admin, Seller, Buyer
            $table->enum('role', ['admin', 'seller', 'buyer'])->default('buyer')->after('email');
            // Status Seller: Pending (nunggu verifikasi), Approved, Rejected
            $table->enum('seller_status', ['pending', 'approved', 'rejected'])->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'seller_status']);
        });
    }
};