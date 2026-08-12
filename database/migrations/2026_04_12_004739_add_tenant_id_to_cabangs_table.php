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
        Schema::table('cabangs', function (Blueprint $table) {
            // Tambahkan kolom tenant_id (pastikan tipe datanya sama dengan id_tenant di tabel tenants)
            $table->unsignedBigInteger('tenant_id')->nullable()->after('id');

            // Tambahkan Foreign Key agar data konsisten
            $table->foreign('tenant_id')->references('id_tenant')->on('tenants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('cabangs', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });
    }
};
