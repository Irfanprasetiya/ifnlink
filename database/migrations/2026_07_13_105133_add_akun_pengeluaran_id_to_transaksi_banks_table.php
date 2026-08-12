<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('transaksi_banks', 'akun_pengeluaran_id')) {
            Schema::table('transaksi_banks', function (Blueprint $table) {
                $table->foreignId('akun_pengeluaran_id')->nullable()->after('jenis_transaksi_id')
                    ->constrained('akun_pengeluarans')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('transaksi_banks', function (Blueprint $table) {
            $table->dropForeign(['akun_pengeluaran_id']);
            $table->dropColumn('akun_pengeluaran_id');
        });
    }
};