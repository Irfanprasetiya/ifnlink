<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanSetoranExport;
use App\Models\Bank;
use App\Models\TransaksiBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // ✅ Hitung metric cards dari transaksi hari ini
        $kasId = Bank::where('nama_bank', 'Kas')->first()?->id;

        $saldoAwalKas = $transaksis->where('is_saldo_awal', 1)
            ->filter(fn($t) => $t->bank_id == $kasId)
            ->sum('nominal');

        $tambahanKas = $transaksis->where('is_saldo_awal', 0)
            ->filter(fn($t) => $t->bank_id == $kasId && $t->jenis_transaksi->nama_transaksi === 'Penambahan Kas')
            ->sum('nominal');

        $penguranganKas = $transaksis->where('is_saldo_awal', 0)
            ->filter(fn($t) => $t->bank_id == $kasId && $t->jenis_transaksi->nama_transaksi === 'Pengurangan Kas')
            ->sum('nominal');

        $totalTransfer = $transaksis
            ->filter(fn($t) => $t->bank_id != $kasId && in_array(strtolower($t->jenis_transaksi->nama_transaksi ?? ''), ['transfer', 'numpang transfer']))
            ->sum('bayar');

        $totalTarikTunai = $transaksis
            ->filter(fn($t) => $t->bank_id != $kasId && strtolower($t->jenis_transaksi->nama_transaksi ?? '') === 'tarik tunai')
            ->sum('bayar');

        return [
            'transaksis' => $hasil['transaksis'],
            'tanggal' => $tanggal,
            'user' => $user,
            // Metric cards
            'saldoAwalKas' => $saldoAwalKas,
            'tambahanKas' => $tambahanKas,
            'penguranganKas' => $penguranganKas,
            'totalTransfer' => $totalTransfer,
            'totalTarikTunai' => $totalTarikTunai,
            'saldoAkhirKas' => $hasil['saldoPerBank']['kas'] ?? 0,
            'saldoBank' => $hasil['saldoPerBank'],
        ];
    }

    private function ambilTransaksiHariIni($user, $tanggal)
    {
        return TransaksiBank::with(['jenis_transaksi', 'bank'])
            ->where('tenant_id', $user->tenant_id)
            ->where('cabang_id', $user->cabang_id)
            ->where('user_id', $user->id)
            ->whereDate('waktu_transaksi', $tanggal)
            ->orderBy('waktu_transaksi', 'asc')
            ->get();
    }

    /**
     * Hitung saldo berjalan PER BARIS dari debit-kredit.
     * Mengembalikan 2 hal terpisah:
     * - 'transaksis' => daftar baris yang DITAMPILKAN (baris pasangan Kas disembunyikan)
     * - 'saldoPerBank' => peta SALDO AKHIR SEBENARNYA tiap bank (termasuk Kas,
     *   dihitung dari SEMUA baris, walau baris Kas tsb disembunyikan dari tampilan)
     */
    private function hitungSaldoPerBaris($transaksis)
    {
        $saldoPerBank = [];
        $filteredTransaksis = collect();
        $runningKas = 0;

        $sorted = $transaksis
            ->sortBy(function ($trx) {
                return sprintf(
                    '%s-%020d',
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
                    // Bank selain Kas
                    if ($jenis === 'tarik tunai') {
                        $saldoPerBank[$bankName] += $nominal;
                    } elseif (in_array($jenis, ['transfer', 'numpang transfer'])) {
                        if ($jenis !== 'numpang transfer') {
                            $saldoPerBank[$bankName] -= $nominal;
                        }
                    } elseif ($jenis === 'penambahan saldo') {
                        $saldoPerBank[$bankName] += $nominal; // hanya bank
                    } elseif ($jenis === 'pengurangan saldo') {
                        $saldoPerBank[$bankName] -= $nominal; // hanya bank
                    }
                }
            }

            // ===== SALDO KAS =====
            // Penambahan/Pengurangan Saldo TIDAK mempengaruhi Kas
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
                    // Hanya transaksi operasional yang mempengaruhi Kas
                    if ($jenis === 'tarik tunai') {
                        $runningKas -= $bayar;
                    } elseif (in_array($jenis, ['transfer', 'numpang transfer'])) {
                        $runningKas += $bayar;
                    }
                    // penambahan saldo / pengurangan saldo → tidak ubah Kas
                }
            }

            $trx->saldo_akhir = $saldoPerBank[$bankName];
            $trx->saldo_kas = $runningKas;

            $filteredTransaksis->push($trx);
        }

        $filteredTransaksis = $filteredTransaksis
            ->sortBy(function ($trx) {
                return sprintf(
                    '%d-%s-%020d',
                    $trx->is_saldo_awal ? 0 : 1,
                    \Carbon\Carbon::parse($trx->waktu_transaksi)->format('Y-m-d H:i:s'),
                    $trx->id
                );
            })
            ->values();

        return [
            'transaksis' => $filteredTransaksis,
            'saldoPerBank' => array_merge($saldoPerBank, ['kas' => $runningKas]),
        ];
    }
}