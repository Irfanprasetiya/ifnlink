<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tambah tenant_id ke kategoris
        Schema::table('kategoris', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
        });

        // Tambah tenant_id ke vouchers (PRODUK - semua produk, bukan voucher saja)
        Schema::table('vouchers', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
        });

        // Tambah tenant_id ke produk_konter (STOK PRODUK per cabang)
        Schema::table('produk_konter', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('kategoris', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });

        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });

        Schema::table('produk_konter', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });
    }
};