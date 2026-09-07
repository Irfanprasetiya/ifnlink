<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('cabang_id')->nullable()->after('tenant_id');
            $table->decimal('bayar', 15, 2)->default(0)->after('total_harga');
            $table->decimal('kembalian', 15, 2)->default(0)->after('bayar');
            $table->enum('status', ['lunas', 'hutang'])->default('lunas')->after('kembalian');
        });
    }

    public function down(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropColumn(['tenant_id', 'cabang_id', 'bayar', 'kembalian', 'status']);
        });
    }
};