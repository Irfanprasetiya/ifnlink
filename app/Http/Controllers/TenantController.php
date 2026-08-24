<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function showRegister()
    {
        // Ambil paket yang aktif dari database
        $plans = Plan::where('is_active', true)
            ->orderBy('harga', 'asc')
            ->get();

        return view('auth.register_tenant', compact('plans'));
    }



    public function storeRegister(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email',
            'no_hp' => 'nullable|string|max:15',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|min:6|confirmed',
            'plan_id' => 'required|exists:plans,id',
        ]);

        DB::beginTransaction();
        try {
            $plan = Plan::findOrFail($request->plan_id);

            $isFree = $plan->harga == 0;

            $tenant = Tenant::create([
                'nama_toko' => $request->nama_toko,
                'nama_pemilik' => $request->nama_pemilik,
                'email' => $request->email,
                'no_hp' => $request->no_hp ?? '-',
                'plan_id' => $plan->id,
                'status_langganan' => $isFree ? 'active' : 'pending',
                'tanggal_berakhir' => $isFree ? now()->addDays(14) : null,
                'max_user' => $plan->max_user ?? ($isFree ? 3 : 10),
            ]);

            // Buat User Owner
            User::create([
                'tenant_id' => $tenant->id_tenant,
                'name' => $request->nama_pemilik,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
            ]);

            DB::commit();

            // === Flow Pembayaran ===
            if ($isFree) {
                return redirect()->route('login')
                    ->with('success', 'Pendaftaran berhasil! Silakan login.');
            }

            // Paket Berbayar
            session(['pending_tenant_id' => $tenant->id_tenant]);

            return redirect()->route('checkout', $plan->id)
                ->with('info', 'Pendaftaran berhasil. Silakan selesaikan pembayaran.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}