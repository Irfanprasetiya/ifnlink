<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    /**
     * Tampilkan halaman maintenance mode
     */
    public function index()
    {
        // Cek apakah maintenance mode aktif
        $isDown = app()->isDownForMaintenance();

        // Ambil pesan maintenance dari file (kalau ada)
        $maintenanceMessage = 'Sistem sedang maintenance. Silakan kembali nanti.';

        if ($isDown) {
            $data = json_decode(file_get_contents(storage_path('framework/down')), true);
            $maintenanceMessage = $data['message'] ?? $maintenanceMessage;
        }

        return view('developer.maintenance', compact('isDown', 'maintenanceMessage'));
    }

    /**
     * Aktifkan maintenance mode
     */
    public function enable(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string|max:255',
        ]);

        if (Auth::user()->role !== 'developer') {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        if (app()->isDownForMaintenance()) {
            Artisan::call('up');
        }

        $message = $request->input('message') ?: 'Sistem sedang maintenance. Silakan kembali nanti.';

        // Gunakan secret supaya developer bisa bypass
        $secret = 'developer-omzetly.id';   // boleh diganti sendiri

        Artisan::call('down', [
            '--retry' => 60,
            '--secret' => $secret,
        ]);

        // Simpan pesan custom
        $downFile = storage_path('framework/down');
        if (file_exists($downFile)) {
            $data = json_decode(file_get_contents($downFile), true) ?? [];
            $data['message'] = $message;
            file_put_contents($downFile, json_encode($data, JSON_PRETTY_PRINT));
        }

        // Hapus session non-developer (opsional)
        $sessions = glob(storage_path('framework/sessions/*'));
        foreach ($sessions as $session) {
            $data = file_get_contents($session);
            if (!str_contains($data, '"role";s:9:"developer"')) {
                @unlink($session);
            }
        }

        \App\Models\ActivityLog::log(
            'maintenance',
            'system',
            'Maintenance mode diaktifkan oleh ' . Auth::user()->name
        );

        return back()->with('success', '✅ Maintenance mode diaktifkan. Developer bisa bypass dengan secret.');
    }

    /**
     * Nonaktifkan maintenance mode
     */
    public function disable()
    {
        // ✅ Hanya developer yang bisa
        if (Auth::user()->role !== 'developer') {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        if (!app()->isDownForMaintenance()) {
            return back()->with('warning', 'Maintenance mode sudah tidak aktif.');
        }

        // Matikan maintenance mode
        Artisan::call('up');

        // Log aktivitas
        \App\Models\ActivityLog::log('maintenance', 'system', 'Maintenance mode dinonaktifkan oleh ' . Auth::user()->name);

        return back()->with('success', '✅ Maintenance mode dinonaktifkan. Sistem kembali online.');
    }

    /**
     * Toggle maintenance mode (ON/OFF)
     */
    public function toggle(Request $request)
    {
        if (Auth::user()->role !== 'developer') {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        if (app()->isDownForMaintenance()) {
            // Matikan
            Artisan::call('up');
            $message = 'Maintenance mode dinonaktifkan.';
            $status = 'off';
        } else {
            // Nyalakan
            $msg = $request->input('message') ?: 'Sistem sedang maintenance. Silakan kembali nanti.';

            Artisan::call('down', [
                '--retry' => 60,
            ]);

            // Simpan pesan custom
            $downFile = storage_path('framework/down');
            if (file_exists($downFile)) {
                $data = json_decode(file_get_contents($downFile), true) ?? [];
                $data['message'] = $msg;
                file_put_contents($downFile, json_encode($data, JSON_PRETTY_PRINT));
            }

            $message = 'Maintenance mode diaktifkan.';
            $status = 'on';
        }

        \App\Models\ActivityLog::log(
            'maintenance',
            'system',
            "Maintenance mode {$status} oleh " . Auth::user()->name
        );

        return back()->with('success', '✅ ' . $message);
    }
}