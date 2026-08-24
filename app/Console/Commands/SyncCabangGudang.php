<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\Cabang;

class SyncCabangGudang extends Command
{
    protected $signature = 'cabang:sync-gudang';
    protected $description = 'Buat cabang Gudang untuk tenant yang belum punya';

    public function handle()
    {
        $tenants = Tenant::all();
        $created = 0;

        foreach ($tenants as $tenant) {
            $exists = Cabang::where('tenant_id', $tenant->id)
                ->where('nama_cabang', 'Gudang')
                ->exists();

            if (!$exists) {
                Cabang::create([
                    'tenant_id' => $tenant->id,
                    'nama_cabang' => 'Gudang',
                    'alamat_cabang' => '-',
                    'keterangan' => 'Saldo Pusat',
                ]);
                $created++;
                $this->info("✅ Gudang dibuat untuk: {$tenant->nama_toko}");
            }
        }

        $this->info("📊 {$created} cabang Gudang baru dibuat");
    }
}