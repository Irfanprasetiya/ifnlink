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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan tenant_id. Kita buat nullable agar role 'developer' (kamu) tidak wajib punya tenant_id.
            $table->unsignedBigInteger('tenant_id')->nullable()->after('id');

            // Relasi ke tabel tenants
            $table->foreign('tenant_id')->references('id_tenant')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
