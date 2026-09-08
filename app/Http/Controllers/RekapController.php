<?php

namespace App\Http\Controllers;

use App\Models\TransaksiBank;
use App\Models\Bank;
use App\Models\Cabang;
use App\Models\User;
use App\Models\AkunPengeluaran;
use App\Models\Tenant;
use App\Models\Penjualan;
use App\Models\ProdukKonter;
use App\Models\Retur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapExport;
use Carbon\Carbon;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $tanggal = $request->tanggal ?? now()->toDateString();
        $cabangId = $request->cabang_id;

        $cabangs = Cabang::where('tenant_id', $tenantId)->get();

        $query = TransaksiBank::with(['jenis_transaksi', 'bank', 'user.cabang'])
            ->where('tenant_id', $tenantId)
            ->whereDate('waktu_transaksi', $tanggal);

        if ($cabangId && $cabangId !== 'semua') {
            $query->where('cabang_id', $cabangId);
        }

        $transaksis = $query->orderBy('waktu_transaksi', 'asc')->get();

        $data = $this->hitungRekap($transaksis, $tenantId, $tanggal, $cabangs, $cabangId);

        // ✅ Tambahan data POS & Stok
        $data['totalPos'] = Penjualan::where('tenant_id', $tenantId)
            ->whereDate('created_at', $tanggal)
            ->when($cabangId && $cabangId !== 'semua', fn($q) => $q->where('cabang_id', $cabangId))
            ->count();

        $data['totalPosNominal'] = Penjualan::where('tenant_id', $tenantId)
            ->whereDate('created_at', $tanggal)
            ->when($cabangId && $cabangId !== 'semua', fn($q) => $q->where('cabang_id', $cabangId))
            ->sum('total_setelah_diskon');

        $data['stokMenipisCount'] = ProdukKonter::where('tenant_id', $tenantId)
            ->where('stok', '<=', 5)
            ->count();

        $data['returPendingCount'] = Retur::where('tenant_id', $tenantId)
            ->where('status', 'pending')
            ->count();

        return view('rekap.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $tanggal = $request->tanggal ?? now()->toDateString();
        $cabangId = $request->cabang_id;
        $cabangs = Cabang::where('tenant_id', $tenantId)->get();

        $tenant = Tenant::find($tenantId);

        $query = TransaksiBank::with(['jenis_transaksi', 'bank', 'user.cabang'])
            ->where('tenant_id', $tenantId)
            ->whereDate('waktu_transaksi', $tanggal);

        if ($cabangId && $cabangId !== 'semua') {
            $query->where('cabang_id', $cabangId);
        }

        $transaksis = $query->orderBy('waktu_transaksi', 'asc')->get();
        $data = $this->hitungRekap($transaksis, $tenantId, $tanggal, $cabangs, $cabangId);

        $data['tenant'] = $tenant;
        $data['tanggal'] = $tanggal;
        $data['cabang_terpilih'] = $cabangId && $cabangId !== 'semua'
            ? Cabang::find($cabangId)->nama_cabang
            : 'Semua Cabang';

        $pdf = Pdf::loadView('rekap.pdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('rekap-' . $tanggal . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $tanggal = $request->tanggal ?? now()->toDateString();
        $cabangId = $request->cabang_id;
        $cabangs = Cabang::where('tenant_id', $tenantId)->get();

        $query = TransaksiBank::with(['jenis_transaksi', 'bank', 'user.cabang'])
            ->where('tenant_id', $tenantId)
            ->whereDate('waktu_transaksi', $tanggal);

        if ($cabangId && $cabangId !== 'semua') {
            $query->where('cabang_id', $cabangId);
        }

        $transaksis = $query->orderBy('waktu_transaksi', 'asc')->get();
        $data = $this->hitungRekap($transaksis, $tenantId, $tanggal, $cabangs, $cabangId);

        $data['cabang_terpilih'] = $cabangId && $cabangId !== 'semua'
            ? Cabang::find($cabangId)->nama_cabang
            : 'Semua Cabang';

        return Excel::download(new RekapExport($data), 'rekap-' . $tanggal . '.xlsx');
    }

    private function hitungRekap($transaksis, $tenantId, $tanggal, $cabangs, $cabangId = null)
    {
        $operSaldoId = AkunPengeluaran::where('nama_akun', 'Oper Saldo')->value('id');

        $totalOmzet = 0;
        $totalTransfer = 0;
        $totalTarikTunai = 0;
        $totalNumpang = 0;
        $totalPenambahanKas = 0;
        $totalPenguranganKas = 0;
        $totalTransaksi = $transaksis->count();

        $bankKas = Bank::where(function ($q) use ($tenantId) {
            $q->where('tenant_id', $tenantId)->orWhereNull('tenant_id');
        })->where('nama_bank', 'Kas')->first();

        $kasId = $bankKas?->id;

        foreach ($transaksis as $trx) {
            $jenis = strtolower($trx->jenis_transaksi->nama_transaksi ?? '');
            $bankId = $trx->bank_id;

            if ($bankId !== $kasId) {
                if ($jenis === 'transfer') {
                    $totalOmzet += $trx->bayar - $trx->nominal;
                    $totalTransfer++;
                } elseif ($jenis === 'tarik tunai') {
                    $totalOmzet += $trx->nominal - $trx->bayar;
                    $totalTarikTunai++;
                } elseif ($jenis === 'numpang transfer') {
                    $totalOmzet += $trx->bayar;
                    $totalNumpang++;
                }
            }

            if ($bankId === $kasId) {
                if ($jenis === 'penambahan kas' || str_contains($jenis, 'penambahan')) {
                    $totalPenambahanKas++;
                } elseif ($jenis === 'pengurangan kas' || str_contains($jenis, 'pengurangan')) {
                    $totalPenguranganKas++;
                }
            }
        }

        $totalPengeluaran = TransaksiBank::where('tenant_id', $tenantId)
            ->whereDate('waktu_transaksi', $tanggal)
            ->whereNotNull('akun_pengeluaran_id')
            ->when($operSaldoId, fn($q) => $q->where('akun_pengeluaran_id', '!=', $operSaldoId))
            ->when($cabangId && $cabangId !== 'semua', fn($q) => $q->where('cabang_id', $cabangId))
            ->sum('nominal');

        $profit = $totalOmzet - $totalPengeluaran;

        $totalSaldoKas = 0;
        if ($kasId) {
            $saldoKasQuery = TransaksiBank::where('bank_id', $kasId)
                ->where('tenant_id', $tenantId)
                ->whereDate('waktu_transaksi', $tanggal);

            if ($cabangId && $cabangId !== 'semua') {
                $saldoKasQuery->where('cabang_id', $cabangId);
            }

            $rowKas = $saldoKasQuery->selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')->first();
            $totalSaldoKas = ($rowKas->total_debit ?? 0) - ($rowKas->total_kredit ?? 0);
        }

        $banks = Bank::where(function ($q) use ($tenantId) {
            $q->where('tenant_id', $tenantId)->orWhereNull('tenant_id');
        })->get();

        $rekapBank = $banks->map(function ($b) use ($transaksis) {
            $trxBank = $transaksis->where('bank_id', $b->id);
            return [
                'nama' => $b->nama_bank,
                'saldo' => $trxBank->sum('debit') - $trxBank->sum('kredit'),
                'total_trx' => $trxBank->count(),
                'debit' => $trxBank->sum('debit'),
                'kredit' => $trxBank->sum('kredit'),
            ];
        });

        $rekapCabang = $cabangs->map(function ($c) use ($transaksis, $kasId, $operSaldoId) {
            $trxCabang = $transaksis->where('cabang_id', $c->id);
            $trxNonKas = $trxCabang->where('bank_id', '!=', $kasId);

            $omzet = $trxNonKas->sum(function ($t) {
                $jenis = strtolower($t->jenis_transaksi->nama_transaksi ?? '');
                if ($jenis === 'transfer')
                    return $t->bayar - $t->nominal;
                if ($jenis === 'tarik tunai')
                    return $t->nominal - $t->bayar;
                if ($jenis === 'numpang transfer')
                    return $t->bayar;
                return 0;
            });

            $pengeluaranCabang = $trxCabang
                ->whereNotNull('akun_pengeluaran_id')
                ->when($operSaldoId, fn($q) => $q->where('akun_pengeluaran_id', '!=', $operSaldoId))
                ->sum('nominal');

            return [
                'nama' => $c->nama_cabang,
                'omzet' => $omzet,
                'pengeluaran' => $pengeluaranCabang,
                'profit' => $omzet - $pengeluaranCabang,
                'total_trx' => $trxNonKas->count(),
                'saldo_kas' => $trxCabang->where('bank_id', $kasId)->sum('debit') - $trxCabang->where('bank_id', $kasId)->sum('kredit'),
            ];
        });

        $users = User::with('cabang')
            ->where('tenant_id', $tenantId)
            ->when($cabangId && $cabangId !== 'semua', fn($q) => $q->where('cabang_id', $cabangId))
            ->get();

        $rekapUser = $users->map(function ($u) use ($transaksis, $kasId, $operSaldoId) {
            $trxUser = $transaksis->where('user_id', $u->id);
            $trxNonKas = $trxUser->where('bank_id', '!=', $kasId);

            $pengeluaranUser = $trxUser
                ->whereNotNull('akun_pengeluaran_id')
                ->when($operSaldoId, fn($q) => $q->where('akun_pengeluaran_id', '!=', $operSaldoId))
                ->sum('nominal');

            $omzetUser = $trxNonKas->sum(function ($t) {
                $jenis = strtolower($t->jenis_transaksi->nama_transaksi ?? '');
                if ($jenis === 'transfer')
                    return $t->bayar - $t->nominal;
                if ($jenis === 'tarik tunai')
                    return $t->nominal - $t->bayar;
                if ($jenis === 'numpang transfer')
                    return $t->bayar;
                return 0;
            });

            return [
                'nama' => $u->name,
                'cabang' => $u->cabang->nama_cabang ?? '-',
                'total_trx' => $trxUser->count(),
                'omzet' => $omzetUser,
                'pengeluaran' => $pengeluaranUser,
                'profit' => $omzetUser - $pengeluaranUser,
            ];
        });

        $labelsOmzet7Hari = [];
        $dataOmzet7Hari = [];
        $dataPengeluaran7Hari = [];
        $dataProfit7Hari = [];

        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $labelsOmzet7Hari[] = Carbon::parse($d)->translatedFormat('d M');

            $omzetHarian = TransaksiBank::where('tenant_id', $tenantId)
                ->whereDate('waktu_transaksi', $d)
                ->where('bank_id', '!=', $kasId)
                ->when($cabangId && $cabangId !== 'semua', fn($q) => $q->where('cabang_id', $cabangId))
                ->get()
                ->sum(function ($t) {
                    $jenis = strtolower($t->jenis_transaksi->nama_transaksi ?? '');
                    if ($jenis === 'transfer')
                        return $t->bayar - $t->nominal;
                    if ($jenis === 'tarik tunai')
                        return $t->nominal - $t->bayar;
                    if ($jenis === 'numpang transfer')
                        return $t->bayar;
                    return 0;
                });

            $pengeluaranHarian = TransaksiBank::where('tenant_id', $tenantId)
                ->whereDate('waktu_transaksi', $d)
                ->whereNotNull('akun_pengeluaran_id')
                ->when($operSaldoId, fn($q) => $q->where('akun_pengeluaran_id', '!=', $operSaldoId))
                ->when($cabangId && $cabangId !== 'semua', fn($q) => $q->where('cabang_id', $cabangId))
                ->sum('nominal');

            $dataOmzet7Hari[] = $omzetHarian;
            $dataPengeluaran7Hari[] = $pengeluaranHarian;
            $dataProfit7Hari[] = $omzetHarian - $pengeluaranHarian;
        }

        $labelsPerJam = [];
        $dataPerJam = [];
        for ($h = 8; $h <= 20; $h++) {
            $labelsPerJam[] = sprintf('%02d:00', $h);
            $dataPerJam[] = $transaksis->filter(function ($t) use ($h) {
                return Carbon::parse($t->waktu_transaksi)->hour == $h;
            })->count();
        }

        return compact(
            'tanggal',
            'cabangs',
            'totalOmzet',
            'totalPengeluaran',
            'profit',
            'totalSaldoKas',
            'totalTransaksi',
            'totalTransfer',
            'totalTarikTunai',
            'totalNumpang',
            'totalPenambahanKas',
            'totalPenguranganKas',
            'rekapBank',
            'rekapCabang',
            'rekapUser',
            'labelsOmzet7Hari',
            'dataOmzet7Hari',
            'dataPengeluaran7Hari',
            'dataProfit7Hari',
            'labelsPerJam',
            'dataPerJam',
            'cabangId'
        );
    }
}