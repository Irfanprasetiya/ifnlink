<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Cabang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks
        Schema::disableForeignKeyConstraints();

        // ==========================================
        // 1. USER DEVELOPER (TETAP - Jangan dihapus!)
        // ==========================================
        User::firstOrCreate(
            ['username' => 'prasetiya'],
            [
                'name' => 'M Irfan Hadi (Dev)',
                'password' => Hash::make('12345678'),
                'role' => 'developer',
                'tenant_id' => null,
                'cabang_id' => null,
                'remember_token' => null,
            ]
        );
        $this->command->info("✅ Developer: prasetiya");

        // ==========================================
        // 2. TAMBAH CABANG GUDANG UNTUK SEMUA TENANT
        // ==========================================
        // $tenants = Tenant::all();

        // if ($tenants->isEmpty()) {
        //     $this->command->warn('⚠️  Tidak ada tenant. Cabang Gudang tidak dibuat.');
        // } else {
        //     $created = 0;

        //     foreach ($tenants as $tenant) {
        //         // Cek manual tanpa firstOrCreate (hindari kolom yang tidak ada)
        //         $exists = DB::table('cabangs')
        //             ->where('tenant_id', $tenant->id)
        //             ->where('nama_cabang', 'Gudang')
        //             ->exists();

        //         if (!$exists) {
        //             // Insert HANYA kolom yang ada di tabel
        //             DB::table('cabangs')->insert([
        //                 'tenant_id' => $tenant->id,
        //                 'nama_cabang' => 'Gudang',
        //                 'alamat_cabang' => '-',
        //                 'keterangan' => 'Saldo Pusat',
        //                 'created_at' => now(),
        //                 'updated_at' => now(),
        //             ]);

        //             $created++;
        //             $this->command->info("✅ Cabang Gudang dibuat untuk: {$tenant->nama_toko}");
        //         } else {
        //             $this->command->line("⏭️  Sudah ada: {$tenant->nama_toko}");
        //         }
        //     }

        //     $this->command->info("📊 {$created} cabang Gudang baru dibuat");
        // }

        // ==========================================
        // 3. AKUN PENGELUARAN (DATA MASTER)
        // ==========================================
        // $pengeluaranMaster = ['PDAM', 'Listrik', 'Internet'];

        // foreach ($pengeluaranMaster as $nama) {
        //     $exists = DB::table('akun_pengeluarans')
        //         ->whereNull('tenant_id')
        //         ->where('nama_akun', $nama)
        //         ->exists();

        //     if (!$exists) {
        //         DB::table('akun_pengeluarans')->insert([
        //             'tenant_id' => null,
        //             'nama_akun' => $nama,
        //             'keterangan' => 'Pengeluaran ' . $nama,
        //             'datetime' => now(), // ✅ Tambahin ini!
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //         $this->command->info("✅ Akun Pengeluaran: {$nama}");
        //     } else {
        //         $this->command->line("⏭️  Akun Pengeluaran {$nama} sudah ada");
        //     }
        // }


        // Aktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();

        $this->command->newLine();
        $this->command->info('✅ Seeder selesai!');
    }
}