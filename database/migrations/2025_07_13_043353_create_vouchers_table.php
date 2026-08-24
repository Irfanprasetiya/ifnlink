<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi: membuat tabel vouchers.
     */
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');      // Nama produk voucher
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('harga_jual', 15, 2);  // Harga jual voucher
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
            $table->string('keterangan');       // Keterangan tambahan
            $table->timestamps();               // created_at dan updated_at
        });
    }

    /**
     * Hapus tabel vouchers jika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
