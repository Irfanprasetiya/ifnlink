<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalBerakhirToTenantsTable extends Migration
{
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'tanggal_berakhir')) {
                $table->timestamp('tanggal_berakhir')->nullable()->after('status_langganan');
            }
            if (!Schema::hasColumn('tenants', 'max_user')) {
                $table->integer('max_user')->default(1)->after('tanggal_berakhir');
            }
            if (!Schema::hasColumn('tenants', 'plan_id')) {
                $table->foreignId('plan_id')->nullable()->after('max_user')->constrained('plans');
            }
            if (!Schema::hasColumn('tenants', 'status_data')) {
                $table->string('status_data')->default('active')->after('plan_id');
            }
            if (!Schema::hasColumn('tenants', 'churned_at')) {
                $table->timestamp('churned_at')->nullable()->after('status_data');
            }
            if (!Schema::hasColumn('tenants', 'churn_reason')) {
                $table->string('churn_reason')->nullable()->after('churned_at');
            }
            if (!Schema::hasColumn('tenants', 'deleted_by')) {
                $table->string('deleted_by')->nullable()->after('churn_reason');
            }
            if (!Schema::hasColumn('tenants', 'delete_reason')) {
                $table->text('delete_reason')->nullable()->after('deleted_by');
            }
            if (!Schema::hasColumn('tenants', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_berakhir',
                'max_user',
                'plan_id',
                'status_data',
                'churned_at',
                'churn_reason',
                'deleted_by',
                'delete_reason',
                'deleted_at',
            ]);
        });
    }
}