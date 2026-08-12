<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiBank;
use App\Models\Bank;
use App\Models\User;
use App\Models\JenisTransaksi;
use App\Models\AkunPengeluaran;
use Carbon\Carbon;

class DummyTransaksiSeeder extends Seeder
{
    public function run()
    {
        $tenantId = 53;
        $kasId = Bank::where('nama_bank', 'Kas')->first()?->id;

        // Jenis transaksi
        $jenisTransfer = JenisTransaksi::where('nama_transaksi', 'Transfer')->first();
        $jenisTarikTunai = JenisTransaksi::where('nama_transaksi', 'Tarik Tunai')->first();
        $jenisNumpang = JenisTransaksi::where('nama_transaksi', 'Numpang Transfer')->first();
        $jenisPenambahan = JenisTransaksi::where('nama_transaksi', 'Penambahan Kas')->first();
        $jenisPengurangan = JenisTransaksi::where('nama_transaksi', 'Pengurangan Kas')->first();

        // Bank selain Kas
        $banks = Bank::where('id', '!=', $kasId)
            ->where(function ($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId)->orWhereNull('tenant_id');
            })->get();

        // Akun pengeluaran yang tersedia (ID: 15, 16, 17, 18)
        $akunIds = AkunPengeluaran::pluck('id')->toArray();
        $akunNama = AkunPengeluaran::pluck('nama_akun')->toArray();

        // Users
        $users = User::whereIn('id', [126, 129])->get();

        // ==========================================
        // SALDO AWAL (7 hari terakhir)
        // ==========================================
        for ($i = 7; $i >= 0; $i--) {
            $tanggal = now()->subDays($i);

            foreach ($users as $user) {
                // Saldo awal Kas
                TransaksiBank::create([
                    'tenant_id' => $tenantId,
                    'cabang_id' => $user->cabang_id,
                    'user_id' => $user->id,
                    'bank_id' => $kasId,
                    'jenis_transaksi_id' => $jenisPenambahan->id,
                    'nominal' => 5000000,
                    'bayar' => 5000000,
                    'debit' => 5000000,
                    'kredit' => 0,
                    'is_saldo_awal' => 1,
                    'keterangan' => 'Saldo Awal',
                    'waktu_transaksi' => $tanggal->copy()->setTime(8, 0, 0),
                ]);

                // Saldo awal bank lain
                foreach ($banks as $bank) {
                    $saldo = rand(1000000, 3000000);
                    TransaksiBank::create([
                        'tenant_id' => $tenantId,
                        'cabang_id' => $user->cabang_id,
                        'user_id' => $user->id,
                        'bank_id' => $bank->id,
                        'jenis_transaksi_id' => $jenisPenambahan->id,
                        'nominal' => $saldo,
                        'bayar' => $saldo,
                        'debit' => $saldo,
                        'kredit' => 0,
                        'is_saldo_awal' => 1,
                        'keterangan' => 'Saldo Awal',
                        'waktu_transaksi' => $tanggal->copy()->setTime(8, 0, 0),
                    ]);
                }
            }
        }

