<?php

namespace App\Http\Controllers;

use App\Models\StokOpname;
use App\Models\StokHistory;
use App\Models\ProdukKonter;
use App\Models\Voucher;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StokOpnameController extends Controller
{
    /**
     * Daftar opname
     */
    public function index()
    {
        $user = Auth::user();

        $opnames = StokOpname::with(['cabang', 'user'])
            ->where('tenant_id', $user->tenant_id)
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('stok_opname.index', compact('opnames'));
    }

    /**
     * Form opname
     */
    public function create()
    {
        $user = Auth::user();

        $cabangs = Cabang::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->get();

        return view('stok_opname.create', compact('cabangs'));
    }

    /**
     * Simpan opname
     */
    public function store(Request $request)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabangs,id',
            'tanggal_opname' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.voucher_id' => 'required|exists:vouchers,id',
            'items.*.stok_aktual' => 'required|integer|min:0',
            'catatan' => 'nullable|string',
        ]);

        $user = Auth::user();

        DB::beginTransaction();

        try {
            $opname = StokOpname::create([
                'kode_opname' => 'OPN-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'tenant_id' => $user->tenant_id,
                'cabang_id' => $request->cabang_id,
                'user_id' => $user->id,
                'tanggal_opname' => $request->tanggal_opname,
                'catatan' => $request->catatan,
            ]);

            foreach ($request->items as $item) {
                $produk = ProdukKonter::where('voucher_id', $item['voucher_id'])
                    ->where('cabang_id', $request->cabang_id)
                    ->first();

                $stokLama = $produk ? $produk->stok : 0;
                $stokAktual = $item['stok_aktual'];

                if ($produk) {
                    $produk->update(['stok' => $stokAktual]);
                } else {
                    ProdukKonter::create([
                        'voucher_id' => $item['voucher_id'],
                        'cabang_id' => $request->cabang_id,
                        'tenant_id' => $user->tenant_id,
                        'stok' => $stokAktual,
                    ]);
                }

                // Catat history opname
                StokHistory::create([
                    'tenant_id' => $user->tenant_id,
                    'cabang_id' => $request->cabang_id,
                    'voucher_id' => $item['voucher_id'],
                    'jenis' => 'opname',
                    'qty' => $stokAktual, // ✅ Stok fisik
                    'keterangan' => "Opname #{$opname->kode_opname} | Stok lama: {$stokLama} | Stok baru: {$stokAktual}",
                    'user_id' => $user->id,
                ]);
            }

            DB::commit();

            return redirect()->route('data_master.stok_opname.index')
                ->with('success', 'Stok opname berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * Get produk by cabang (AJAX)
     */
    public function getProdukByCabang($cabangId)
    {
        $user = Auth::user();

        $produks = ProdukKonter::with('voucher')
            ->where('cabang_id', $cabangId)
            ->where('tenant_id', $user->tenant_id)
            ->get();

        return response()->json($produks);
    }

    /**
     * Detail opname
     */
    public function show($kode)
    {
        $user = Auth::user();

        $opname = StokOpname::with(['cabang', 'user'])
            ->where('tenant_id', $user->tenant_id)
            ->where('kode_opname', $kode) // ✅ Cari pakai kode
            ->firstOrFail();

        $details = StokHistory::with('voucher')
            ->where('keterangan', 'like', '%Opname #' . $opname->kode_opname . '%')
            ->get();

        return view('stok_opname.show', compact('opname', 'details'));
    }

    /**
     * PDF opname
     */
    public function pdf($kode)
    {
        $user = Auth::user();

        $opname = StokOpname::with(['cabang', 'user'])
            ->where('tenant_id', $user->tenant_id)
            ->where('kode_opname', $kode) // ✅ Cari pakai kode
            ->firstOrFail();

        $details = StokHistory::with('voucher')
            ->where('keterangan', 'like', '%Opname #' . $opname->kode_opname . '%')
            ->get();

        $pdf = \PDF::loadView('stok_opname.pdf', compact('opname', 'details'));

        return $pdf->download('opname-' . $opname->kode_opname . '.pdf');
    }
}