<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('produk_konter', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel vouchers
            $table->foreignId('voucher_id')->constrained('vouchers')->onDelete('cascade');

            // Relasi ke tabel cabangs
            $table->foreignId('cabang_id')->constrained('cabangs')->onDelete('cascade');

            // Kolom data
            $table->integer('stok')->default(0);
            $table->string('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_konter');
    }
};