        // ==========================================
        // TRANSAKSI HARIAN (7 hari terakhir)
        // ==========================================
        for ($i = 7; $i >= 0; $i--) {
            $tanggal = now()->subDays($i);

            foreach ($users as $user) {
                $jmlTransaksi = rand(5, 10);

                for ($j = 0; $j < $jmlTransaksi; $j++) {
                    $bank = $banks->random();
                    $waktu = $tanggal->copy()->setTime(rand(9, 20), rand(0, 59), 0);
                    $rand = rand(1, 10);

                    if ($rand <= 6) {
                        // ========== TRANSFER (60%) ==========
                        $nominal = [50000, 100000, 200000, 500000][rand(0, 3)];
                        $admin = [5000, 7000, 10000][rand(0, 2)];
                        $bayar = $nominal + $admin;

                        // Transaksi Bank
                        TransaksiBank::create([
                            'tenant_id' => $tenantId,
                            'cabang_id' => $user->cabang_id,
                            'user_id' => $user->id,
                            'bank_id' => $bank->id,
                            'jenis_transaksi_id' => $jenisTransfer->id,
                            'nominal' => $nominal,
                            'bayar' => $bayar,
                            'debit' => 0,
                            'kredit' => $nominal,
                            'is_saldo_awal' => 0,
                            'keterangan' => 'Transfer',
                            'waktu_transaksi' => $waktu,
                        ]);

                        // Pasangan Kas
                        TransaksiBank::create([
                            'tenant_id' => $tenantId,
                            'cabang_id' => $user->cabang_id,
                            'user_id' => $user->id,
                            'bank_id' => $kasId,
                            'jenis_transaksi_id' => $jenisTransfer->id,
                            'nominal' => $nominal,
                            'bayar' => $bayar,
                            'debit' => $bayar,
                            'kredit' => 0,
                            'is_saldo_awal' => 0,
                            'keterangan' => 'Transfer',
                            'waktu_transaksi' => $waktu,
                        ]);

                    } elseif ($rand <= 9) {
                        // ========== TARIK TUNAI (30%) ==========
                        $bayar = [50000, 100000, 200000][rand(0, 2)];
                        $admin = [5000, 7000, 10000][rand(0, 2)];
                        $nominal = $bayar + $admin;

                        // Transaksi Bank
                        TransaksiBank::create([
                            'tenant_id' => $tenantId,
                            'cabang_id' => $user->cabang_id,
                            'user_id' => $user->id,
                            'bank_id' => $bank->id,
                            'jenis_transaksi_id' => $jenisTarikTunai->id,
                            'nominal' => $nominal,
                            'bayar' => $bayar,
                            'debit' => $nominal,
                            'kredit' => 0,
                            'is_saldo_awal' => 0,
                            'keterangan' => 'Tarik Tunai',
                            'waktu_transaksi' => $waktu,
                        ]);

                        // Pasangan Kas
                        TransaksiBank::create([
                            'tenant_id' => $tenantId,
                            'cabang_id' => $user->cabang_id,
                            'user_id' => $user->id,
                            'bank_id' => $kasId,
                            'jenis_transaksi_id' => $jenisTarikTunai->id,
                            'nominal' => $nominal,
                            'bayar' => $bayar,
                            'debit' => 0,
                            'kredit' => $bayar,
                            'is_saldo_awal' => 0,
                            'keterangan' => 'Tarik Tunai',
                            'waktu_transaksi' => $waktu,
                        ]);

                    } else {
                        // ========== NUMPANG TRANSFER (10%) ==========
                        $bayar = [5000, 7000, 10000][rand(0, 2)];

                        // Transaksi Bank
                        TransaksiBank::create([
                            'tenant_id' => $tenantId,
                            'cabang_id' => $user->cabang_id,
                            'user_id' => $user->id,
                            'bank_id' => $bank->id,
                            'jenis_transaksi_id' => $jenisNumpang->id,
                            'nominal' => 0,
                            'bayar' => $bayar,
                            'debit' => 0,
                            'kredit' => 0,
                            'is_saldo_awal' => 0,
                            'keterangan' => 'Numpang TF',
                            'waktu_transaksi' => $waktu,
                        ]);

                        // Pasangan Kas
                        TransaksiBank::create([
                            'tenant_id' => $tenantId,
                            'cabang_id' => $user->cabang_id,
                            'user_id' => $user->id,
                            'bank_id' => $kasId,
                            'jenis_transaksi_id' => $jenisNumpang->id,
                            'nominal' => 0,
                            'bayar' => $bayar,
                            'debit' => $bayar,
                            'kredit' => 0,
                            'is_saldo_awal' => 0,
                            'keterangan' => 'Numpang TF',
                            'waktu_transaksi' => $waktu,
                        ]);
                    }
                }

                // ========== PENGELUARAN (1-2 per hari) ==========
                $jmlPengeluaran = rand(1, 2);
                for ($k = 0; $k < $jmlPengeluaran; $k++) {
                    $nominal = [20000, 50000, 100000][rand(0, 2)];
                    $waktu = $tanggal->copy()->setTime(rand(8, 17), rand(0, 59), 0);
                    $idx = array_rand($akunIds);

                    TransaksiBank::create([
                        'tenant_id' => $tenantId,
                        'cabang_id' => $user->cabang_id,
                        'user_id' => $user->id,
                        'bank_id' => $kasId,
                        'jenis_transaksi_id' => $jenisPengurangan->id,
                        'nominal' => $nominal,
                        'bayar' => $nominal,
                        'debit' => 0,
                        'kredit' => $nominal,
                        'is_saldo_awal' => 0,
                        'akun_pengeluaran_id' => $akunIds[$idx],
                        'keterangan' => $akunNama[$idx],
                        'waktu_transaksi' => $waktu,
                    ]);
                }
            }
        }

        $this->command->newLine();
        $this->command->info('═══════════════════════════════════');
        $this->command->info('  ✅ DATA DUMMY BERHASIL DIBUAT');
        $this->command->info('═══════════════════════════════════');
        $this->command->info('Tenant ID: ' . $tenantId);
        $this->command->info('Rizki (126): ' . TransaksiBank::where('user_id', 126)->count() . ' transaksi');
        $this->command->info('Asep (129): ' . TransaksiBank::where('user_id', 129)->count() . ' transaksi');
        $this->command->info('Total: ' . TransaksiBank::count() . ' transaksi');
        $this->command->info('═══════════════════════════════════');
    }
}