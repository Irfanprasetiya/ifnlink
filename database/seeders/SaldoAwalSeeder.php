<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiBank;
use App\Models\User;
use App\Models\JenisTransaksi;
use Carbon\Carbon;

class SaldoAwalSeeder extends Seeder
{
    public function run()
    {
        $tenantId = 3;

        // ✅ Data User
        $users = [
            ['id' => 9, 'name' => 'Asep', 'cabang_id' => 50],
            ['id' => 10, 'name' => 'Supendi', 'cabang_id' => 49],
            ['id' => 11, 'name' => 'Wartam', 'cabang_id' => 48],
        ];

        // ✅ Bank IDs
        $banks = [
            7 => 'Kas',
            44 => 'Bank BRI',
            45 => 'Bank BCA',
            46 => 'Dana',
        ];

        // ✅ Jenis Transaksi: Saldo Awal
        $jenisSaldoAwal = JenisTransaksi::where('id', 13)
            ->orWhere('nama_transaksi', 'Saldo Awal')
            ->first();

        if (!$jenisSaldoAwal) {
            $this->command->error('Jenis transaksi "Saldo Awal" tidak ditemukan.');
            return;
        }

        $this->command->info('Jenis Transaksi: ' . $jenisSaldoAwal->nama_transaksi . ' (ID: ' . $jenisSaldoAwal->id . ')');

        // ✅ Nominal saldo awal per bank
        $saldoByBank = [
            7 => 10000000,  // Kas: Rp 10.000.000
            44 => 5000000,  // BRI: Rp 5.000.000
            45 => 3000000,  // BCA: Rp 3.000.000
            46 => 2000000,  // Dana: Rp 2.000.000
        ];

        $totalSaldoAwal = 0;

        // ==========================================
        // ✅ SALDO AWAL
        // Dari 15 Agustus 2026 mundur 30 hari
        // ==========================================
        for ($i = 0; $i < 30; $i++) {
            $tanggal = Carbon::create(2026, 8, 15)->subDays($i);
            $waktuSaldo = $tanggal->copy()->setTime(8, 0, 0);
            $hari = $tanggal->format('d M Y');

            $this->command->info("Saldo Awal: {$hari}");

            foreach ($users as $userData) {
                $user = User::find($userData['id']);

                if (!$user) {
                    $this->command->error("User ID {$userData['id']} tidak ditemukan.");
                    continue;
                }

                foreach ($saldoByBank as $bankId => $saldo) {
                    $namaBank = $banks[$bankId] ?? 'Bank';

                    TransaksiBank::create([
                        'user_id' => $userData['id'],
                        'bank_id' => $bankId,
                        'jenis_transaksi_id' => $jenisSaldoAwal->id,
                        'akun_pengeluaran_id' => null,
                        'kredit' => 0.00,
                        'no_tujuan' => null,
                        'waktu_transaksi' => $waktuSaldo,
                        'nominal' => $saldo,
                        'bayar' => $saldo,
                        'keterangan' => 'Saldo Awal',
                        'is_saldo_awal' => 1,
                        'created_at' => $waktuSaldo,
                        'updated_at' => $waktuSaldo,
                        'saldo_akhir' => $saldo,
                        'debit' => $saldo,
                        'cabang_id' => $userData['cabang_id'],
                        'tenant_id' => $tenantId,
                    ]);

                    $totalSaldoAwal++;
                }
            }
        }

        $this->command->newLine();
        $this->command->info('═══════════════════════════════════');
        $this->command->info('  ✅ SALDO AWAL BERHASIL DIBUAT');
        $this->command->info('═══════════════════════════════════');
        $this->command->info('Periode: 15 Agustus - 30 hari ke belakang');
        $this->command->info('Total Saldo Awal: ' . $totalSaldoAwal . ' transaksi');
        $this->command->info('═══════════════════════════════════');
    }
}