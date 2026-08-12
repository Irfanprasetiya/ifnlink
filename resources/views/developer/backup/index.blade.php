@extends('layouts.app')

@section('title', 'Backup & Restore')

@section('container')
    <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6 max-w-4xl mx-auto">

        {{-- Notifikasi --}}
        @if (session('success'))
            <div
                class="mb-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif

        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-6">🗄️ Backup & Restore Database</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

            {{-- Card Backup --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5 sm:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-900 dark:text-white">Download Backup</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Simpan database ke file SQL</p>
                    </div>
                </div>
                <a href="{{ route('developer.backup.download') }}"
                    class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">
                    📥 Download Backup Sekarang
                </a>
                <p class="text-xs text-gray-400 mt-2 text-center">File .sql, tidak termasuk tabel activity_logs</p>
            </div>

            {{-- Card Restore --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5 sm:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-10 w-10 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-900 dark:text-white">Restore Database</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Upload file SQL untuk restore</p>
                    </div>
                </div>
                <form action="{{ route('developer.backup.restore') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" name="backup_file" accept=".sql,.txt" required
                            class="w-full text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white py-2 px-3">
                    </div>
                    <button type="submit"
                        class="block w-full text-center bg-amber-600 hover:bg-amber-700 text-white font-medium py-2.5 rounded-lg transition"
                        onclick="return confirm('⚠️ Ini akan MENIMPA semua data saat ini! Yakin ingin melanjutkan?')">
                        🔄 Restore Database
                    </button>
                </form>
                <p class="text-xs text-red-500 mt-2 text-center">⚠️ Data saat ini akan ditimpa seluruhnya!</p>
            </div>
        </div>

        {{-- Riwayat Backup --}}
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-gray-100 dark:border-gray-700">
                <h2 class="font-semibold text-gray-900 dark:text-white">📂 Riwayat Backup</h2>
            </div>

            @if ($backups->count() > 0)
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($backups as $backup)
                        <div class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-750">
                            <div class="flex items-center gap-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $backup['filename'] }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $backup['date'] }} • {{ $backup['size'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ asset('storage/backups/' . $backup['filename']) }}"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">📥</a>
                                <form action="{{ route('developer.backup.delete', $backup['filename']) }}" method="POST"
                                    onsubmit="return confirm('Hapus file backup ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium">🗑️</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                    Belum ada file backup.
                </div>
            @endif
        </div>
    </div>
@endsection
