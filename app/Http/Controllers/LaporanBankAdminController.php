<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\JenisTransaksi;
use App\Models\TransaksiBank;
use App\Models\Cabang;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanBankAdminExport;

class LaporanBankAdminController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $tanggal = $request->input('tanggal', now()->toDateString());
        $cabang_id = $request->input('cabang_id');
        $user_id = $request->input('user_id');

        $cabangs = Cabang::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)->orWhereNull('tenant_id');
        })
            ->orderByRaw("CASE WHEN tenant_id IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('nama_cabang', 'asc')
            ->get();

        $users = User::where('tenant_id', $tenantId)
            ->where('id', '!=', Auth::id())
            ->orderBy('name', 'asc')
            ->get();

        $jenisTransaksis = JenisTransaksi::all();
        $dataBanks = Bank::where(function ($q) use ($tenantId) {
            $q->where('tenant_id', $tenantId)->orWhereNull('tenant_id');
        })->get();

        if (!$cabang_id || !$user_id) {
            return view('laporan_bank.index', [
                'transaksis' => collect(),
                'tanggal' => $tanggal,
                'cabangs' => $cabangs,
                'cabang_id' => $cabang_id,
                'user_id' => $user_id,
                'jenisTransaksis' => $jenisTransaksis,
                'users' => $users,
                'dataBanks' => $dataBanks,
                'saldoKas' => 0,
            ])->with('warning', 'Silakan pilih Cabang dan User terlebih dahulu.');
        }

        $transaksis = $this->ambilTransaksiHariIni($tenantId, $user_id, $tanggal);
        ['transaksis' => $filteredTransaksis, 'saldoPerBank' => $saldoPerBank] = $this->hitungSaldoPerBaris($transaksis);

        return view('laporan_bank.index', [
            'transaksis' => $filteredTransaksis,
            'tanggal' => $tanggal,
            'cabangs' => $cabangs,
            'cabang_id' => $cabang_id,
            'user_id' => $user_id,
            'jenisTransaksis' => $jenisTransaksis,
            'users' => $users,
            'dataBanks' => $dataBanks,
            'saldoKas' => $saldoPerBank['kas'] ?? 0,
        ]);
    }

    public function getUsersByCabang($cabangId)
    {
        $tenantId = Auth::user()->tenant_id;

        $users = User::where('cabang_id', $cabangId)
            ->where('tenant_id', $tenantId) // ✅ Filter tenant
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return response()->json($users);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_transaksi_id' => 'required',
            'bank_id' => 'required',
            'nominal' => 'required|numeric',
        ]);

        $trx = TransaksiBank::findOrFail($id);
        $kasId = Bank::where('nama_bank', 'Kas')->first()?->id;

        $jenisBaru = strtolower(JenisTransaksi::find($request->jenis_transaksi_id)->nama_transaksi ?? '');
        $nominalBaru = (float) $request->nominal;
        $bayarBaru = (float) ($request->bayar ?? 0);
        $bankBaruId = (int) $request->bank_id;

        // Pertahankan waktu asli (supaya urutan tidak pindah)
        $waktuAsli = $trx->waktu_transaksi;
        $waktuBaru = $waktuAsli;

        if ($request->filled('waktu_transaksi')) {
            $waktuInput = Carbon::parse($request->waktu_transaksi);
            if (abs($waktuInput->diffInSeconds($waktuAsli)) > 60) {
                $waktuBaru = $waktuInput;
            }
        }

        // =============================================
        // HAPUS pasangan Kas lama — SPESIFIK
        // (jenis + bayar/nominal + waktu dekat)
        // supaya tidak nyasar ke transaksi lain di menit sama
        // =============================================
        if ($kasId && $trx->bank_id != $kasId) {
            TransaksiBank::where('bank_id', $kasId)
                ->where('id', '!=', $trx->id)
                ->where('user_id', $trx->user_id)
                ->where('tenant_id', $trx->tenant_id)
                ->where('jenis_transaksi_id', $trx->jenis_transaksi_id)
                ->where(function ($q) use ($trx) {
                    $q->where('bayar', $trx->bayar)
                        ->orWhere('nominal', $trx->nominal);
                })
                ->whereBetween('waktu_transaksi', [
                    Carbon::parse($waktuAsli)->subSeconds(10),
                    Carbon::parse($waktuAsli)->addSeconds(10),
                ])
                ->delete();
        }

        // =============================================
        // Tentukan debit / kredit + perlu pasangan?
        // =============================================
        $debit = $kredit = $kasDebit = $kasKredit = 0;
        $buatPasanganKas = false;

        switch ($jenisBaru) {
            case 'transfer':
                $kredit = $nominalBaru;
                $kasDebit = $bayarBaru;
                $buatPasanganKas = true;
                break;

            case 'tarik tunai':
                $debit = $nominalBaru;
                $kasKredit = $bayarBaru;
                $buatPasanganKas = true;
                break;

            case 'numpang transfer':
                $kasDebit = $bayarBaru;
                $buatPasanganKas = true;
                break;

            case 'penambahan saldo':
                $debit = $nominalBaru;
                $kasDebit = $nominalBaru;
                $buatPasanganKas = true;
                break;

            case 'pengurangan saldo':
                $kredit = $nominalBaru;
                $kasKredit = $nominalBaru;
                $buatPasanganKas = false;
                break;

            case 'penambahan kas':
                $debit = $nominalBaru;
                $buatPasanganKas = false;
                break;

            case 'pengurangan kas':
                $kredit = $nominalBaru;
                $buatPasanganKas = false;
                break;

            default:
                $debit = $nominalBaru;
                $buatPasanganKas = false;
                break;
        }

        // Update transaksi utama
        $trx->update([
            'bank_id' => $bankBaruId,
            'jenis_transaksi_id' => $request->jenis_transaksi_id,
            'nominal' => $nominalBaru,
            'bayar' => $bayarBaru,
            'debit' => $debit,
            'kredit' => $kredit,
            'keterangan' => $request->keterangan,
            'waktu_transaksi' => $waktuBaru,
        ]);

        // Buat pasangan Kas baru (hanya jika perlu)
        if (
            $kasId &&
            $buatPasanganKas &&
            !$trx->is_saldo_awal &&
            $bankBaruId != $kasId
        ) {
            TransaksiBank::create([
                'tenant_id' => $trx->tenant_id,
                'user_id' => $trx->user_id,
                'cabang_id' => $trx->cabang_id,
                'bank_id' => $kasId,
                'jenis_transaksi_id' => $request->jenis_transaksi_id,
                'nominal' => $nominalBaru,
                'bayar' => $bayarBaru,
                'debit' => $kasDebit,
                'kredit' => $kasKredit,
                'keterangan' => $request->keterangan,
                'waktu_transaksi' => $waktuBaru,
                'is_saldo_awal' => 0,
            ]);
        }

        return back()->with('success', 'Update berhasil ✅ Urutan tidak berubah.');
    }

    public function destroy($id)
    {
        $trx = TransaksiBank::findOrFail($id);
        $kasId = Bank::where('nama_bank', 'Kas')->first()?->id;
        $waktu = Carbon::parse($trx->waktu_transaksi);

        // Hapus pasangan Kas — SPESIFIK
        if ($kasId && $trx->bank_id != $kasId) {
            TransaksiBank::where('bank_id', $kasId)
                ->where('id', '!=', $id)
                ->where('user_id', $trx->user_id)
                ->where('tenant_id', $trx->tenant_id)
                ->where('jenis_transaksi_id', $trx->jenis_transaksi_id)
                ->where(function ($q) use ($trx) {
                    $q->where('bayar', $trx->bayar)
                        ->orWhere('nominal', $trx->nominal);
                })
                ->whereBetween('waktu_transaksi', [
                    $waktu->copy()->subSeconds(10),
                    $waktu->copy()->addSeconds(10),
                ])
                ->delete();
        }

        $trx->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus.');
    }

    public function rekap(Request $request)
    {
        $request->validate(['cabang_id' => 'required', 'user_id' => 'required']);
        $data = $this->getRekapData($request);
        return view('laporan_bank.rekap', $data);
    }

    public function exportRekapPdf(Request $request)
    {
        $request->validate(['cabang_id' => 'required', 'user_id' => 'required']);
        $data = $this->getRekapData($request);
        $pdf = Pdf::loadView('laporan_bank.rekap_pdf', $data)->setPaper('a4', 'portrait');
        return $pdf->download('laporan-bank-' . $data['tanggal'] . '.pdf');
    }

    public function exportRekapExcel(Request $request)
    {
        $request->validate(['cabang_id' => 'required', 'user_id' => 'required']);
        $data = $this->getRekapData($request);
        return Excel::download(new LaporanBankAdminExport($data), 'laporan-bank-' . $data['tanggal'] . '.xlsx');
    }

    private function ambilTransaksiHariIni($tenantId, $userId, $tanggal)
    {
        return TransaksiBank::with(['jenis_transaksi', 'bank', 'user.cabang'])
            ->where('tenant_id', $tenantId)
            ->where('user_id', $userId)
            ->whereDate('waktu_transaksi', $tanggal)
            ->orderBy('waktu_transaksi', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    private function hitungSaldoPerBaris($transaksis)
    {
        $saldoPerBank = [];
        $filteredTransaksis = collect();
        $runningKas = 0;
        $kasId = Bank::where('nama_bank', 'Kas')->first()?->id;

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
            $bankId = $trx->bank_id;

            // Skip pasangan Kas
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

            // Laba Bersih
            if ($bankId != $kasId) {
                if ($jenis === 'transfer') {
                    $trx->laba_bersih = $bayar - $nominal;
                } elseif ($jenis === 'tarik tunai') {
                    $trx->laba_bersih = $nominal - $bayar;
                } else {
                    $trx->laba_bersih = 0;
                }
            } else {
                $trx->laba_bersih = 0;
            }

            $trx->saldo_akhir_dynamic = $saldoPerBank[$bankName];
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

    private function getRekapData(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());
        $cabangId = $request->input('cabang_id');
        $userId = $request->input('user_id');

        $cabang = Cabang::find($cabangId);
        $user = User::find($userId);
        $tenantId = Auth::user()->tenant_id;

        $transaksis = $this->ambilTransaksiHariIni($tenantId, $userId, $tanggal);
        $hasil = $this->hitungSaldoPerBaris($transaksis);

        $kasId = Bank::where('nama_bank', 'Kas')->first()?->id;

        $saldoAwalKas = $transaksis->where('is_saldo_awal', 1)
            ->filter(fn($t) => $t->bank_id == $kasId)
            ->sum('nominal');

        $tambahanKas = $transaksis->where('is_saldo_awal', 0)
            ->filter(fn($t) => $t->bank_id == $kasId && ($t->jenis_transaksi->nama_transaksi ?? '') === 'Penambahan Kas')
            ->sum('nominal');

        $penguranganKas = $transaksis->where('is_saldo_awal', 0)
            ->filter(fn($t) => $t->bank_id == $kasId && ($t->jenis_transaksi->nama_transaksi ?? '') === 'Pengurangan Kas')
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
            'cabang' => $cabang,
            'user' => $user,
            'saldoAwalKas' => $saldoAwalKas,
            'tambahanKas' => $tambahanKas,
            'penguranganKas' => $penguranganKas,
            'totalTransfer' => $totalTransfer,
            'totalTarikTunai' => $totalTarikTunai,
            'saldoAkhirKas' => $hasil['saldoPerBank']['kas'] ?? 0,
            'saldoBank' => $hasil['saldoPerBank'],
        ];
    }
}