<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('transaksi_banks', function (Blueprint $table) {
            $table->dropColumn([
                'saldo_akhir',
                'is_saldo_awal'
            ]);
        });
    }

    public function down()
    {
        Schema::table('transaksi_banks', function (Blueprint $table) {
            $table->integer('saldo_akhir')->nullable();
            $table->boolean('is_saldo_awal')->default(false);
        });
    }
};
