<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cabang;
use App\Models\Bank;
use App\Models\TransaksiBank;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LaporanSaldoController extends Controller
{
    public function index(Request $request)
    {
        $tenant = Auth::user()->tenant;

        if (!$tenant->plan || $tenant->plan->harga == 0) {
            return redirect()->route('upgrade')
                ->with('error', '⚠️ Fitur Laporan Saldo hanya untuk paket PRO. Pilih paket di bawah ini.');
        }

        $tenantId = $tenant->id_tenant;
        $tanggal = $request->input('tanggal', now()->toDateString());

        // ✅ Cabang: tenant + data master (Gudang)
        $cabangs = Cabang::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');
        })
            ->orderByRaw("CASE WHEN tenant_id IS NULL THEN 1 ELSE 0 END ASC") // ✅ Gudang di bawah
            ->orderBy('nama_cabang', 'asc')
            ->get();

        $banks = Bank::where(function ($q) use ($tenantId) {
            $q->where('tenant_id', $tenantId)->orWhereNull('tenant_id');
        })->get();

        $saldo = [];
        $totalSaldo = [];

        foreach ($cabangs as $cabang) {
            foreach ($banks as $bank) {
                $transaksis = TransaksiBank::with(['jenis_transaksi', 'bank', 'user'])
                    ->where('tenant_id', $tenantId)
                    ->whereHas('user', fn($q) => $q->where('cabang_id', $cabang->id))
                    ->where('bank_id', $bank->id)
                    ->whereDate('waktu_transaksi', $tanggal)
                    ->orderBy('waktu_transaksi', 'asc')
                    ->get();

                $saldoPerBank = 0;
                foreach ($transaksis as $trx) {
                    $bankName = strtolower($trx->bank->nama_bank ?? 'unknown');
                    $jenis = strtolower($trx->jenis_transaksi->nama_transaksi ?? '');
                    $nominal = $trx->nominal ?? 0;
                    $bayar = $trx->bayar ?? 0;

                    if ($trx->is_saldo_awal && $saldoPerBank == 0) {
                        $saldoPerBank = $nominal;
                    }

                    if (!$trx->is_saldo_awal) {
                        if ($bankName === 'kas') {
                            if ($jenis === 'tarik tunai') {
                                $saldoPerBank -= $bayar;
                            } elseif (in_array($jenis, ['transfer', 'numpang transfer'])) {
                                $saldoPerBank += $bayar;
                            } elseif ($trx->jenis_transaksi->nama_transaksi === 'Penambahan Kas') {
                                $saldoPerBank += $nominal;
                            } elseif ($trx->jenis_transaksi->nama_transaksi === 'Pengurangan Kas') {
                                $saldoPerBank -= $nominal;
                            }
                        } else {
                            if ($jenis === 'tarik tunai') {
                                $saldoPerBank += $nominal;
                            } elseif (in_array($jenis, ['transfer', 'numpang transfer'])) {
                                $saldoPerBank -= $nominal;
                            } elseif ($trx->jenis_transaksi->nama_transaksi === 'Penambahan Saldo') {
                                $saldoPerBank += $nominal;
                            } elseif ($trx->jenis_transaksi->nama_transaksi === 'Pengurangan Saldo') {
                                $saldoPerBank -= $nominal;
                            }
                        }
                    }

                    $trx->saldo_akhir = $saldoPerBank;
                }

                $saldo[$cabang->id][$bank->id] = $saldoPerBank;

                if (!isset($totalSaldo[$bank->id])) {
                    $totalSaldo[$bank->id] = 0;
                }
                $totalSaldo[$bank->id] += $saldoPerBank;
            }
        }

        return view('laporan_saldo.index', [
            'tanggal' => $tanggal,
            'cabangs' => $cabangs,
            'banks' => $banks,
            'saldo' => $saldo,
            'totalSaldo' => $totalSaldo,
        ]);
    }
}