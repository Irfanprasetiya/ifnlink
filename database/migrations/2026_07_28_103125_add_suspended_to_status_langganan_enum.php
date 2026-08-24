<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE tenants MODIFY COLUMN status_langganan ENUM('trial', 'active', 'pending', 'expired', 'suspended') NOT NULL DEFAULT 'trial'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tenants MODIFY COLUMN status_langganan ENUM('trial', 'active', 'pending', 'expired') NOT NULL DEFAULT 'trial'");
    }
};