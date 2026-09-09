<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('penjualan_details', function (Blueprint $table) {
            if (!Schema::hasColumn('penjualan_details', 'diskon')) {
                $table->decimal('diskon', 15, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('penjualan_details', 'total_setelah_diskon')) {
                $table->decimal('total_setelah_diskon', 15, 2)->default(0)->after('diskon');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penjualan_details', function (Blueprint $table) {
            $table->dropColumn(['diskon', 'total_setelah_diskon']);
        });
    }
};