<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // ✅ Skip kalau tabel sudah ada (CI/CD friendly)
        if (Schema::hasTable('harga_cabang')) {
            return;
        }

        Schema::create('harga_cabang', function (Blueprint $table) {
            $table->id();

            // FK columns
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('cabang_id');
            $table->unsignedBigInteger('voucher_id');

            $table->decimal('harga_jual', 15, 2);
            $table->decimal('harga_beli', 15, 2)->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_berakhir')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // ✅ FK eksplisit sesuai PK masing-masing tabel
            $table->foreign('tenant_id')
                ->references('id_tenant')      // ← PK tenants
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreign('cabang_id')
                ->references('id')              // ← PK cabangs
                ->on('cabangs')
                ->onDelete('cascade');

            $table->foreign('voucher_id')
                ->references('id')              // ← PK vouchers
                ->on('vouchers')
                ->onDelete('cascade');

            // Index untuk performa query
            $table->unique(['cabang_id', 'voucher_id', 'tanggal_mulai'], 'unique_harga_cabang');
            $table->index(['cabang_id', 'voucher_id', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('harga_cabang');
    }
};