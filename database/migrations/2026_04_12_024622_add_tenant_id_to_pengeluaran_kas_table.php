<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengeluaran_kas', function (Blueprint $table) {
            // Hanya buat kolom jika belum ada
            if (!Schema::hasColumn('pengeluaran_kas', 'tenant_id')) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
            }

            // Tambahkan Foreign Key
            $table->foreign('tenant_id')->references('id_tenant')->on('tenants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('pengeluaran_kas', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });
    }
};
