<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->unsignedBigInteger('produk_konter_id')->nullable()->change();
            $table->integer('qty')->nullable()->default(0)->change();
            $table->decimal('harga', 15, 2)->nullable()->default(0)->change();
            $table->decimal('harga_grosir', 15, 2)->nullable()->default(0)->change();
            $table->decimal('total_harga', 15, 2)->nullable()->default(0)->change();
            $table->string('keterangan')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->unsignedBigInteger('produk_konter_id')->nullable(false)->change();
            $table->integer('qty')->nullable(false)->change();
            $table->decimal('harga', 15, 2)->nullable(false)->change();
            $table->decimal('harga_grosir', 15, 2)->nullable(false)->change();
            $table->decimal('total_harga', 15, 2)->nullable(false)->change();
            $table->string('keterangan')->nullable(false)->change();
        });
    }
};