<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('vouchers', 'kategori_id')) {
                $table->unsignedBigInteger('kategori_id')->after('id');
                $table->foreign('kategori_id', 'vouchers_kategori_id_foreign')
                    ->references('id')->on('kategoris')
                    ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            if (Schema::hasColumn('vouchers', 'kategori_id')) {
                $table->dropForeign('vouchers_kategori_id_foreign');
                $table->dropColumn('kategori_id');
            }
        });
    }
};
