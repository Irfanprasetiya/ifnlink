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
            $table->boolean('is_saldo_awal')->default(false)->after('waktu_transaksi');
        });
    }

    public function down()
    {
        Schema::table('transaksi_banks', function (Blueprint $table) {
            $table->dropColumn('is_saldo_awal');
        });
    }

};
