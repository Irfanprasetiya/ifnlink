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
        Schema::table('akun_pengeluarans', function (Blueprint $table) {
            $table->unique('nama_akun');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('akun_pengeluarans', function (Blueprint $table) {
            $table->unique('nama_akun');
        });

    }
};
