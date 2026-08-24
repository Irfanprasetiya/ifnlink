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
        if (Schema::hasColumn('transaksi_banks', 'trx_code')) {
            Schema::table('transaksi_banks', function (Blueprint $table) {
                $table->dropColumn('trx_code');
            });
        }
    }

    public function down()
    {
        Schema::table('transaksi_banks', function (Blueprint $table) {
            $table->string('trx_code')->nullable();
        });
    }

};
