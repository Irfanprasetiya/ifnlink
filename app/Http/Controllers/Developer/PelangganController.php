<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::with(['plan', 'users'])
            ->withCount(['users', 'transaksi']);

        if ($request->has('tab') && $request->tab == 'trash') {
            $query->onlyTrashed();
        } elseif ($request->status) {
            $query->where('status_langganan', $request->status);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_toko', 'like', "%{$search}%")
                    ->orWhere('nama_pemilik', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->plan) {
            $query->where('plan_id', $request->plan);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $tenants = $query->orderBy('created_at', 'desc')->paginate(15);
        $plans = Plan::orderBy('harga', 'asc')->get();

        $stats = [
            'total' => Tenant::count(),
            'active' => Tenant::where('status_langganan', 'active')->count(),
            'trial' => Tenant::where('status_langganan', 'trial')->count(),
            'expired' => Tenant::where('status_langganan', 'expired')->count(),
            'trashed' => Tenant::onlyTrashed()->count(),
            'new_this_month' => Tenant::whereMonth('created_at', now()->month)->count(),
        ];

        return view('developer.pelanggan.index', compact('tenants', 'plans', 'stats'));
    }

    public function show($id)
    {
        $tenant = Tenant::withTrashed()
            ->with([
                'plan',
                'users' => function ($query) {
                    $query->orderBy('role', 'asc');
                },
                'transaksi' => function ($query) {
                    $query->latest()->limit(20);
                },
                'transaksi.jenis_transaksi'
            ])
            ->withCount(['users', 'transaksi'])
            ->findOrFail($id);

        $plans = Plan::orderBy('harga', 'asc')->get();

        $transactionStats = [
            'total' => $tenant->transaksi()->count(),
            'this_month' => $tenant->transaksi()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'total_amount' => $tenant->transaksi()->sum('nominal'),
        ];

        return view('developer.pelanggan.show', compact('tenant', 'plans', 'transactionStats'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_langganan' => 'required|string|in:active,trial,expired,suspended',
            'tanggal_berakhir' => 'nullable|date',
            'plan_id' => 'nullable|exists:plans,id',
        ]);

        $tenant = Tenant::findOrFail($id);
        $oldStatus = $tenant->status_langganan;

        $data = ['status_langganan' => $request->status_langganan];

        if ($request->filled('tanggal_berakhir')) {
            $data['tanggal_berakhir'] = $request->tanggal_berakhir;
        }

        if ($request->filled('plan_id')) {
            $data['plan_id'] = $request->plan_id;
        }

        $tenant->update($data);

        ActivityLog::log(
            'update_status',
            'pelanggan',
            "Ubah status {$tenant->nama_toko} dari {$oldStatus} ke {$request->status_langganan}",
            ['status' => $oldStatus],
            ['status' => $request->status_langganan]
        );

        return back()->with('success', 'Status berhasil diupdate!');
    }

    public function loginAs($id)
    {
        $tenant = Tenant::findOrFail($id);
        $ownerUser = $tenant->users()->where('role', 'super_admin')->first();

        if (!$ownerUser) {
            return back()->with('error', 'Owner user tidak ditemukan!');
        }

        session([
            'impersonator_id' => auth()->id(),
            'impersonator_role' => auth()->user()->role,
        ]);

        auth()->login($ownerUser);

        // Log aktivitas
        ActivityLog::log(
            'impersonate',
            'pelanggan',
            "Login sebagai owner {$tenant->nama_toko} ({$ownerUser->name})"
        );

        return redirect()->route('dashboard')
            ->with('info', "Anda login sebagai {$ownerUser->name} - Owner {$tenant->nama_toko}");
    }

    public function logoutImpersonate()
    {
        if (!session('impersonator_id')) {
            return redirect()->route('dashboard');
        }

        $developerId = session('impersonator_id');
        session()->forget(['impersonator_id', 'impersonator_role']);
        auth()->loginUsingId($developerId);

        // Log aktivitas
        ActivityLog::log('logout_impersonate', 'pelanggan', "Kembali ke akun developer");

        return redirect()->route('developer.dashboard')
            ->with('success', 'Kembali ke akun developer');
    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'delete_reason' => 'required|string|max:255',
        ]);

        $tenant = Tenant::findOrFail($id);

        DB::transaction(function () use ($tenant, $request) {
            $tenant->update([
                'deleted_by' => auth()->user()->name,
                'delete_reason' => $request->delete_reason,
                'churned_at' => now(),
                'churn_reason' => $request->delete_reason,
            ]);

            $tenant->delete();
        });

        // Log aktivitas
        ActivityLog::log(
            'delete',
            'pelanggan',
            "Nonaktifkan {$tenant->nama_toko} - Alasan: {$request->delete_reason}",
            $tenant->toArray(),
            null
        );

        return redirect()->route('developer.pelanggan.index')
            ->with('success', "{$tenant->nama_toko} berhasil dinonaktifkan!");
    }

    public function restore($id)
    {
        $tenant = Tenant::withTrashed()->findOrFail($id);

        $tenant->restore();

        // FIX: HANYA bersihkan metadata terkait penghapusan.
        // status_langganan & tanggal_berakhir TIDAK disentuh —
        // soft delete tidak pernah mengubah keduanya, jadi biarkan
        // tenant kembali persis seperti kondisi sebelum dihapus.
        $tenant->update([
            'deleted_by' => null,
            'delete_reason' => null,
            'churned_at' => null,
            'churn_reason' => null,
        ]);

        ActivityLog::log('restore', 'pelanggan', "Pulihkan {$tenant->nama_toko}");

        return back()->with('success', "{$tenant->nama_toko} berhasil dipulihkan!");
    }

    public function forceDelete(Request $request, $id)
    {
        $request->validate([
            'confirm_name' => 'required|string',
        ]);

        $tenant = Tenant::withTrashed()->findOrFail($id);

        if ($request->confirm_name !== $tenant->nama_toko) {
            return back()->with('error', 'Nama toko tidak cocok!');
        }

        $nama = $tenant->nama_toko;
        $tenant->users()->forceDelete();
        $tenant->forceDelete();

        // Log aktivitas
        ActivityLog::log('force_delete', 'pelanggan', "Hapus permanen {$nama}");

        return redirect()->route('developer.pelanggan.index')
            ->with('success', 'Pelanggan dihapus permanen!');
    }
}