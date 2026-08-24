<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksi_banks', function (Blueprint $table) {
            // Tambahkan kolom cabang_id. Dibuat nullable dulu agar data lama tidak error
            $table->foreignId('cabang_id')->nullable()->constrained('cabangs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_banks', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
            $table->dropColumn('cabang_id');
        });
    }
};
