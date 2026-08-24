<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\Cabang;
use App\Models\JenisTransaksi;
use App\Models\TransaksiBank;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaldoAwalController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        // ✅ Ambil cabang milik tenant ATAU data master (tenant_id NULL)
        $cabangs = Cabang::with('akuns')
            ->where(function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId)
                    ->orWhereNull('tenant_id');
            })
            ->orderByRaw("CASE WHEN tenant_id IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('nama_cabang', 'asc')
            ->get();

        $banks = Bank::where('tenant_id', $tenantId)
            ->orWhereNull('tenant_id')
            ->get();

        // Tambahkan status saldo awal per akun (khusus hari ini)
        foreach ($cabangs as $cabang) {
            foreach ($cabang->akuns as $akun) {
                $cekSaldoAwal = TransaksiBank::where('tenant_id', $tenantId)
                    ->where('cabang_id', $cabang->id)
                    ->where('user_id', $akun->id)
                    ->where('is_saldo_awal', 1)
                    ->whereDate('waktu_transaksi', now()->toDateString())
                    ->exists();

                $akun->status_saldo_awal = $cekSaldoAwal ? 'tersimpan' : 'belum_diisi';
            }
        }

        return view('transaksi_bank.saldo_awal', compact('cabangs', 'banks'));
    }

    /**
     * Endpoint AJAX: ambil user berdasarkan cabang
     */
    public function getUsersByCabang($cabangId)
    {
        $tenantId = Auth::user()->tenant_id;

        $users = User::where('tenant_id', $tenantId)
            ->where('cabang_id', $cabangId)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return response()->json($users);
    }

    /**
     * Endpoint AJAX: cek saldo awal yang sudah tersimpan HARI INI
     */
    public function cekSaldoAwal($cabang_id, $user_id)
    {
        $tenantId = Auth::user()->tenant_id;

        $existing = TransaksiBank::where('tenant_id', $tenantId)
            ->where('cabang_id', $cabang_id)
            ->where('user_id', $user_id)
            ->where('is_saldo_awal', 1)
            ->whereDate('waktu_transaksi', now()->toDateString())
            ->get(['bank_id', 'nominal'])
            ->groupBy('bank_id')
            ->map(function ($items) {
                // Ambil nominal terakhir jika ada duplikat
                return $items->last()->nominal;
            });

        return response()->json($existing);
    }

    /**
     * Simpan saldo awal — replace semua saldo awal untuk akun yang dipilih HARI INI
     */
    public function store(Request $request)
    {
        $request->validate([
            'cabang_user' => 'required',
            'saldo' => 'nullable|array',
        ]);

        [$cabangId, $userId] = explode('|', $request->cabang_user);
        $tenantId = Auth::user()->tenant_id;
        $hariIni = now()->toDateString();

        // Cari jenis transaksi "Saldo Awal" - TANPA filter tenant_id
        $jenisSaldoAwal = JenisTransaksi::where('nama_transaksi', 'Saldo Awal')->first();

        if (!$jenisSaldoAwal) {
            return back()->with('error', 'Jenis transaksi "Saldo Awal" belum ada di master data. Silakan tambahkan terlebih dahulu.');
        }

        // Ambil bank_id yang SUDAH punya saldo awal HARI INI
        $bankSudahAda = TransaksiBank::where('tenant_id', $tenantId)
            ->where('cabang_id', $cabangId)
            ->where('user_id', $userId)
            ->where('is_saldo_awal', 1)
            ->whereDate('waktu_transaksi', $hariIni)
            ->pluck('bank_id')
            ->toArray();

        $tersimpan = 0;

        foreach ($request->saldo ?? [] as $bankId => $nominal) {
            // Lewati bank yang sudah punya saldo awal hari ini
            if (in_array($bankId, $bankSudahAda)) {
                continue;
            }

            // Lewati kalau kosong / 0
            $nominalBersih = (int) preg_replace('/\D/', '', $nominal ?? '');
            if ($nominalBersih <= 0) {
                continue;
            }

            TransaksiBank::create([
                'tenant_id' => $tenantId,
                'cabang_id' => $cabangId,
                'user_id' => $userId,
                'bank_id' => $bankId,
                'jenis_transaksi_id' => $jenisSaldoAwal->id,
                'nominal' => $nominalBersih,
                'bayar' => $nominalBersih,
                'debit' => $nominalBersih,
                'kredit' => 0,
                'saldo_awal' => 0,
                'saldo_akhir' => $nominalBersih,
                'is_saldo_awal' => 1,
                'keterangan' => 'Saldo Awal',
                'waktu_transaksi' => now(),
            ]);

            $tersimpan++;
        }

        if ($tersimpan === 0) {
            return back()->with('error', 'Tidak ada saldo baru yang disimpan (mungkin semua bank sudah punya saldo awal hari ini, atau nominal kosong).');
        }

        return back()->with('success', $tersimpan . ' saldo awal berhasil disimpan.');
    }
}