<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabang;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CabangGudangSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah tabel cabangs ada
        if (!Schema::hasTable('cabangs')) {
            $this->command->error('❌ Tabel "cabangs" tidak ditemukan!');
            return;
        }

        // Ambil semua tenant
        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada tenant ditemukan. Jalankan TenantSeeder terlebih dahulu.');

            // Buat tenant dummy kalau tidak ada
            $this->command->info('Membuat tenant dummy...');
            $tenant = Tenant::create([
                'nama_toko' => 'Toko Default',
                'alamat' => 'Alamat Default',
                'telepon' => '081234567890',
                'status' => 'aktif',
            ]);
            $tenants = collect([$tenant]);
        }

        $created = 0;
        $skipped = 0;

        foreach ($tenants as $tenant) {
            // Cek apakah tenant sudah punya cabang dengan nama "Gudang"
            $exists = Cabang::where('tenant_id', $tenant->id)
                ->where('nama_cabang', 'Gudang')
                ->exists();

            if (!$exists) {
                try {
                    DB::beginTransaction();

                    // Cek apakah kolom is_default ada
                    $data = [
                        'tenant_id' => $tenant->id,
                        'nama_cabang' => 'Gudang',
                        'alamat_cabang' => '-',
                        'keterangan' => 'Saldo Pusat',
                    ];

                    // Tambah is_default kalau kolomnya ada
                    if (Schema::hasColumn('cabangs', 'is_default')) {
                        $data['is_default'] = true;
                    }

                    // Tambah status kalau kolomnya ada
                    if (Schema::hasColumn('cabangs', 'status')) {
                        $data['status'] = 'aktif';
                    }

                    // Tambah telepon kalau kolomnya ada
                    if (Schema::hasColumn('cabangs', 'telepon')) {
                        $data['telepon'] = '-';
                    }

                    Cabang::create($data);

                    DB::commit();
                    $created++;

                    $this->command->info("✅ Cabang Gudang dibuat untuk tenant: {$tenant->nama_toko} (ID: {$tenant->id})");

                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->command->error("❌ Gagal membuat untuk tenant {$tenant->id}: " . $e->getMessage());
                }
            } else {
                $skipped++;
                $this->command->line("⏭️  Tenant {$tenant->nama_toko} (ID: {$tenant->id}) sudah punya cabang Gudang");
            }
        }

        $this->command->newLine();
        $this->command->info("📊 Hasil: {$created} dibuat, {$skipped} dilewati");

        // Tampilkan semua cabang Gudang
        $gudangs = Cabang::where('nama_cabang', 'Gudang')->get();
        if ($gudangs->isNotEmpty()) {
            $this->command->newLine();
            $this->command->info('📋 Daftar Cabang Gudang:');

            $headers = ['ID', 'Tenant ID', 'Nama Cabang', 'Alamat', 'Keterangan'];
            $rows = $gudangs->map(function ($g) {
                return [
                    $g->id,
                    $g->tenant_id,
                    $g->nama_cabang,
                    $g->alamat_cabang,
                    $g->keterangan,
                ];
            })->toArray();

            $this->command->table($headers, $rows);
        }
    }
}