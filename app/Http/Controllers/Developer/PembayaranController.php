<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with(['tenant', 'plan']);

        // Filter status
        if ($request->status && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        // ✅ Filter tanggal
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        } elseif ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter search
        if ($request->search) {
            $query->whereHas('tenant', function ($q) use ($request) {
                $q->where('nama_toko', 'like', "%{$request->search}%")
                    ->orWhere('nama_pemilik', 'like', "%{$request->search}%");
            });
        }

        $pembayarans = $query->latest()->paginate(20);

        // Stats
        $stats = [
            'total' => Pembayaran::count(),
            'success' => Pembayaran::whereIn('status', ['confirmed', 'settlement', 'capture', 'success'])->count(),
            'pending' => Pembayaran::where('status', 'pending')->count(),
            'failed' => Pembayaran::whereIn('status', ['failed', 'expired', 'expire', 'deny'])->count(),
            'cancelled' => Pembayaran::where('status', 'cancelled')->count(),
        ];

        return view('developer.pembayaran.index', compact('pembayarans', 'stats'));
    }

    public function show($id)
    {
        // ✅ Eager loading tenant dan plan
        $pembayaran = Pembayaran::with(['tenant', 'plan'])->findOrFail($id);

        return view('developer.pembayaran.show', compact('pembayaran'));
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Pembayaran::with(['tenant', 'plan']);

        // Filter status
        if ($request->status && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        // ✅ Filter tanggal
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        } elseif ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter search
        if ($request->search) {
            $query->whereHas('tenant', function ($q) use ($request) {
                $q->where('nama_toko', 'like', "%{$request->search}%")
                    ->orWhere('nama_pemilik', 'like', "%{$request->search}%");
            });
        }

        $pembayarans = $query->latest()->get();

        // Hitung total hanya untuk status sukses
        $totalJumlahSukses = $pembayarans->whereIn('status', ['confirmed', 'settlement', 'capture', 'success'])->sum('jumlah');

        // Hitung total keseluruhan
        $totalJumlahSemua = $pembayarans->sum('jumlah');

        // Hitung jumlah transaksi sukses
        $totalTransaksiSukses = $pembayarans->whereIn('status', ['confirmed', 'settlement', 'capture', 'success'])->count();

        // Generate PDF
        $pdf = Pdf::loadView('developer.pembayaran.export-pdf', compact(
            'pembayarans',
            'totalJumlahSukses',
            'totalJumlahSemua',
            'totalTransaksiSukses'
        ));

        // Set paper size landscape A4
        $pdf->setPaper('A4', 'landscape');

        // Download PDF
        return $pdf->download('riwayat-pembayaran-' . date('Y-m-d-His') . '.pdf');
    }
}