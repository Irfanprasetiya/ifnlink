<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiBank;
use Illuminate\Support\Facades\DB;

class BersihkanSaldoAwalSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Membersihkan saldo awal duplikat...');

        // ✅ Cari duplikat
        $duplicates = TransaksiBank::where('is_saldo_awal', 1)
            ->select('user_id', 'bank_id', 'waktu_transaksi', DB::raw('MIN(id) as keep_id'), DB::raw('count(*) as total'))
            ->groupBy('user_id', 'bank_id', 'waktu_transaksi')
            ->having('total', '>', 1)
            ->get();

        $deleted = 0;

        foreach ($duplicates as $duplicate) {
            // Hapus semua kecuali yang keep_id
            $hapus = TransaksiBank::where('is_saldo_awal', 1)
                ->where('user_id', $duplicate->user_id)
                ->where('bank_id', $duplicate->bank_id)
                ->where('waktu_transaksi', $duplicate->waktu_transaksi)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();

            $deleted += $hapus;
        }

        $this->command->info("✅ Berhasil menghapus {$deleted} saldo awal duplikat.");

        // Tampilkan sisa saldo awal
        $sisa = TransaksiBank::where('is_saldo_awal', 1)->count();
        $this->command->info("Sisa saldo awal: {$sisa} transaksi.");
    }
}