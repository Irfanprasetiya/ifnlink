<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateStatusLanggananEnum extends Migration
{
    public function up()
    {
        // Ubah ENUM: tambahkan 'inactive', 'pending', 'deleted'
        DB::statement("ALTER TABLE tenants MODIFY COLUMN status_langganan ENUM('trial','active','expired','inactive','pending','deleted') NOT NULL DEFAULT 'trial'");
    }

    public function down()
    {
        // Kembalikan ke semula
        DB::statement("ALTER TABLE tenants MODIFY COLUMN status_langganan ENUM('trial','active','expired') NOT NULL DEFAULT 'trial'");
    }
}