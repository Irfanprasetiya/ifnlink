<?php

namespace App\Http\Controllers;

use App\Models\AkunPengeluaran;
use App\Models\JenisTransaksi;
use App\Models\Bank;
use App\Models\User;
use App\Models\Cabang;
use App\Models\BankSaldo;
use App\Models\TransaksiBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BankSaldoController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();
        $cabang_id = $request->cabang_id;
        $user_id = $request->user_id;
        $tenantId = Auth::user()->tenant_id;

        $banks = Bank::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)->orWhereNull('tenant_id');
        })->orderByRaw("CASE WHEN nama_bank = 'Kas' THEN 1 ELSE 0 END, nama_bank ASC")->get();

        $cabangs = Cabang::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');
        })
            ->orderByRaw("CASE WHEN tenant_id IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('nama_cabang', 'asc')
            ->get();

        $users = User::with('cabang')->where('tenant_id', $tenantId)->orderBy('name', 'asc')->get();
        $usersFilter = User::with('cabang')->where('tenant_id', $tenantId)
            ->when($cabang_id, fn($q) => $q->where('cabang_id', $cabang_id))->orderBy('name', 'asc')->get();
        $akunPengeluaran = AkunPengeluaran::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');
        })
            ->orderBy('nama_akun', 'asc')
            ->get();

        if (!$cabang_id || !$user_id) {
            $saldoKosong = $banks->pluck('nama_bank')->mapWithKeys(fn($nama) => [strtolower($nama) => 0])->toArray();
            $statusKosong = $banks->pluck('id')->mapWithKeys(fn($id) => [$id => 'Disable'])->toArray();

            return view('transaksi_bank.index', [
                'banks' => $banks,
                'transaksis' => collect(),
                'saldoTotal' => $saldoKosong,
                'statusBank' => $statusKosong,
                'tanggal' => $tanggal,
                'cabangs' => $cabangs,
                'users' => $users,
                'usersFilter' => $usersFilter,
                'akunPengeluaran' => $akunPengeluaran,
                'cabang_id' => $cabang_id,
                'user_id' => $user_id,
                'hasSaldoAwal' => false,
            ])->with('warning', 'Silakan pilih Cabang dan User terlebih dahulu.');
        }

        $transaksis = TransaksiBank::with(['jenis_transaksi', 'bank', 'user.cabang'])
            ->where('tenant_id', $tenantId)->where('cabang_id', $cabang_id)
            ->where('user_id', $user_id)->whereDate('waktu_transaksi', $tanggal)
            ->orderBy('waktu_transaksi', 'asc')->get();

        $hasSaldoAwal = TransaksiBank::where('tenant_id', $tenantId)
            ->where('cabang_id', $cabang_id)->where('user_id', $user_id)
            ->where('is_saldo_awal', 1)->exists();

        // FIX: pakai method yang sama persis dengan TransaksiBankController (user side)
        $saldoPerBank = $this->hitungSaldoPerBank($transaksis);

        $saldoTotalPerBank = [];
        $statusPerBank = [];

        foreach ($banks as $bank) {
            $bankNameLower = strtolower($bank->nama_bank);
            $saldoTotalPerBank[$bankNameLower] = $saldoPerBank[$bankNameLower] ?? 0;

            $trxList = $transaksis->where('bank_id', $bank->id);
            $cekSaldoAwal = $trxList->where('is_saldo_awal', 1)->isNotEmpty();
            $statusPerBank[$bank->id] = $cekSaldoAwal ? 'Active' : 'Disable';
        }

        return view('transaksi_bank.index', [
            'banks' => $banks,
            'transaksis' => $transaksis,
            'saldoTotal' => $saldoTotalPerBank,
            'statusBank' => $statusPerBank,
            'tanggal' => $tanggal,
            'cabangs' => $cabangs,
            'users' => $users,
            'usersFilter' => $usersFilter,
            'akunPengeluaran' => $akunPengeluaran,
            'cabang_id' => $cabang_id,
            'user_id' => $user_id,
            'hasSaldoAwal' => $hasSaldoAwal,
        ]);
    }

    /**
     * Hitung saldo per bank (termasuk Kas) — logic IDENTIK dengan
     * TransaksiBankController@index() di sisi user, supaya kedua halaman
     * selalu menampilkan angka yang sama persis.
     */
    private function hitungSaldoPerBank($transaksis)
    {
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
        }

        return $saldoPerBank;
    }

    public function store(Request $request)
    {
        // ✅ Validasi dinamis
        $rules = [
            'cabang_id' => 'required',
            'bank_id' => 'required',
            'user_id' => 'required',
            'nominal' => 'required|numeric|min:1',
            'jenis_transaksi' => 'required|in:penambahan,pengeluaran',
        ];

        // ✅ Akun pengeluaran WAJIB jika jenis = pengeluaran
        if ($request->jenis_transaksi === 'pengeluaran') {
            $rules['akun_pengeluaran_id'] = 'required|exists:akun_pengeluarans,id';
        }

        $request->validate($rules);

        $tenantId = Auth::user()->tenant_id;
        $tanggal = now()->toDateString();
        $bank = Bank::findOrFail($request->bank_id);

        $cekSaldoAwal = TransaksiBank::where('tenant_id', $tenantId)
            ->where('cabang_id', $request->cabang_id)
            ->where('bank_id', $request->bank_id)
            ->where('user_id', $request->user_id)
            ->where('is_saldo_awal', 1)
            ->exists();

        if (!$cekSaldoAwal) {
            return back()->with('error', 'User ini belum memiliki saldo awal!');
        }

        $nominal = abs($request->nominal);
        $debit = $request->jenis_transaksi === 'penambahan' ? $nominal : 0;
        $kredit = $request->jenis_transaksi === 'pengeluaran' ? $nominal : 0;

        $namaJenisTransaksi = $request->jenis_transaksi === 'pengeluaran'
            ? ($bank->nama_bank === 'Kas' ? 'Pengurangan Kas' : 'Pengurangan Saldo')
            : ($bank->nama_bank === 'Kas' ? 'Penambahan Kas' : 'Penambahan Saldo');

        $jenisTransaksi = JenisTransaksi::where('nama_transaksi', $namaJenisTransaksi)->first();
        if (!$jenisTransaksi) {
            return back()->with('error', 'Jenis transaksi tidak ditemukan!');
        }

        // ✅ Set akun pengeluaran & keterangan (TANPA cek jenis_transaksi)
        $akunPengeluaranId = $request->akun_pengeluaran_id ?: null;
        $keterangan = $request->input('keterangan', '-');

        // ✅ Fallback: kalau pengeluaran tapi akun null, tolak
        if ($request->jenis_transaksi === 'pengeluaran' && !$akunPengeluaranId) {
            return back()->with('error', '⚠️ Kategori pengeluaran wajib dipilih!')->withInput();
        }

        if ($akunPengeluaranId) {
            $akun = AkunPengeluaran::find($akunPengeluaranId);
            if ($akun) {
                $keterangan = $akun->nama_akun . ($request->keterangan ? ' - ' . $request->keterangan : '');
            }
        }

        DB::transaction(function () use ($request, $bank, $nominal, $debit, $kredit, $jenisTransaksi, $tenantId, $tanggal, $keterangan, $akunPengeluaranId) {

            $row = TransaksiBank::where('bank_id', $bank->id)
                ->where('tenant_id', $tenantId)
                ->where('cabang_id', $request->cabang_id)
                ->where('user_id', $request->user_id)
                ->whereDate('waktu_transaksi', $tanggal)
                ->selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
                ->first();

            $saldoSebelum = ($row->total_debit ?? 0) - ($row->total_kredit ?? 0);
            $saldoAkhir = $saldoSebelum + $debit - $kredit;

            TransaksiBank::create([
                'tenant_id' => $tenantId,
                'cabang_id' => $request->cabang_id,
                'user_id' => $request->user_id,
                'bank_id' => $bank->id,
                'jenis_transaksi_id' => $jenisTransaksi->id,
                'akun_pengeluaran_id' => $akunPengeluaranId,
                'nominal' => $nominal,
                'bayar' => $nominal,
                'debit' => $debit,
                'kredit' => $kredit,
                'saldo_awal' => $saldoSebelum,
                'saldo_akhir' => $saldoAkhir,
                'keterangan' => $keterangan,
                'is_saldo_awal' => 0,
                'waktu_transaksi' => now(),
            ]);
        });

        return back()->with('success', 'Transaksi berhasil disimpan.');
    }

    public function transferAntarCabang(Request $request)
    {
        $request->validate([
            'source_user_id' => 'required',
            'source_bank_id' => 'required',
            'nominal_keluar' => 'required|numeric|min:1',
            'dest_user_id' => 'required',
            'dest_bank_id' => 'required',
            'nominal_masuk' => 'nullable|numeric|min:0',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $tanggal = now()->toDateString();
        $nominalKeluar = $request->nominal_keluar;
        $nominalMasuk = $request->nominal_masuk > 0 ? $request->nominal_masuk : $nominalKeluar;

        $sourceUser = User::with('cabang')->findOrFail($request->source_user_id);
        $destUser = User::with('cabang')->findOrFail($request->dest_user_id);
        $sourceBank = Bank::findOrFail($request->source_bank_id);
        $destBank = Bank::findOrFail($request->dest_bank_id);

        // ✅ Ambil ID "Oper Saldo"
        $operSaldoId = AkunPengeluaran::where('nama_akun', 'Oper Saldo')->value('id');

        $jenisSumber = strtolower($sourceBank->nama_bank) === 'kas'
            ? JenisTransaksi::where('nama_transaksi', 'Pengurangan Kas')->first()
            : JenisTransaksi::where('nama_transaksi', 'Pengurangan Saldo')->first();

        $jenisTujuan = strtolower($destBank->nama_bank) === 'kas'
            ? JenisTransaksi::where('nama_transaksi', 'Penambahan Kas')->first()
            : JenisTransaksi::where('nama_transaksi', 'Penambahan Saldo')->first();

        if (!$jenisSumber || !$jenisTujuan) {
            return back()->with('error', 'Jenis transaksi tidak ditemukan!')->withInput();
        }

        $namaSumber = "{$sourceUser->name} ({$sourceUser->cabang->nama_cabang}) - {$sourceBank->nama_bank}";
        $namaTujuan = "{$destUser->name} ({$destUser->cabang->nama_cabang}) - {$destBank->nama_bank}";

        DB::transaction(function () use ($request, $tenantId, $tanggal, $nominalKeluar, $nominalMasuk, $sourceUser, $destUser, $sourceBank, $destBank, $jenisSumber, $jenisTujuan, $namaSumber, $namaTujuan, $operSaldoId) {

            // =============================================
            // SUMBER (Uang Keluar)
            // =============================================
            $rowSumber = TransaksiBank::where('bank_id', $sourceBank->id)
                ->where('tenant_id', $tenantId)
                ->where('cabang_id', $sourceUser->cabang_id)
                ->where('user_id', $sourceUser->id)
                ->whereDate('waktu_transaksi', $tanggal)
                ->selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
                ->first();
            $saldoSumberSebelum = ($rowSumber->total_debit ?? 0) - ($rowSumber->total_kredit ?? 0);

            TransaksiBank::create([
                'bank_id' => $sourceBank->id,
                'user_id' => $sourceUser->id,
                'cabang_id' => $sourceUser->cabang_id,
                'tenant_id' => $tenantId,
                'jenis_transaksi_id' => $jenisSumber->id,
                'akun_pengeluaran_id' => $operSaldoId, // ✅ Set ke Oper Saldo
                'nominal' => $nominalKeluar,
                'bayar' => $nominalKeluar,
                'debit' => 0,
                'kredit' => $nominalKeluar,
                'saldo_awal' => $saldoSumberSebelum,
                'saldo_akhir' => $saldoSumberSebelum - $nominalKeluar,
                'is_saldo_awal' => 0,
                'waktu_transaksi' => now(),
                'keterangan' => "Oper ke {$namaTujuan}" . ($request->keterangan ? ' - ' . $request->keterangan : ''),
            ]);

            // =============================================
            // TUJUAN (Uang Masuk)
            // =============================================
            $rowTujuan = TransaksiBank::where('bank_id', $destBank->id)
                ->where('tenant_id', $tenantId)
                ->where('cabang_id', $destUser->cabang_id)
                ->where('user_id', $destUser->id)
                ->whereDate('waktu_transaksi', $tanggal)
                ->selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
                ->first();
            $saldoTujuanSebelum = ($rowTujuan->total_debit ?? 0) - ($rowTujuan->total_kredit ?? 0);

            TransaksiBank::create([
                'bank_id' => $destBank->id,
                'user_id' => $destUser->id,
                'cabang_id' => $destUser->cabang_id,
                'tenant_id' => $tenantId,
                'jenis_transaksi_id' => $jenisTujuan->id,
                'akun_pengeluaran_id' => $operSaldoId, // ✅ Set ke Oper Saldo
                'nominal' => $nominalMasuk,
                'bayar' => $nominalMasuk,
                'debit' => $nominalMasuk,
                'kredit' => 0,
                'saldo_awal' => $saldoTujuanSebelum,
                'saldo_akhir' => $saldoTujuanSebelum + $nominalMasuk,
                'is_saldo_awal' => 0,
                'waktu_transaksi' => now(),
                'keterangan' => "Oper dari {$namaSumber}" . ($request->keterangan ? ' - ' . $request->keterangan : ''),
            ]);
        });

        return redirect()->route('trx-bank.index')->with('success', "Oper dari {$namaSumber} ke {$namaTujuan} berhasil.");
    }

    public function cekSaldoAwal(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $cek = TransaksiBank::where('tenant_id', $tenantId)
            ->where('cabang_id', $request->cabang_id)->where('bank_id', $request->bank_id)
            ->where('user_id', $request->user_id)->where('is_saldo_awal', 1)
            ->whereDate('waktu_transaksi', now()->toDateString())->exists();
        return response()->json(['status' => $cek]);
    }

    public function destroy(BankSaldo $bankSaldo)
    {
        $bankSaldo->delete();
        return back()->with('success', 'Saldo berhasil dihapus.');
    }

    public function getUsersByCabang($cabang_id)
    {
        $tenantId = Auth::user()->tenant_id;
        $users = User::where('cabang_id', $cabang_id)
            ->where('tenant_id', $tenantId)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);
        return response()->json($users);
    }
}