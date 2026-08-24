<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::rename('pengeluaran', 'pengeluaran_kas');
    }

    public function down()
    {
        Schema::rename('pengeluaran_kas', 'pengeluaran');
    }
};

