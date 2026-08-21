<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\AkunPengeluaran;
use Illuminate\Http\Request;
use App\Models\TransaksiBank;
use App\Models\User;
use App\Models\Cabang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        if ($tenant && $tenant->status_langganan === 'pending') {
            return view('dashboard.pending', compact('tenant'));
        }

        $tenantId = $user->tenant_id;
        $periode = $request->periode ?? 'harian';
        $cabangId = $request->cabang_id ?? 'semua';
        $tanggal = $request->tanggal ?? now()->toDateString();

        $allCabangs = Cabang::where('tenant_id', $tenantId)->get();
        $cabangs = ($cabangId !== 'semua')
            ? Cabang::where('tenant_id', $tenantId)->where('id', $cabangId)->get()
            : $allCabangs;

        $userIds = User::where('tenant_id', $tenantId)
            ->when($cabangId !== 'semua', fn($q) => $q->where('cabang_id', $cabangId))
            ->pluck('id');

        // RANGE METRIK
        if ($periode === 'harian') {
            $metricStart = Carbon::parse($tanggal)->startOfDay();
            $metricEnd = Carbon::parse($tanggal)->endOfDay();
            $groupFormat = 'Y-m-d';
            $chartStart = now()->subDays(6)->startOfDay();
        } elseif ($periode === 'mingguan') {
            $metricStart = now()->startOfWeek();
            $metricEnd = now()->endOfWeek();
            $groupFormat = 'o-W';
            $chartStart = now()->subWeeks(7)->startOfWeek();
        } else {
            $metricStart = now()->startOfMonth();
            $metricEnd = now()->endOfMonth();
            $groupFormat = 'Y-m';
            $chartStart = now()->subMonths(5)->startOfMonth();
        }

        // ✅ Ambil ID Oper Saldo
        $operSaldoId = AkunPengeluaran::where('nama_akun', 'Oper Saldo')->value('id');

        // DATA KARTU RINGKASAN
        $trxMetric = TransaksiBank::with(['jenis_transaksi', 'bank'])
            ->where('tenant_id', $tenantId)
            ->whereIn('user_id', $userIds)
            ->whereBetween('waktu_transaksi', [$metricStart, $metricEnd])
            ->orderBy('waktu_transaksi', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $totalTransaksi = 0;
        $totalTransfer = 0;
        $totalTarikTunai = 0;
        $totalNumpang = 0;
        $totalLabaKotor = 0;

        foreach ($trxMetric as $trx) {
            $bankName = strtolower(trim($trx->bank->nama_bank ?? ''));
            $jenis = strtolower(trim($trx->jenis_transaksi->nama_transaksi ?? ''));
            $nominal = (float) ($trx->nominal ?? 0);
            $bayar = (float) ($trx->bayar ?? 0);

            if ($bankName === 'kas')
                continue;
            if (!in_array($jenis, ['tarik tunai', 'transfer', 'numpang transfer']))
                continue;

            $totalTransaksi++;

            if ($jenis === 'tarik tunai') {
                $totalLabaKotor += ($nominal - $bayar);
                $totalTarikTunai++;
            } elseif ($jenis === 'transfer') {
                $totalLabaKotor += ($bayar - $nominal);
                $totalTransfer++;
            } elseif ($jenis === 'numpang transfer') {
                $totalLabaKotor += $bayar;
                $totalNumpang++;
            }
        }

        // ✅ Pengeluaran Operasional (semua bank, skip Oper Saldo)
        $totalPengeluaran = TransaksiBank::where('tenant_id', $tenantId)
            ->whereIn('user_id', $userIds)
            ->whereBetween('waktu_transaksi', [$metricStart, $metricEnd])
            ->whereNotNull('akun_pengeluaran_id')
            ->when($operSaldoId, fn($q) => $q->where('akun_pengeluaran_id', '!=', $operSaldoId))
            ->where('is_saldo_awal', 0)
            ->sum('nominal');

        // ✅ Profit
        $profit = $totalLabaKotor - $totalPengeluaran;

        // ✅ Saldo Kas
        $totalSaldoKas = $this->hitungSaldoKas($trxMetric);

        // =============================================
        // ✅ DATA GRAFIK 3 GARIS (Omzet + Pengeluaran + Profit)
        // =============================================
        $trxChart = TransaksiBank::with(['jenis_transaksi', 'bank'])
            ->where('tenant_id', $tenantId)
            ->whereIn('user_id', $userIds)
            ->where('waktu_transaksi', '>=', $chartStart)
            ->where('waktu_transaksi', '<=', $metricEnd)
            ->orderBy('waktu_transaksi', 'asc')
            ->get();

        // ✅ 1. Omzet per periode
        $omzetChart = [];
        foreach ($trxChart as $trx) {
            $bankName = strtolower(trim($trx->bank->nama_bank ?? ''));
            $jenis = strtolower(trim($trx->jenis_transaksi->nama_transaksi ?? ''));
            $nominal = (float) ($trx->nominal ?? 0);
            $bayar = (float) ($trx->bayar ?? 0);

            if ($bankName === 'kas')
                continue;

            $laba = 0;
            if ($jenis === 'tarik tunai')
                $laba = $nominal - $bayar;
            elseif ($jenis === 'transfer')
                $laba = $bayar - $nominal;
            elseif ($jenis === 'numpang transfer')
                $laba = $bayar;
            else
                continue;

            $label = Carbon::parse($trx->waktu_transaksi)->format($groupFormat);
            if (!isset($omzetChart[$label]))
                $omzetChart[$label] = 0;
            $omzetChart[$label] += $laba;
        }

        // ✅ 2. Pengeluaran per periode
        $pengeluaranChart = TransaksiBank::where('tenant_id', $tenantId)
            ->whereIn('user_id', $userIds)
            ->where('waktu_transaksi', '>=', $chartStart)
            ->where('waktu_transaksi', '<=', $metricEnd)
            ->whereNotNull('akun_pengeluaran_id')
            ->when($operSaldoId, fn($q) => $q->where('akun_pengeluaran_id', '!=', $operSaldoId))
            ->where('is_saldo_awal', 0)
            ->get()
            ->groupBy(function ($trx) use ($groupFormat) {
                return Carbon::parse($trx->waktu_transaksi)->format($groupFormat);
            })
            ->map(fn($items) => $items->sum('nominal'));

        // ✅ 3. Profit per periode = Omzet - Pengeluaran
        $profitChart = $omzetChart;
        foreach ($pengeluaranChart as $label => $pengeluaran) {
            if (isset($profitChart[$label])) {
                $profitChart[$label] -= $pengeluaran;
            } else {
                $profitChart[$label] = -$pengeluaran;
            }
        }

        // ✅ Gabungkan semua label
        $allLabels = array_unique(array_merge(
            array_keys($omzetChart),
            array_keys($pengeluaranChart->toArray()),
            array_keys($profitChart)
        ));
        sort($allLabels);

        $labelsOmzet = [];
        $dataOmzetKotor = [];
        $dataPengeluaranChart = [];
        $dataOmzet = [];

        foreach ($allLabels as $label) {
            $labelsOmzet[] = $label;
            $dataOmzetKotor[] = $omzetChart[$label] ?? 0;
            $dataPengeluaranChart[] = $pengeluaranChart[$label] ?? 0;
            $dataOmzet[] = $profitChart[$label] ?? 0;
        }

        // =============================================
        // PERBANDINGAN CABANG (Profit per cabang)
        // =============================================
        $labelsCabang = [];
        $dataCabang = [];

        foreach ($cabangs as $cabang) {
            $labelsCabang[] = $cabang->nama_cabang;
            $ids = User::where('tenant_id', $tenantId)->where('cabang_id', $cabang->id)->pluck('id');

            $omzetCabang = TransaksiBank::with(['jenis_transaksi', 'bank'])
                ->where('tenant_id', $tenantId)
                ->whereIn('user_id', $ids)
                ->whereBetween('waktu_transaksi', [$metricStart, $metricEnd])
                ->get()
                ->sum(function ($trx) {
                    $bankName = strtolower(trim($trx->bank->nama_bank ?? ''));
                    $jenis = strtolower(trim($trx->jenis_transaksi->nama_transaksi ?? ''));
                    if ($bankName === 'kas')
                        return 0;
                    $nominal = (float) ($trx->nominal ?? 0);
                    $bayar = (float) ($trx->bayar ?? 0);
                    if ($jenis === 'tarik tunai')
                        return $nominal - $bayar;
                    if ($jenis === 'transfer')
                        return $bayar - $nominal;
                    if ($jenis === 'numpang transfer')
                        return $bayar;
                    return 0;
                });

            $pengeluaranCabang = TransaksiBank::where('tenant_id', $tenantId)
                ->whereIn('user_id', $ids)
                ->whereBetween('waktu_transaksi', [$metricStart, $metricEnd])
                ->whereNotNull('akun_pengeluaran_id')
                ->when($operSaldoId, fn($q) => $q->where('akun_pengeluaran_id', '!=', $operSaldoId))
                ->where('is_saldo_awal', 0)
                ->sum('nominal');

            $dataCabang[] = $omzetCabang - $pengeluaranCabang;
        }

        // PERBANDINGAN PERIODE SEBELUMNYA
        if ($periode === 'harian') {
            $prevStart = Carbon::parse($tanggal)->subDay()->startOfDay();
            $prevEnd = Carbon::parse($tanggal)->subDay()->endOfDay();
        } elseif ($periode === 'mingguan') {
            $prevStart = now()->subWeek()->startOfWeek();
            $prevEnd = now()->subWeek()->endOfWeek();
        } else {
            $prevStart = now()->subMonth()->startOfMonth();
            $prevEnd = now()->subMonth()->endOfMonth();
        }

        $omzetKemarin = TransaksiBank::with(['jenis_transaksi', 'bank'])
            ->where('tenant_id', $tenantId)
            ->whereIn('user_id', $userIds)
            ->whereBetween('waktu_transaksi', [$prevStart, $prevEnd])
            ->whereHas('bank', fn($q) => $q->whereRaw('LOWER(nama_bank) != "kas"'))
            ->whereHas('jenis_transaksi', fn($q) => $q->whereIn('nama_transaksi', ['Transfer', 'Tarik Tunai', 'Numpang Transfer']))
            ->get()
            ->sum(function ($trx) {
                $jenis = strtolower(trim($trx->jenis_transaksi->nama_transaksi ?? ''));
                $nominal = (float) ($trx->nominal ?? 0);
                $bayar = (float) ($trx->bayar ?? 0);
                if ($jenis === 'tarik tunai')
                    return $nominal - $bayar;
                if ($jenis === 'transfer')
                    return $bayar - $nominal;
                if ($jenis === 'numpang transfer')
                    return $bayar;
                return 0;
            });

        $pengeluaranKemarin = TransaksiBank::where('tenant_id', $tenantId)
            ->whereIn('user_id', $userIds)
            ->whereBetween('waktu_transaksi', [$prevStart, $prevEnd])
            ->whereNotNull('akun_pengeluaran_id')
            ->when($operSaldoId, fn($q) => $q->where('akun_pengeluaran_id', '!=', $operSaldoId))
            ->where('is_saldo_awal', 0)
            ->sum('nominal');

        // TRANSAKSI TERBARU
        $transaksiTerbaru = TransaksiBank::with(['jenis_transaksi', 'bank'])
            ->where('tenant_id', $tenantId)
            ->whereIn('user_id', $userIds)
            ->whereBetween('waktu_transaksi', [$metricStart, $metricEnd])
            ->whereHas('bank', fn($q) => $q->whereRaw('LOWER(nama_bank) != "kas"'))
            ->latest('waktu_transaksi')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'periode',
            'cabangId',
            'cabangs',
            'labelsOmzet',
            'dataOmzetKotor',
            'dataPengeluaranChart',
            'dataOmzet',
            'totalLabaKotor',
            'totalPengeluaran',
            'profit',
            'totalTransaksi',
            'totalTransfer',
            'totalTarikTunai',
            'totalNumpang',
            'totalSaldoKas',
            'omzetKemarin',
            'pengeluaranKemarin',
            'transaksiTerbaru',
            'labelsCabang',
            'dataCabang',
            'tenant'
        ));
    }

    /**
     * Hitung Saldo Kas dari collection transaksi
     */
    private function hitungSaldoKas($transaksis)
    {
        $runningKas = 0;

        $sorted = $transaksis->sortBy(function ($trx) {
            return sprintf('%s-%020d', Carbon::parse($trx->waktu_transaksi)->format('Y-m-d H:i:s'), $trx->id);
        });

        foreach ($sorted as $trx) {
            $bankName = strtolower(trim($trx->bank->nama_bank ?? ''));
            $jenis = strtolower(trim($trx->jenis_transaksi->nama_transaksi ?? ''));
            $nominal = (float) ($trx->nominal ?? 0);
            $bayar = (float) ($trx->bayar ?? 0);

            if ($bankName === 'kas' && in_array($jenis, ['transfer', 'numpang transfer', 'tarik tunai']))
                continue;

            if ($trx->is_saldo_awal && $bankName === 'kas') {
                $runningKas = $nominal;
                continue;
            }

            if ($bankName === 'kas') {
                if ($jenis === 'penambahan kas')
                    $runningKas += $nominal;
                elseif ($jenis === 'pengurangan kas')
                    $runningKas -= $nominal;
            } else {
                if ($jenis === 'tarik tunai')
                    $runningKas -= $bayar;
                elseif (in_array($jenis, ['transfer', 'numpang transfer']))
                    $runningKas += $bayar;
            }
        }

        return $runningKas;
    }
}