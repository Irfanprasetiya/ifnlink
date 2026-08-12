@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('container')
    <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">📝 Log Aktivitas</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $logs->total() }} aktivitas tercatat</p>
            </div>
            <form action="{{ route('developer.log.clear') }}" method="POST"
                onsubmit="return confirm('Yakin ingin menghapus semua log?')">
                @csrf @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-100 transition">
                    🗑️ Bersihkan Log
                </button>
            </form>
        </div>

        {{-- Filter --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 mb-4">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs text-gray-500 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Deskripsi..."
                        class="w-full text-sm border rounded-lg py-2 px-3 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Aksi</label>
                    <select name="aksi" class="text-sm border rounded-lg py-2 px-3 dark:bg-gray-700 dark:text-white">
                        <option value="">Semua</option>
                        @foreach ($aksis as $aksi)
                            <option value="{{ $aksi }}" {{ request('aksi') == $aksi ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $aksi)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Modul</label>
                    <select name="modul" class="text-sm border rounded-lg py-2 px-3 dark:bg-gray-700 dark:text-white">
                        <option value="">Semua</option>
                        @foreach ($moduls as $modul)
                            <option value="{{ $modul }}" {{ request('modul') == $modul ? 'selected' : '' }}>
                                {{ ucfirst($modul) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Dari</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="text-sm border rounded-lg py-2 px-3 w-32 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Sampai</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="text-sm border rounded-lg py-2 px-3 w-32 dark:bg-gray-700 dark:text-white">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
                    <a href="{{ route('developer.log.index') }}"
                        class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700">Reset</a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-750">
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Modul</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 text-sm">
                                <td class="py-3 px-4 text-gray-500 whitespace-nowrap">
                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3 px-4">
                                    <span
                                        class="font-medium text-gray-900 dark:text-white">{{ $log->user->name ?? 'System' }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $colors = [
                                            'login' => 'bg-blue-50 text-blue-700',
                                            'logout' => 'bg-gray-50 text-gray-700',
                                            'create' => 'bg-emerald-50 text-emerald-700',
                                            'update_status' => 'bg-amber-50 text-amber-700',
                                            'delete' => 'bg-red-50 text-red-700',
                                            'restore' => 'bg-emerald-50 text-emerald-700',
                                            'force_delete' => 'bg-red-100 text-red-800',
                                            'impersonate' => 'bg-purple-50 text-purple-700',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $colors[$log->aksi] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst(str_replace('_', ' ', $log->aksi)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-500">{{ ucfirst($log->modul) ?? '-' }}</td>
                                <td class="py-3 px-4 text-gray-900 dark:text-white">{{ $log->deskripsi }}</td>
                                <td class="py-3 px-4 text-xs text-gray-400 font-mono">{{ $log->ip_address }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-400">Belum ada log aktivitas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($logs->hasPages())
                <div class="p-4 border-t dark:border-gray-700">
                    {{ $logs->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
