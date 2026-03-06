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
            $table->decimal('debit', 15, 2)->default(0)->after('jenis_transaksi_id');
            $table->decimal('kredit', 15, 2)->default(0)->after('debit');
        });
    }

    public function down()
    {
        Schema::table('transaksi_banks', function (Blueprint $table) {
            $table->dropColumn(['debit', 'kredit']);
        });
    }

};
