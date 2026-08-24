<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('transaksi_banks', function (Blueprint $table) {

            if (!Schema::hasColumn('transaksi_banks', 'debit')) {
                $table->decimal('debit', 15, 2)->default(0);
            }

            if (!Schema::hasColumn('transaksi_banks', 'kredit')) {
                $table->decimal('kredit', 15, 2)->default(0);
            }

            if (!Schema::hasColumn('transaksi_banks', 'saldo_akhir')) {
                $table->decimal('saldo_akhir', 15, 2)->default(0);
            }

            if (!Schema::hasColumn('transaksi_banks', 'is_saldo_awal')) {
                $table->boolean('is_saldo_awal')->default(0);
            }
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_banks', function (Blueprint $table) {
            //
        });
    }
};
