<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // ✅ Cek dan tambah kolom jika belum ada
            if (!Schema::hasColumn('pembayarans', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('order_id');
            }

            // ✅ Pastikan kolom lain ada
            if (!Schema::hasColumn('pembayarans', 'plan_id')) {
                $table->unsignedBigInteger('plan_id')->nullable()->after('tenant_id');
            }

            if (!Schema::hasColumn('pembayarans', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('metode');
            }

            if (!Schema::hasColumn('pembayarans', 'tanggal_bayar')) {
                $table->timestamp('tanggal_bayar')->nullable()->after('status');
            }

            if (!Schema::hasColumn('pembayarans', 'tanggal_konfirmasi')) {
                $table->timestamp('tanggal_konfirmasi')->nullable()->after('tanggal_bayar');
            }
        });
    }

    public function down()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn([
                'transaction_id',
                'plan_id',
                'keterangan',
                'tanggal_bayar',
                'tanggal_konfirmasi',
            ]);
        });
    }
};