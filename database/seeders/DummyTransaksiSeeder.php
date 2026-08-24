<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiBank;
use App\Models\JenisTransaksi;
use Carbon\Carbon;

class DummyTransaksiSeeder extends Seeder
{
    public function run()
    {
        $tenantId = 3;

        // ✅ Jenis Transaksi
        $jenisTransfer = JenisTransaksi::where('id', 2)
            ->orWhere('nama_transaksi', 'Transfer')
            ->first();

        $jenisTarikTunai = JenisTransaksi::where('id', 1)
            ->orWhere('nama_transaksi', 'Tarik Tunai')
            ->first();

        if (!$jenisTransfer || !$jenisTarikTunai) {
            $this->command->error('Jenis transaksi tidak ditemukan.');
            return;
        }

        $this->command->info('Jenis Transfer: ' . $jenisTransfer->nama_transaksi . ' (ID: ' . $jenisTransfer->id . ')');
        $this->command->info('Jenis Tarik Tunai: ' . $jenisTarikTunai->nama_transaksi . ' (ID: ' . $jenisTarikTunai->id . ')');

        // ✅ Data User
        $users = [
            ['id' => 9, 'name' => 'Asep', 'cabang_id' => 50],
            ['id' => 10, 'name' => 'Supendi', 'cabang_id' => 49],
            ['id' => 11, 'name' => 'Wartam', 'cabang_id' => 48],
        ];

        // ✅ Bank selain Kas
        $bankIds = [44, 45, 46]; // BRI, BCA, Dana

        $totalTransaksi = 0;

        // ==========================================
        // ✅ TRANSAKSI 15 HARI TERAKHIR
        // Jam 6-7 pagi
        // ==========================================
        for ($i = 0; $i < 15; $i++) {
            $tanggal = Carbon::today()->subDays($i);
            $hari = $tanggal->format('d M Y');

            $this->command->info("Transaksi: {$hari}");

            foreach ($users as $userData) {
                // 2-3 transaksi per user per hari
                $jmlTransaksi = rand(2, 3);

                for ($j = 0; $j < $jmlTransaksi; $j++) {
                    // Pilih bank acak
                    $bankId = $bankIds[array_rand($bankIds)];

                    // ✅ Jam 6-7 pagi (06:00 - 07:59)
                    $jam = rand(6, 7);
                    $menit = rand(0, 59);
                    $waktu = $tanggal->copy()->setTime($jam, $menit, 0);

                    $rand = rand(1, 10);

                    if ($rand <= 6) {
                        // ========== TRANSFER (60%) ==========
                        $nominal = [50000, 100000, 200000, 500000][rand(0, 3)];
                        $admin = [5000, 7000, 10000][rand(0, 2)];
                        $bayar = $nominal + $admin;

                        // Transaksi Bank
                        TransaksiBank::create([
                            'user_id' => $userData['id'],
                            'bank_id' => $bankId,
                            'jenis_transaksi_id' => $jenisTransfer->id,
                            'akun_pengeluaran_id' => null,
                            'kredit' => $nominal,
                            'no_tujuan' => null,
                            'waktu_transaksi' => $waktu,
                            'nominal' => $nominal,
                            'bayar' => $bayar,
                            'keterangan' => 'Transfer',
                            'is_saldo_awal' => 0,
                            'created_at' => $waktu,
                            'updated_at' => $waktu,
                            'saldo_akhir' => 0.00,
                            'debit' => 0.00,
                            'cabang_id' => $userData['cabang_id'],
                            'tenant_id' => $tenantId,
                        ]);

                        // Pasangan Kas
                        TransaksiBank::create([
                            'user_id' => $userData['id'],
                            'bank_id' => 7, // Kas
                            'jenis_transaksi_id' => $jenisTransfer->id,
                            'akun_pengeluaran_id' => null,
                            'kredit' => 0.00,
                            'no_tujuan' => null,
                            'waktu_transaksi' => $waktu,
                            'nominal' => $nominal,
                            'bayar' => $bayar,
                            'keterangan' => 'Transfer',
                            'is_saldo_awal' => 0,
                            'created_at' => $waktu,
                            'updated_at' => $waktu,
                            'saldo_akhir' => 0.00,
                            'debit' => $bayar,
                            'cabang_id' => $userData['cabang_id'],
                            'tenant_id' => $tenantId,
                        ]);

                        $totalTransaksi += 2;

                    } else {
                        // ========== TARIK TUNAI (40%) ==========
                        $bayar = [50000, 100000, 200000][rand(0, 2)];
                        $admin = [5000, 7000, 10000][rand(0, 2)];
                        $nominal = $bayar + $admin;

                        // Transaksi Bank
                        TransaksiBank::create([
                            'user_id' => $userData['id'],
                            'bank_id' => $bankId,
                            'jenis_transaksi_id' => $jenisTarikTunai->id,
                            'akun_pengeluaran_id' => null,
                            'kredit' => 0.00,
                            'no_tujuan' => null,
                            'waktu_transaksi' => $waktu,
                            'nominal' => $nominal,
                            'bayar' => $bayar,
                            'keterangan' => 'Tarik Tunai',
                            'is_saldo_awal' => 0,
                            'created_at' => $waktu,
                            'updated_at' => $waktu,
                            'saldo_akhir' => 0.00,
                            'debit' => $nominal,
                            'cabang_id' => $userData['cabang_id'],
                            'tenant_id' => $tenantId,
                        ]);

                        // Pasangan Kas
                        TransaksiBank::create([
                            'user_id' => $userData['id'],
                            'bank_id' => 7, // Kas
                            'jenis_transaksi_id' => $jenisTarikTunai->id,
                            'akun_pengeluaran_id' => null,
                            'kredit' => $bayar,
                            'no_tujuan' => null,
                            'waktu_transaksi' => $waktu,
                            'nominal' => $nominal,
                            'bayar' => $bayar,
                            'keterangan' => 'Tarik Tunai',
                            'is_saldo_awal' => 0,
                            'created_at' => $waktu,
                            'updated_at' => $waktu,
                            'saldo_akhir' => 0.00,
                            'debit' => 0.00,
                            'cabang_id' => $userData['cabang_id'],
                            'tenant_id' => $tenantId,
                        ]);

                        $totalTransaksi += 2;
                    }
                }
            }
        }

        $this->command->newLine();
        $this->command->info('═══════════════════════════════════');
        $this->command->info('  ✅ TRANSAKSI BERHASIL DIBUAT');
        $this->command->info('═══════════════════════════════════');
        $this->command->info('Jenis: Transfer & Tarik Tunai');
        $this->command->info('Periode: 15 hari terakhir');
        $this->command->info('Waktu: 06:00 - 07:59');
        $this->command->info('Total Transaksi: ' . $totalTransaksi);
        $this->command->info('═══════════════════════════════════');
    }
}