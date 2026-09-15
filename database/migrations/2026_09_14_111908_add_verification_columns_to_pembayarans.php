<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Kolom verifikasi admin (nullable — aman untuk Midtrans)
            if (!Schema::hasColumn('pembayarans', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('tanggal_konfirmasi');
                $table->foreign('verified_by')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null');
            }

            if (!Schema::hasColumn('pembayarans', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verified_by');
            }

            if (!Schema::hasColumn('pembayarans', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable()->after('verified_at');
            }

            // Index untuk query QRIS manual
            $table->index(['metode', 'status'], 'idx_pembayarans_metode_status');
        });
    }

    public function down()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropIndex('idx_pembayarans_metode_status');
            $table->dropColumn(['verified_by', 'verified_at', 'catatan_admin']);
        });
    }
};