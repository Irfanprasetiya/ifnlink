<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Bank;
use App\Models\Cabang;
use App\Models\JenisTransaksi;
use App\Models\Tenant;
use App\Models\TransaksiBank;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TransaksiBankController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $cabangId = Auth::user()->cabang_id;
        $userId = $request->user_id ?? Auth::id();
        $tanggal = $request->tanggal ?? now()->toDateString();

        // ✅ Cache banks per tenant
        $banks = Cache::remember("banks_tenant_{$tenantId}", 300, function () use ($tenantId) {
            return Bank::where(function ($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId)->orWhereNull('tenant_id');
            })
                ->orderByRaw("CASE WHEN nama_bank = 'Kas' THEN 1 ELSE 0 END, nama_bank ASC")
                ->get();
        });

        // ✅ Transaksi HARI INI — pakai whereBetween biar index kepakai
        $transaksis = TransaksiBank::with(['jenis_transaksi', 'bank'])
            ->where('tenant_id', $tenantId)
            ->where('cabang_id', $cabangId)
            ->where('user_id', $userId)
            ->whereBetween('waktu_transaksi', [
                $tanggal . ' 00:00:00',
                $tanggal . ' 23:59:59',
            ])
            ->orderBy('waktu_transaksi', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // ✅ Query sudah di-sort, langsung hitung saldo (tidak perlu sortBy ulang)
        $saldoPerBank = $this->hitungSaldoPerBank($transaksis);

        $data = $banks->map(function ($bank) use ($saldoPerBank) {
            $bankName = strtolower($bank->nama_bank);
            return [
                'id' => $bank->id,
                'nama' => $bank->nama_bank,
                'saldo' => $saldoPerBank[$bankName] ?? 0,
            ];
        });

        // ✅ Cache cabangs & users per tenant
        $cabangs = Cache::remember("cabangs_tenant_{$tenantId}", 300, function () use ($tenantId) {
            return Cabang::where('tenant_id', $tenantId)->orderBy('nama_cabang')->get();
        });

        $users = Cache::remember("users_tenant_{$tenantId}", 300, function () use ($tenantId) {
            return User::where('tenant_id', $tenantId)->orderBy('name')->get();
        });

        return view('frontend.transaksi_bank.index', compact('data', 'tanggal', 'cabangs', 'users', 'userId'));
    }

    /**
     * ✅ OPTIMASI: Pakai aggregate SQL — tidak load semua transaksi lama
     * Dari 50.000 rows → 1 aggregate query
     */
    private function getSaldoSebelumTanggal($tenantId, $cabangId, $userId, $tanggal)
    {
        // Ambil saldo per bank via SQL aggregate (debit - kredit)
        $saldos = TransaksiBank::where('tenant_id', $tenantId)
            ->where('cabang_id', $cabangId)
            ->where('user_id', $userId)
            ->where('waktu_transaksi', '<', $tanggal . ' 00:00:00')
            ->groupBy('bank_id')
            ->select('bank_id', DB::raw('SUM(debit - kredit) as saldo'))
            ->get();

        // Map bank_id → nama_bank
        $bankIds = $saldos->pluck('bank_id')->unique()->toArray();
        $bankNames = Bank::whereIn('id', $bankIds)->pluck('nama_bank', 'id');

        $saldoPerBank = [];
        foreach ($saldos as $s) {
            $bankName = strtolower(trim($bankNames[$s->bank_id] ?? 'unknown'));
            $saldoPerBank[$bankName] = (float) $s->saldo;
        }

        return $saldoPerBank;
    }

    /**
     * Hitung saldo per bank dari collection transaksi.
     *
     * Aturan:
     * - Penambahan/Pengurangan Saldo → hanya bank, Kas tidak kena
     * - Penambahan/Pengurangan Kas   → hanya Kas, bank tidak kena
     * - Tarik Tunai / Transfer / Numpang → bank + Kas
     *
     * ✅ Query sudah di-sort di SQL, tidak perlu sortBy ulang di PHP
     */
    private function hitungSaldoPerBank($transaksis, $saldoAwal = [])
    {
        $saldoPerBank = $saldoAwal;

        foreach ($transaksis as $trx) {
            $bankName = strtolower(trim($trx->bank->nama_bank ?? 'unknown'));
            $jenis = strtolower(trim($trx->jenis_transaksi->nama_transaksi ?? ''));
            $nominal = (float) ($trx->nominal ?? 0);
            $bayar = (float) ($trx->bayar ?? 0);

            // Skip pasangan Kas
            if ($bankName === 'kas' && in_array($jenis, ['transfer', 'numpang transfer', 'tarik tunai'])) {
                continue;
            }

            if (!isset($saldoPerBank[$bankName])) {
                $saldoPerBank[$bankName] = 0;
            }

            // Saldo Awal → set langsung
            if ($trx->is_saldo_awal) {
                $saldoPerBank[$bankName] = $nominal;
                continue;
            }

            // ===== Kas =====
            if ($bankName === 'kas') {
                if ($jenis === 'penambahan kas') {
                    $saldoPerBank[$bankName] += $nominal;
                } elseif ($jenis === 'pengurangan kas') {
                    $saldoPerBank[$bankName] -= $nominal;
                }
            }
            // ===== Bank selain Kas =====
            else {
                if ($jenis === 'tarik tunai') {
                    $saldoPerBank[$bankName] += $nominal;
                    if (!isset($saldoPerBank['kas'])) {
                        $saldoPerBank['kas'] = 0;
                    }
                    $saldoPerBank['kas'] -= $bayar;
                } elseif (in_array($jenis, ['transfer', 'numpang transfer'])) {
                    if ($jenis !== 'numpang transfer') {
                        $saldoPerBank[$bankName] -= $nominal;
                    }
                    if (!isset($saldoPerBank['kas'])) {
                        $saldoPerBank['kas'] = 0;
                    }
                    $saldoPerBank['kas'] += $bayar;
                } elseif ($jenis === 'penambahan saldo') {
                    // hanya bank, Kas tidak kena
                    $saldoPerBank[$bankName] += $nominal;
                } elseif ($jenis === 'pengurangan saldo') {
                    // hanya bank, Kas tidak kena
                    $saldoPerBank[$bankName] -= $nominal;
                }
            }
        }

        return $saldoPerBank;
    }

    public function create($bank_id)
    {
        $bank = Bank::findOrFail($bank_id);
        $allowed = ['Tarik Tunai', 'Transfer', 'Numpang Transfer'];
        $jenisTransaksis = JenisTransaksi::whereIn('nama_transaksi', $allowed)->get();

        return view('frontend.transaksi_bank.form', compact('bank', 'jenisTransaksis'));
    }

    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        // ✅ Cek limit paket gratis — pakai whereBetween
        $tenant = Tenant::with('plan')->find($tenantId);
        if ($tenant && $tenant->plan && $tenant->plan->harga == 0) {
            $todayCount = TransaksiBank::where('tenant_id', $tenantId)
                ->whereBetween('waktu_transaksi', [
                    now()->toDateString() . ' 00:00:00',
                    now()->toDateString() . ' 23:59:59',
                ])
                ->count();

            if ($todayCount >= 20) {
                return back()->with('error', '⚠️ Paket Gratis hanya 20 transaksi/hari. Upgrade ke PRO!')->withInput();
            }
        }

        $request->validate([
            'bank_id' => 'required',
            'jenis_transaksi_id' => 'required',
            'bayar' => 'required|numeric|min:1',
            'nominal' => 'nullable|numeric|min:1',
        ]);

        $jenis = JenisTransaksi::findOrFail($request->jenis_transaksi_id);
        $isNumpangTransfer = $jenis->nama_transaksi === 'Numpang Transfer';

        $nominal = $isNumpangTransfer ? 0 : ($request->nominal ?? 0);
        $bayar = $request->bayar;
        $bankId = $request->bank_id;
        $kasId = Bank::where('nama_bank', 'Kas')->first()?->id ?? 7;

        if (!$isNumpangTransfer) {
            if ($jenis->nama_transaksi === 'Tarik Tunai' && $nominal < $bayar) {
                return back()->with('error', 'Tarik Tunai, Nominal harus lebih besar dari Bayar')->withInput();
            }
            if ($jenis->nama_transaksi === 'Transfer' && $nominal > $bayar) {
                return back()->with('error', 'Transfer, Nominal harus lebih kecil dari Bayar')->withInput();
            }
        }

        $cabangId = Auth::user()->cabang_id;
        $userId = Auth::id();
        $waktu = now();

        $bankDebit = $bankKredit = $kasDebit = $kasKredit = 0;

        if ($jenis->nama_transaksi == 'Transfer') {
            $bankKredit = $nominal;
            $kasDebit = $bayar;
        } elseif ($jenis->nama_transaksi == 'Tarik Tunai') {
            $bankDebit = $nominal;
            $kasKredit = $bayar;
        } elseif ($jenis->nama_transaksi == 'Numpang Transfer') {
            $kasDebit = $bayar;
        }

        // ✅ Pakai transaction supaya 2 insert tidak terpisah
        DB::transaction(function () use ($bankId, $kasId, $userId, $jenis, $nominal, $bayar, $bankDebit, $bankKredit, $kasDebit, $kasKredit, $request, $waktu, $cabangId, $tenantId) {
            TransaksiBank::create([
                'bank_id' => $bankId,
                'user_id' => $userId,
                'jenis_transaksi_id' => $jenis->id,
                'nominal' => $nominal,
                'bayar' => $bayar,
                'debit' => $bankDebit,
                'kredit' => $bankKredit,
                'saldo_awal' => 0,
                'saldo_akhir' => 0,
                'keterangan' => $request->keterangan,
                'no_tujuan' => $request->no_tujuan,
                'waktu_transaksi' => $waktu,
                'cabang_id' => $cabangId,
                'tenant_id' => $tenantId,
                'is_saldo_awal' => 0,
            ]);

            TransaksiBank::create([
                'bank_id' => $kasId,
                'user_id' => $userId,
                'jenis_transaksi_id' => $jenis->id,
                'nominal' => $nominal,
                'bayar' => $bayar,
                'debit' => $kasDebit,
                'kredit' => $kasKredit,
                'saldo_awal' => 0,
                'saldo_akhir' => 0,
                'keterangan' => $request->keterangan,
                'waktu_transaksi' => $waktu,
                'cabang_id' => $cabangId,
                'tenant_id' => $tenantId,
                'is_saldo_awal' => 0,
            ]);
        });

        ActivityLog::log('create', 'transaksi', 'Transaksi baru - Rp ' . number_format($bayar));

        return redirect()->route('transaksi-bank')->with('success', 'Transaksi berhasil disimpan');
    }

    public function detail($bank_id, Request $request)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();
        $tenantId = Auth::user()->tenant_id;
        $cabangId = Auth::user()->cabang_id;
        $userId = Auth::id();

        $bank = Bank::findOrFail($bank_id);

        // ✅ Pakai whereBetween
        $transaksis = TransaksiBank::with('jenis_transaksi')
            ->where('bank_id', $bank_id)
            ->where('tenant_id', $tenantId)
            ->where('cabang_id', $cabangId)
            ->where('user_id', $userId)
            ->whereBetween('waktu_transaksi', [
                $tanggal . ' 00:00:00',
                $tanggal . ' 23:59:59',
            ])
            ->orderBy('waktu_transaksi', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $saldoSebelumnya = $this->getSaldoSebelumTanggal($tenantId, $cabangId, $userId, $tanggal);
        $bankName = strtolower(trim($bank->nama_bank));
        $runningSaldo = $saldoSebelumnya[$bankName] ?? 0;

        // ✅ Pakai method yang sama dengan index() biar konsisten
        $transaksis->transform(function ($trx) use (&$runningSaldo, $bankName) {
            $jenis = strtolower(trim($trx->jenis_transaksi->nama_transaksi ?? ''));
            $nominal = (float) ($trx->nominal ?? 0);
            $bayar = (float) ($trx->bayar ?? 0);

            if ($trx->is_saldo_awal) {
                $runningSaldo = $nominal;
            } else {
                if ($bankName === 'kas') {
                    if ($jenis === 'penambahan kas') {
                        $runningSaldo += $nominal;
                    } elseif ($jenis === 'pengurangan kas') {
                        $runningSaldo -= $nominal;
                    } elseif ($jenis === 'tarik tunai') {
                        $runningSaldo -= $bayar;
                    } elseif (in_array($jenis, ['transfer', 'numpang transfer'])) {
                        $runningSaldo += $bayar;
                    }
                } else {
                    if ($jenis === 'tarik tunai') {
                        $runningSaldo += $nominal;
                    } elseif (in_array($jenis, ['transfer', 'numpang transfer'])) {
                        if ($jenis !== 'numpang transfer') {
                            $runningSaldo -= $nominal;
                        }
                    } elseif ($jenis === 'penambahan saldo') {
                        $runningSaldo += $nominal;
                    } elseif ($jenis === 'pengurangan saldo') {
                        $runningSaldo -= $nominal;
                    }
                }
            }

            $trx->saldo_akhir_hitung = $runningSaldo;
            $trx->saldo_kas_hitung = $runningSaldo;

            return $trx;
        });

        $saldo = $runningSaldo;

        $allowed = ['Tarik Tunai', 'Transfer', 'Numpang Transfer'];
        $jenisTransaksis = JenisTransaksi::whereIn('nama_transaksi', $allowed)->get();
        $users = User::with('cabang')->get();
        $cabangs = Cabang::all();

        return view('frontend.transaksi_bank.detail', compact(
            'bank',
            'transaksis',
            'saldo',
            'jenisTransaksis',
            'users',
            'cabangs',
            'tanggal'
        ));
    }
}