<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaransTable extends Migration
{
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants', 'id_tenant');
            $table->decimal('jumlah', 15, 2);
            $table->string('status')->default('pending');
            $table->string('bukti_pembayaran')->nullable();
            $table->string('metode')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamp('tanggal_konfirmasi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayarans');
    }
}