<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cabangs', function (Blueprint $table) {
            // 1. Hapus aturan unik pada kolom alamat_cabang
            // (Laravel otomatis memberi nama index 'tabel_kolom_unique')
            $table->dropUnique(['alamat_cabang']);

            $table->unique(['tenant_id', 'nama_cabang']);

            // 2. Ubah kolom menjadi nullable
            $table->string('alamat_cabang')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cabangs', function (Blueprint $table) {
            // Jika di-rollback, kembalikan seperti semula
            $table->string('alamat_cabang')->nullable(false)->unique()->change();
        });
    }
};
