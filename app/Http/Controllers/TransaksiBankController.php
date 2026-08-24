<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\JenisTransaksi;
use App\Models\TransaksiBank;
use App\Models\Bank;
use App\Models\User;
use App\Models\Cabang;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class TransaksiBankController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $cabangId = Auth::user()->cabang_id;
        $userId = $request->user_id ?? Auth::id();
        $tanggal = $request->tanggal ?? now()->toDateString();

        $banks = Bank::where(function ($q) use ($tenantId) {
            $q->where('tenant_id', $tenantId)->orWhereNull('tenant_id');
        })->orderByRaw("CASE WHEN nama_bank = 'Kas' THEN 1 ELSE 0 END, nama_bank ASC")->get();

        // Hanya transaksi HARI INI
        $transaksis = TransaksiBank::with(['jenis_transaksi', 'bank'])
            ->where('tenant_id', $tenantId)
            ->where('cabang_id', $cabangId)
            ->where('user_id', $userId)
            ->whereDate('waktu_transaksi', $tanggal)
            ->orderBy('waktu_transaksi', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Mulai dari 0 — tidak bawa saldo kemarin
        // Jam 00:00 otomatis reset karena hanya hitung transaksi tanggal hari ini
        $saldoPerBank = $this->hitungSaldoPerBank($transaksis);

        $data = $banks->map(function ($bank) use ($saldoPerBank) {
            $bankName = strtolower($bank->nama_bank);
            return [
                'id' => $bank->id,
                'nama' => $bank->nama_bank,
                'saldo' => $saldoPerBank[$bankName] ?? 0,
            ];
        });

        $cabangs = Cabang::where('tenant_id', $tenantId)->orderBy('nama_cabang')->get();
        $users = User::where('tenant_id', $tenantId)->orderBy('name')->get();

        return view('frontend.transaksi_bank.index', compact('data', 'tanggal', 'cabangs', 'users', 'userId'));
    }

    private function getSaldoSebelumTanggal($tenantId, $cabangId, $userId, $tanggal)
    {
        $transaksisSebelumnya = TransaksiBank::with(['jenis_transaksi', 'bank'])
            ->where('tenant_id', $tenantId)
            ->where('cabang_id', $cabangId)
            ->where('user_id', $userId)
            ->whereDate('waktu_transaksi', '<', $tanggal)
            ->orderBy('waktu_transaksi', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return $this->hitungSaldoPerBank($transaksisSebelumnya);
    }

    /**
     * Aturan:
     * - Penambahan/Pengurangan Saldo → hanya bank, Kas tidak kena
     * - Penambahan/Pengurangan Kas   → hanya Kas, bank tidak kena
     * - Tarik Tunai / Transfer / Numpang → bank + Kas
     */
    private function hitungSaldoPerBank($transaksis, $saldoAwal = [])
    {
        $saldoPerBank = $saldoAwal;

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

        $tenant = Tenant::with('plan')->find($tenantId);
        if ($tenant && $tenant->plan && $tenant->plan->harga == 0) {
            $todayCount = TransaksiBank::where('tenant_id', $tenantId)
                ->whereDate('waktu_transaksi', now()->toDateString())
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

        $transaksis = TransaksiBank::with('jenis_transaksi')
            ->where('bank_id', $bank_id)
            ->where('tenant_id', $tenantId)
            ->where('cabang_id', $cabangId)
            ->where('user_id', $userId)
            ->whereDate('waktu_transaksi', $tanggal)
            ->orderBy('waktu_transaksi', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $saldoSebelumnya = $this->getSaldoSebelumTanggal($tenantId, $cabangId, $userId, $tanggal);
        $bankName = strtolower(trim($bank->nama_bank));
        $runningSaldo = $saldoSebelumnya[$bankName] ?? 0;

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