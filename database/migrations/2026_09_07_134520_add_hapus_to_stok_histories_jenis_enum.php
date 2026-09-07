<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Ubah enum jenis di stok_histories
        DB::statement("ALTER TABLE stok_histories MODIFY COLUMN jenis ENUM('masuk', 'keluar', 'opname', 'retur', 'hapus') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE stok_histories MODIFY COLUMN jenis ENUM('masuk', 'keluar', 'opname', 'retur') NOT NULL");
    }
};