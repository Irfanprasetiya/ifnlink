@extends('layouts.app')

@section('title', 'Maintenance Mode')

@section('container')
    <div class="w-full max-w-3xl mx-auto space-y-6 pb-12 mt-5">

        {{-- Header Page --}}
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-rose-50 rounded-full blur-2xl opacity-60 pointer-events-none">
            </div>

            <div class="relative z-10 flex items-start gap-4">
                <div class="p-3 bg-rose-50 text-rose-600 rounded-xl shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                        Pengaturan Maintenance Mode
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium leading-relaxed">
                        Aktifkan mode pemeliharaan untuk menutup akses sementara dari sisi pengguna aplikasi saat Anda
                        sedang melakukan perbaikan atau pembaruan sistem.
                    </p>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl text-xs sm:text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div
                class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-2xl text-xs sm:text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Status Card --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div
                class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Status Sistem Saat Ini</h2>
                    <p class="text-[11px] text-slate-500 mt-0.5">Pantau dan kelola keadaan aplikasi Anda.</p>
                </div>

                {{-- Badge Status --}}
                <div
                    class="inline-flex items-center gap-2.5 px-4 py-2 rounded-xl text-sm font-bold shadow-sm border {{ $isDown ? 'bg-rose-50 border-rose-200 text-rose-700' : 'bg-emerald-50 border-emerald-200 text-emerald-700' }}">
                    <span class="relative flex h-3 w-3">
                        @if ($isDown)
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                        @else
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        @endif
                    </span>
                    {{ $isDown ? 'MAINTENANCE AKTIF' : 'SISTEM NORMAL' }}
                </div>
            </div>

            <div class="p-5 sm:p-6 space-y-6">

                @if ($isDown)
                    <div class="p-4 bg-rose-50/50 rounded-xl border border-rose-100">
                        <span class="block text-[11px] font-bold uppercase tracking-wider text-rose-500 mb-1">Pesan yang
                            Tampil Saat Ini</span>
                        <p class="text-sm font-medium text-rose-900">"{{ $maintenanceMessage }}"</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Form Enable --}}
                    <div class="border border-slate-200/80 rounded-xl p-5 {{ $isDown ? 'opacity-60 grayscale' : '' }}">
                        <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Tutup Akses (Aktifkan)
                        </h3>
                        <form action="{{ route('developer.maintenance.enable') }}" method="POST">
                            @csrf
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Pesan
                                Maintenance</label>
                            <input type="text" name="message" value="{{ old('message', $maintenanceMessage) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 mb-4 text-xs sm:text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition"
                                placeholder="Cth: Sistem sedang dalam perbaikan..." {{ $isDown ? 'disabled' : 'required' }}>

                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold py-2.5 px-4 rounded-xl text-xs sm:text-sm shadow-md shadow-rose-500/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ $isDown ? 'disabled' : '' }}
                                onclick="return confirm('Yakin ingin mengaktifkan Maintenance Mode? Semua user biasa akan tidak bisa login/mengakses halaman.')">
                                Aktifkan Maintenance Mode
                            </button>
                        </form>
                    </div>

                    {{-- Form Disable --}}
                    <div
                        class="border border-slate-200/80 rounded-xl p-5 {{ !$isDown ? 'opacity-60 grayscale' : 'ring-2 ring-emerald-500/20 border-emerald-300 bg-emerald-50/10' }}">
                        <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                            Buka Akses (Nonaktifkan)
                        </h3>
                        <p class="text-xs text-slate-500 mb-5 leading-relaxed">
                            Buka kembali akses sistem agar dapat digunakan secara normal oleh seluruh pengguna dan
                            pelanggan.
                        </p>

                        <form action="{{ route('developer.maintenance.disable') }}" method="POST" class="mt-auto">
                            @csrf
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-4 rounded-xl text-xs sm:text-sm shadow-md shadow-emerald-500/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ !$isDown ? 'disabled' : '' }}
                                onclick="return confirm('Yakin ingin menonaktifkan Maintenance Mode? Sistem akan kembali normal.')">
                                Nonaktifkan Maintenance Mode
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>

        {{-- Info Card --}}
        <div class="bg-blue-50/50 rounded-2xl border border-blue-100 p-5 sm:p-6 flex gap-4">
            <svg class="w-6 h-6 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h4 class="text-sm font-bold text-blue-900 mb-1.5">Informasi Penting</h4>
                <ul class="list-disc pl-4 space-y-1.5 text-xs sm:text-sm text-blue-800/80 font-medium leading-relaxed">
                    <li>Saat <strong>Maintenance Mode</strong> aktif, pengunjung akan melihat halaman Error 503 yang berisi
                        pesan perbaikan.</li>
                    <li>Akun Developer/Administrator tetap memiliki izin (<em>bypass</em>) untuk login dan mengakses semua
                        fitur demi keperluan testing.</li>
                    <li>Pastikan menyimpan pesan yang informatif agar pengguna mengetahui estimasi perbaikan selesai.</li>
                </ul>
            </div>
        </div>

    </div>
@endsection
