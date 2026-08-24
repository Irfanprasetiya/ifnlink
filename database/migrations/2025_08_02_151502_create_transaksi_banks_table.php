<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi_banks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('bank_id')->constrained()->onDelete('cascade');
            $table->foreignId('jenis_transaksi_id')->constrained('jenis_transaksis')->onDelete('cascade');

            $table->dateTime('waktu_transaksi');
            $table->bigInteger('nominal');
            $table->bigInteger('bayar')->nullable();
            $table->bigInteger('saldo_akhir');
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_banks');
    }
};
