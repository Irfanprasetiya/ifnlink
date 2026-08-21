<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Penjualan;
use App\Models\TransaksiBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $tanggal = $request->tanggal ?? now()->toDateString();

        // Stok masuk
        $stokMasuk = BarangMasuk::whereHas('produk_konter', fn($q) => $q->where('cabang_id', $user->cabang_id))
            ->whereDate('created_at', $tanggal)
            ->sum('qty');

        // Stok keluar
        $stokKeluar = Penjualan::where('user_id', $user->id)
            ->whereDate('created_at', $tanggal)
            ->sum('qty');

        // Omset
        $omset = Penjualan::where('user_id', $user->id)
            ->whereDate('created_at', $tanggal)
            ->sum('total_harga');

        // Keuntungan
        $keuntungan = DB::table('penjualans')
            ->join('produk_konter', 'penjualans.produk_konter_id', '=', 'produk_konter.id')
            ->join('vouchers', 'produk_konter.voucher_id', '=', 'vouchers.id')
            ->where('penjualans.user_id', $user->id)
            ->whereDate('penjualans.created_at', $tanggal)
            ->select(DB::raw('SUM(penjualans.total_harga - (penjualans.qty * vouchers.harga_beli)) AS total_keuntungan'))
            ->value('total_keuntungan');

        // =====================
        // SALDO KAS (pakai query SUM(debit) - SUM(kredit) — sama dengan halaman transaksi)
        // =====================
        $bankId = 7; // ID Kas
        $tenantId = $user->tenant_id;
        $cabangId = $user->cabang_id;
        $userId = $user->id;

        $transaksis = TransaksiBank::with(['jenis_transaksi', 'bank'])
            ->whereDate('waktu_transaksi', $tanggal)
            ->where('user_id', $userId)
            ->orderBy('waktu_transaksi', 'asc')
            ->get();

        // Saldo kas dari database
        $row = TransaksiBank::where('bank_id', $bankId)
            ->where('tenant_id', $tenantId)
            ->where('cabang_id', $cabangId)
            ->where('user_id', $userId)
            ->whereDate('waktu_transaksi', $tanggal)
            ->selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
            ->first();

        $saldoAkhirKas = ($row->total_debit ?? 0) - ($row->total_kredit ?? 0);

        // Data pendukung (ringkasan)
        $saldoAwalKas = $transaksis->where('keterangan', 'Kas Awal')->sum('nominal');

        $tambahanKas = $transaksis
            ->where('jenis_transaksi.nama_transaksi', 'Penambahan Kas')
            ->where('keterangan', '!=', 'Kas Awal')
            ->sum('nominal');

        $penguranganKas = $transaksis
            ->where('jenis_transaksi.nama_transaksi', 'Pengurangan Kas')
            ->sum('nominal');

        $totalTransfer = $transaksis
            ->filter(function ($trx) {
                $bankName = strtolower($trx->bank->nama_bank ?? '');
                $jenis = strtolower($trx->jenis_transaksi->nama_transaksi ?? '');
                return $bankName !== 'kas' && in_array($jenis, ['transfer', 'numpang transfer']);
            })
            ->sum('bayar');

        $totalTarikTunai = $transaksis
            ->filter(function ($trx) {
                $bankName = strtolower($trx->bank->nama_bank ?? '');
                $jenis = strtolower($trx->jenis_transaksi->nama_transaksi ?? '');
                return $bankName !== 'kas' && $jenis === 'tarik tunai';
            })
            ->sum('bayar');

        // Saldo per bank (untuk tabel)
        $saldoPerBank = [];
        foreach ($transaksis as $trx) {
            $bankName = strtolower($trx->bank->nama_bank ?? 'unknown');
            $jenis = strtolower($trx->jenis_transaksi->nama_transaksi ?? '');
            $nominal = $trx->nominal ?? 0;
            $bayar = $trx->bayar ?? 0;

            if (!isset($saldoPerBank[$bankName])) {
                $saldoPerBank[$bankName] = $trx->is_saldo_awal ? $nominal : 0;
            }

            if (!$trx->is_saldo_awal) {
                if ($bankName === 'kas') {
                    if ($jenis === 'tarik tunai') {
                        $saldoPerBank[$bankName] -= $bayar;
                    } elseif (in_array($jenis, ['transfer', 'numpang transfer'])) {
                        $saldoPerBank[$bankName] += $bayar;
                    } elseif ($trx->jenis_transaksi->nama_transaksi === 'Penambahan Kas') {
                        $saldoPerBank[$bankName] += $nominal;
                    } elseif ($trx->jenis_transaksi->nama_transaksi === 'Pengurangan Kas') {
                        $saldoPerBank[$bankName] -= $nominal;
                    }
                } else {
                    if ($jenis === 'tarik tunai') {
                        $saldoPerBank[$bankName] += $nominal;
                    } elseif (in_array($jenis, ['transfer', 'numpang transfer'])) {
                        $saldoPerBank[$bankName] -= $nominal;
                    } elseif ($trx->jenis_transaksi->nama_transaksi === 'Penambahan Saldo') {
                        $saldoPerBank[$bankName] += $nominal;
                    } elseif ($trx->jenis_transaksi->nama_transaksi === 'Pengurangan Saldo') {
                        $saldoPerBank[$bankName] -= $nominal;
                    }
                }
            }

            $trx->saldo_akhir = $saldoPerBank[$bankName];
        }

        return view('main', compact(
            'stokMasuk',
            'stokKeluar',
            'omset',
            'keuntungan',
            'transaksis',
            'saldoAwalKas',
            'tambahanKas',
            'penguranganKas',
            'totalTransfer',
            'totalTarikTunai',
            'saldoAkhirKas',
            'saldoPerBank'
        ));
    }
}