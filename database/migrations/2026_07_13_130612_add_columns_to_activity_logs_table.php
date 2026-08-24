<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToActivityLogsTable extends Migration
{
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_logs', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('activity_logs', 'aksi')) {
                $table->string('aksi')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('activity_logs', 'modul')) {
                $table->string('modul')->nullable()->after('aksi');
            }
            if (!Schema::hasColumn('activity_logs', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('modul');
            }
            if (!Schema::hasColumn('activity_logs', 'data_lama')) {
                $table->json('data_lama')->nullable()->after('deskripsi');
            }
            if (!Schema::hasColumn('activity_logs', 'data_baru')) {
                $table->json('data_baru')->nullable()->after('data_lama');
            }
            if (!Schema::hasColumn('activity_logs', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('data_baru');
            }
            if (!Schema::hasColumn('activity_logs', 'user_agent')) {
                $table->string('user_agent')->nullable()->after('ip_address');
            }
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'aksi',
                'modul',
                'deskripsi',
                'data_lama',
                'data_baru',
                'ip_address',
                'user_agent',
            ]);
        });
    }
}