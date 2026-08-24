<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataDefaultSeeder extends Seeder
{
    public function run()
    {
        // ==========================================
        // 1. BANK KAS (DATA MASTER)
        // ==========================================
        $exists = DB::table('banks')
            ->whereNull('tenant_id')
            ->where('nama_bank', 'Kas')
            ->exists();

        if (!$exists) {
            DB::table('banks')->insert([
                'tenant_id' => null,
                'nama_bank' => 'Kas',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('✅ Bank: Kas');
        } else {
            $this->command->line('⏭️  Bank Kas sudah ada');
        }

        // ==========================================
        // 2. AKUN PENGELUARAN (DATA MASTER)
        // ==========================================
        $pengeluaranMaster = [
            ['PDAM', 'Pengeluaran PDAM'],
            ['Listrik', 'Pengeluaran Listrik'],
            ['Internet', 'Pengeluaran Internet'],
            ['Oper Saldo', 'Transfer ke cabang lain'],
            ['Sampah', '-'],
            ['Di tarik', '-'],
            ['Sumbangan', '-'],
        ];

        foreach ($pengeluaranMaster as $data) {
            $exists = DB::table('akun_pengeluarans')
                ->whereNull('tenant_id')
                ->where('nama_akun', $data[0])
                ->exists();

            if (!$exists) {
                DB::table('akun_pengeluarans')->insert([
                    'tenant_id' => null,
                    'nama_akun' => $data[0],
                    'keterangan' => $data[1],
                    'datetime' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info("✅ Akun Pengeluaran: {$data[0]}");
            } else {
                $this->command->line("⏭️  Akun Pengeluaran {$data[0]} sudah ada");
            }
        }

        // ==========================================
        // 3. CABANG GUDANG UNTUK SEMUA TENANT
        // ==========================================
        $tenants = DB::table('tenants')->get();

        if ($tenants->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada tenant. Cabang Gudang tidak dibuat.');
        } else {
            $created = 0;

            foreach ($tenants as $tenant) {
                $exists = DB::table('cabangs')
                    ->where('tenant_id', $tenant->id_tenant)
                    ->where('nama_cabang', 'Gudang')
                    ->exists();

                if (!$exists) {
                    DB::table('cabangs')->insert([
                        'tenant_id' => $tenant->id_tenant,
                        'nama_cabang' => 'Gudang',
                        'alamat_cabang' => '-',
                        'keterangan' => 'Saldo Pusat',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $created++;
                    $this->command->info("✅ Cabang Gudang dibuat untuk: {$tenant->nama_toko}");
                } else {
                    $this->command->line("⏭️  Sudah ada: {$tenant->nama_toko}");
                }
            }

            $this->command->info("📊 {$created} cabang Gudang baru dibuat");
        }

        // ==========================================
        // 4. BANK KAS UNTUK SEMUA TENANT
        // ==========================================
        $tenants = DB::table('tenants')->get();

        if ($tenants->isNotEmpty()) {
            $created = 0;

            foreach ($tenants as $tenant) {
                $exists = DB::table('banks')
                    ->where('tenant_id', $tenant->id_tenant)
                    ->where('nama_bank', 'Kas')
                    ->exists();

                if (!$exists) {
                    DB::table('banks')->insert([
                        'tenant_id' => $tenant->id_tenant,
                        'nama_bank' => 'Kas',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $created++;
                    $this->command->info("✅ Bank Kas dibuat untuk: {$tenant->nama_toko}");
                } else {
                    $this->command->line("⏭️  Bank Kas sudah ada untuk: {$tenant->nama_toko}");
                }
            }

            $this->command->info("📊 {$created} Bank Kas baru dibuat");
        }
    }
}
