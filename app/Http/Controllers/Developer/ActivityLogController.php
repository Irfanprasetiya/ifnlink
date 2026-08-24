<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->aksi, function ($q) use ($request) {
                $q->where('aksi', $request->aksi);
            })
            ->when($request->modul, function ($q) use ($request) {
                $q->where('modul', $request->modul);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where('deskripsi', 'like', "%{$request->search}%");
            })
            ->when($request->date_from, function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->date_to, function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $aksis = ActivityLog::distinct()->pluck('aksi');
        $moduls = ActivityLog::distinct()->pluck('modul');

        return view('developer.log.index', compact('logs', 'aksis', 'moduls'));
    }

    public function clear()
    {
        ActivityLog::truncate();

        ActivityLog::log('clear_log', 'system', 'Log dibersihkan');

        return back()->with('success', 'Log berhasil dibersihkan!');
    }
}