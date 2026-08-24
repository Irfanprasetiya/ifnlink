<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\AkunPengeluaran;
use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\Cabang;
use App\Models\User;
use App\Models\TransaksiBank;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LabaRugiExport;

class LabaRugiController extends Controller
{
    public function index(Request $request)
    {
        $tenant = Auth::user()->tenant;

        if (!$tenant->plan || $tenant->plan->harga == 0) {
            return redirect()->route('upgrade')
                ->with('error', '⚠️ Fitur Laba/Rugi hanya untuk paket PRO. Pilih paket di bawah ini.');
        }

        $data = $this->getLabaRugiData($request);
        return view('laba_rugi.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getLabaRugiData($request);
        $pdf = Pdf::loadView('laba_rugi.pdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laba-rugi-' . $data['tanggalAwal'] . '-sd-' . $data['tanggalAkhir'] . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getLabaRugiData($request);
        return Excel::download(new LabaRugiExport($data), 'laba-rugi-' . $data['tanggalAwal'] . '-sd-' . $data['tanggalAkhir'] . '.xlsx');
    }

    private function getLabaRugiData(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $tanggalAwal = $request->tanggal_awal ?? now()->startOfMonth()->toDateString();
        $tanggalAkhir = $request->tanggal_akhir ?? now()->toDateString();
        $cabang_id = $request->cabang_id;

        // ✅ Cabang: tenant + data master (Gudang)
        $allCabangs = Cabang::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');
        })
            ->orderByRaw("CASE WHEN tenant_id IS NULL THEN 1 ELSE 0 END ASC")
            ->orderBy('nama_cabang', 'asc')
            ->get();

        $cabangs = $cabang_id
            ? Cabang::where(function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId)
                    ->orWhereNull('tenant_id');
            })->where('id', $cabang_id)->get()
            : $allCabangs;

        // ✅ Ambil ID "Oper Saldo" untuk di-skip dari pengeluaran
        $operSaldoId = AkunPengeluaran::where('nama_akun', 'Oper Saldo')->value('id');

        $labaKotor = [];
        $pengeluaran = [];
        $labaBersih = [];

        foreach ($cabangs as $cabang) {
            $users = User::where('tenant_id', $tenantId)->where('cabang_id', $cabang->id)->get();
            $totalLabaKotorCabang = 0;

            // =============================================
            // HITUNG LABA KOTOR (Omzet per cabang)
            // =============================================
            foreach ($users as $user) {
                $trxList = TransaksiBank::with(['jenis_transaksi', 'bank'])
                    ->where('tenant_id', $tenantId)
                    ->where('user_id', $user->id)
                    ->whereBetween('waktu_transaksi', [
                        \Carbon\Carbon::parse($tanggalAwal)->startOfDay(),
                        \Carbon\Carbon::parse($tanggalAkhir)->endOfDay()
                    ])
                    ->get();

                foreach ($trxList as $trx) {
                    $bankName = strtolower($trx->bank->nama_bank ?? '');
                    $jenis = strtolower($trx->jenis_transaksi->nama_transaksi ?? '');

                    // Skip Kas & transaksi administratif
                    if ($bankName === 'kas' || in_array($jenis, ['penambahan saldo', 'penambahan kas', 'saldo awal', 'pengurangan kas', 'pengurangan saldo'])) {
                        continue;
                    }

                    $nominal = $trx->nominal ?? 0;
                    $bayar = $trx->bayar ?? 0;

                    if ($jenis === 'tarik tunai') {
                        $totalLabaKotorCabang += ($nominal - $bayar);
                    } elseif ($jenis === 'transfer') {
                        $totalLabaKotorCabang += ($bayar - $nominal);
                    } elseif ($jenis === 'numpang transfer') {
                        $totalLabaKotorCabang += $bayar;
                    }
                }
            }

            $labaKotor[$cabang->id] = $totalLabaKotorCabang;

            // =============================================
            // HITUNG PENGELUARAN OPERASIONAL (Semua Bank + Kas)
            // =============================================
            $pengeluaranOperasional = TransaksiBank::where('tenant_id', $tenantId)
                ->whereNotNull('akun_pengeluaran_id')           // ✅ Punya kategori pengeluaran
                ->when($operSaldoId, function ($q) use ($operSaldoId) {
                    return $q->where('akun_pengeluaran_id', '!=', $operSaldoId); // ✅ Skip Oper Saldo
                })
                ->where('cabang_id', $cabang->id)
                ->whereBetween('waktu_transaksi', [
                    \Carbon\Carbon::parse($tanggalAwal)->startOfDay(),
                    \Carbon\Carbon::parse($tanggalAkhir)->endOfDay()
                ])
                ->sum('nominal');

            $pengeluaran[$cabang->id] = $pengeluaranOperasional;

            // =============================================
            // HITUNG LABA BERSIH
            // =============================================
            $labaBersih[$cabang->id] = $labaKotor[$cabang->id] - $pengeluaran[$cabang->id];
        }

        // =============================================
        // TOTAL KESELURUHAN
        // =============================================
        $totalLabaKotor = array_sum($labaKotor);
        $totalPengeluaran = array_sum($pengeluaran);
        $totalLabaBersih = array_sum($labaBersih);

        return [
            'cabangs' => $cabangs,
            'allCabangs' => $allCabangs,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'cabang_id' => $cabang_id,
            'labaKotor' => $labaKotor,
            'pengeluaran' => $pengeluaran,
            'labaBersih' => $labaBersih,
            'totalLabaKotor' => $totalLabaKotor,
            'totalPengeluaran' => $totalPengeluaran,
            'totalLabaBersih' => $totalLabaBersih,
        ];
    }
}