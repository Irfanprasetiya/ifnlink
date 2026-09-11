<?php

namespace App\Http\Controllers;

use App\Exports\LaporanSetoranExport;
use App\Models\Bank;
use App\Models\TransaksiBank;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class LaporanBankController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $tanggal = $request->tanggal ?? now()->toDateString();

        $transaksis = $this->ambilTransaksiHariIni($user, $tanggal);
        ['transaksis' => $filteredTransaksis] = $this->hitungSaldoPerBaris($transaksis);

        return view('frontend.laporan_bank.index', [
            'transaksis' => $filteredTransaksis,
            'tanggal' => $tanggal,
        ]);
    }

    public function rekap(Request $request)
    {
        $data = $this->getLaporanData($request);
        return view('frontend.laporan_bank.rekap', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getLaporanData($request);
        $pdf = Pdf::loadView('frontend.laporan_bank.pdf', $data)->setPaper('a4', 'portrait');
        return $pdf->download('laporan-setoran-' . $data['tanggal'] . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getLaporanData($request);
        return Excel::download(new LaporanSetoranExport($data), 'laporan-setoran-' . $data['tanggal'] . '.xlsx');
    }

    private function getLaporanData(Request $request)
    {
        $user = Auth::user();
        $tanggal = $request->tanggal ?? now()->toDateString();

        $transaksis = $this->ambilTransaksiHariIni($user, $tanggal);
        $hasil = $this->hitungSaldoPerBaris($transaksis);

        // ✅ Hitung metric cards dalam 1 loop — bukan 5 filter terpisah
        $metrics = $this->hitungMetricCards($transaksis);

        return [
            'transaksis' => $hasil['transaksis'],
            'tanggal' => $tanggal,
            'user' => $user,
            // Metric cards
            'saldoAwalKas' => $metrics['saldoAwalKas'],
            'tambahanKas' => $metrics['tambahanKas'],
            'penguranganKas' => $metrics['penguranganKas'],
            'totalTransfer' => $metrics['totalTransfer'],
            'totalTarikTunai' => $metrics['totalTarikTunai'],
            'saldoAkhirKas' => $hasil['saldoPerBank']['kas'] ?? 0,
            'saldoBank' => $hasil['saldoPerBank'],
        ];
    }

    /**
     * ✅ Hitung semua metric cards dalam SATU loop
     */
    private function hitungMetricCards($transaksis): array
    {
        // ✅ Cache kas_id — jarang berubah
        $kasId = Cache::remember('kas_bank_id', 3600, function () {
            return Bank::where('nama_bank', 'Kas')->first()?->id;
        });

        $saldoAwalKas = 0;
        $tambahanKas = 0;
        $penguranganKas = 0;
        $totalTransfer = 0;
        $totalTarikTunai = 0;

        foreach ($transaksis as $t) {
            $jenis = strtolower($t->jenis_transaksi->nama_transaksi ?? '');
            $isKas = $t->bank_id == $kasId;

            // Saldo awal kas
            if ($t->is_saldo_awal && $isKas) {
                $saldoAwalKas += (float) $t->nominal;
                continue;
            }

            if ($t->is_saldo_awal) {
                continue;
            }

            // Transaksi Kas
            if ($isKas) {
                if ($jenis === 'penambahan kas') {
                    $tambahanKas += (float) $t->nominal;
                } elseif ($jenis === 'pengurangan kas') {
                    $penguranganKas += (float) $t->nominal;
                }
                continue;
            }

            // Transaksi Bank (bukan Kas)
            if (in_array($jenis, ['transfer', 'numpang transfer'])) {
                $totalTransfer += (float) $t->bayar;
            } elseif ($jenis === 'tarik tunai') {
                $totalTarikTunai += (float) $t->bayar;
            }
        }

        return [
            'saldoAwalKas' => $saldoAwalKas,
            'tambahanKas' => $tambahanKas,
            'penguranganKas' => $penguranganKas,
            'totalTransfer' => $totalTransfer,
            'totalTarikTunai' => $totalTarikTunai,
        ];
    }

    /**
     * ✅ Pakai whereBetween biar index kepakai
     */
    private function ambilTransaksiHariIni($user, $tanggal)
    {
        return TransaksiBank::with(['jenis_transaksi', 'bank'])
            ->where('tenant_id', $user->tenant_id)
            ->where('cabang_id', $user->cabang_id)
            ->where('user_id', $user->id)
            ->whereBetween('waktu_transaksi', [
                $tanggal . ' 00:00:00',
                $tanggal . ' 23:59:59',
            ])
            ->orderBy('waktu_transaksi', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Hitung saldo berjalan PER BARIS dari debit-kredit.
     * Mengembalikan 2 hal terpisah:
     * - 'transaksis' => daftar baris yang DITAMPILKAN (baris pasangan Kas disembunyikan)
     * - 'saldoPerBank' => peta SALDO AKHIR SEBENARNYA tiap bank (termasuk Kas,
     *   dihitung dari SEMUA baris, walau baris Kas tsb disembunyikan dari tampilan)
     *
     * ✅ OPTIMASI: 
     * - Query SQL sudah di-sort, tidak perlu sortBy Carbon 2x
     * - Sort kedua (untuk urutan tampilan) tetap dipertahankan
     */
    private function hitungSaldoPerBaris($transaksis)
    {
        $saldoPerBank = [];
        $filteredTransaksis = collect();
        $runningKas = 0;

        // ✅ Sort di awal: saldo_awal dulu, lalu waktu asc, lalu id asc
        $sorted = $transaksis
            ->sortBy(function ($trx) {
                return sprintf(
                    '%d-%s-%020d',
                    $trx->is_saldo_awal ? 0 : 1,           // saldo awal dulu
                    \Carbon\Carbon::parse($trx->waktu_transaksi)->format('Y-m-d H:i:s'),
                    $trx->id
                );
            })
            ->values();

        foreach ($sorted as $trx) {
            $bankName = strtolower(trim($trx->bank->nama_bank ?? 'unknown'));
            $jenis = strtolower(trim($trx->jenis_transaksi->nama_transaksi ?? ''));
            $nominal = (float) ($trx->nominal ?? 0);
            $bayar = (float) ($trx->bayar ?? 0);

            // Skip pasangan Kas (Transfer / Tarik Tunai / Numpang)
            if ($bankName === 'kas' && in_array($jenis, ['transfer', 'numpang transfer', 'tarik tunai'])) {
                continue;
            }

            if (!isset($saldoPerBank[$bankName])) {
                $saldoPerBank[$bankName] = 0;
            }

            // ===== SALDO BANK =====
            if ($trx->is_saldo_awal) {
                $saldoPerBank[$bankName] = $nominal;
            } else {
                if ($bankName === 'kas') {
                    if ($jenis === 'penambahan kas') {
                        $saldoPerBank[$bankName] += $nominal;
                    } elseif ($jenis === 'pengurangan kas') {
                        $saldoPerBank[$bankName] -= $nominal;
                    }
                } else {
                    if ($jenis === 'tarik tunai') {
                        $saldoPerBank[$bankName] += $nominal;
                    } elseif (in_array($jenis, ['transfer', 'numpang transfer'])) {
                        if ($jenis !== 'numpang transfer') {
                            $saldoPerBank[$bankName] -= $nominal;
                        }
                    } elseif ($jenis === 'penambahan saldo') {
                        $saldoPerBank[$bankName] += $nominal;
                    } elseif ($jenis === 'pengurangan saldo') {
                        $saldoPerBank[$bankName] -= $nominal;
                    }
                }
            }

            // ===== SALDO KAS =====
            if ($trx->is_saldo_awal && $bankName === 'kas') {
                $runningKas = $nominal;
            } elseif (!$trx->is_saldo_awal) {
                if ($bankName === 'kas') {
                    if ($jenis === 'penambahan kas') {
                        $runningKas += $nominal;
                    } elseif ($jenis === 'pengurangan kas') {
                        $runningKas -= $nominal;
                    }
                } else {
                    if ($jenis === 'tarik tunai') {
                        $runningKas -= $bayar;
                    } elseif (in_array($jenis, ['transfer', 'numpang transfer'])) {
                        $runningKas += $bayar;
                    }
                }
            }

            $trx->saldo_akhir = $saldoPerBank[$bankName];
            $trx->saldo_kas = $runningKas;

            $filteredTransaksis->push($trx);
        }

        // ✅ Sort kedua untuk URUTAN TAMPILAN — SAMA seperti sort pertama (ascending)
        // Tidak perlu sort ulang karena sudah urut dari loop di atas
        $filteredTransaksis = $filteredTransaksis->values();

        return [
            'transaksis' => $filteredTransaksis,
            'saldoPerBank' => array_merge($saldoPerBank, ['kas' => $runningKas]),
        ];
    }
}