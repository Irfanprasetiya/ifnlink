@extends('layouts.app')

@section('title', 'Manajemen User')

@section('container')
    <div class="w-full max-w-full overflow-x-hidden space-y-4 sm:space-y-6 pb-12 mt-3 sm:mt-5">

        {{-- Notifikasi Limit & Kuota User (Compact di Mobile) --}}
        @php
            $tenant = Auth::user()->tenant;
            $plan = $tenant->plan;

            if ($plan && $plan->harga > 0) {
                $isPro = true;
                $maxUser = null;
            } else {
                $isPro = false;
                $maxUser = $plan->max_user ?? 1;
            }

            $currentUser = $tenant->users()->count();
            $canAddUser = $isPro ? true : $currentUser < $maxUser;
        @endphp

        @if (!$canAddUser)
            <div
                class="bg-amber-50 border border-amber-200 text-amber-800 px-4 sm:px-5 py-3 sm:py-4 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm relative overflow-hidden">
                <div class="flex items-start sm:items-center gap-2.5 sm:gap-3 relative z-10">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mt-0.5 sm:mt-0 shrink-0 text-amber-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <p class="text-[11px] sm:text-sm font-medium leading-relaxed">
                        Kuota user penuh ({{ $currentUser }}/{{ $maxUser }}). <strong class="hidden sm:inline">Upgrade
                            ke PRO untuk menambah kapasitas.</strong>
                        <span class="sm:hidden font-bold">Upgrade ke PRO!</span>
                    </p>
                </div>
                <a href="{{ route('upgrade') }}"
                    class="inline-flex items-center justify-center w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white text-[11px] sm:text-sm font-bold px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg transition-colors relative z-10 active:scale-95">
                    Upgrade Sekarang
                </a>
            </div>
        @endif

        @if ($tenant->plan && $tenant->plan->harga == 0 && $canAddUser)
            <div
                class="bg-blue-50/80 border border-blue-100 text-blue-800 px-4 sm:px-5 py-3 sm:py-4 rounded-2xl flex items-center gap-2.5 sm:gap-3 shadow-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0 text-blue-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-[11px] sm:text-sm font-medium">Paket Gratis: <strong
                        class="text-blue-900">{{ $currentUser }}/{{ $maxUser }}</strong> user. Sisa
                    <strong>{{ $maxUser - $currentUser }}</strong> slot.
                </p>
            </div>
        @endif

        {{-- Alert Sukses --}}
        @if (session('success'))
            <div id="alert-success"
                class="flex items-center justify-between bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl shadow-sm">
                <div class="flex items-center gap-2.5 sm:gap-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-[11px] sm:text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" class="text-emerald-500 hover:text-emerald-800 font-bold p-1"
                    data-dismiss-target="#alert-success">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Header & Tombol Tambah --}}
        <div
            class="flex items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-60 pointer-events-none">
            </div>

            <div class="relative z-10 w-full flex items-center justify-between">
                <div>
                    <h1 class="text-lg sm:text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                        <span
                            class="p-1.5 sm:p-2 bg-blue-50 text-blue-600 rounded-lg shrink-0 text-sm sm:text-base">👥</span>
                        Manajemen User
                    </h1>
                    <p class="text-[10px] sm:text-sm text-slate-500 mt-1 font-medium hidden sm:block">
                        Kelola daftar operator dan administrator yang memiliki akses ke sistem toko.
                    </p>
                </div>

                <div class="shrink-0">
                    @if ($canAddUser)
                        <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" type="button"
                            class="inline-flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm shadow-sm transition-all active:scale-95">
                            <span>+</span> <span class="hidden sm:inline">Tambah User</span>
                        </button>
                    @else
                        <button disabled type="button" title="Kuota user penuh"
                            class="inline-flex items-center justify-center gap-1.5 bg-slate-100 text-slate-400 cursor-not-allowed font-semibold px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm border border-slate-200">
                            <span>🔒</span> <span class="hidden sm:inline">Limit Tercapai</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Script Alert Global Modal --}}
        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = document.getElementById('crud-modal');
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                });
            </script>
        @endif

        {{-- Tabel Daftar User --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm sm:text-base font-bold text-slate-800">Daftar Pengguna Aktif</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead
                        class="bg-slate-50 border-b border-slate-200 text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 text-center w-10">No</th>
                            {{-- Disembunyikan di Mobile --}}
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 hidden sm:table-cell">Nama Pemilik</th>
                            {{-- Info User (Gabungan Nama & Username di Mobile) --}}
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5">Info User</th>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 hidden sm:table-cell">Username</th>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 text-center hidden md:table-cell">Password</th>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 text-center">Role</th>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 hidden sm:table-cell">Cabang</th>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                        @php $no = 1; @endphp
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/70">
                                <td
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-center font-semibold text-slate-500 text-[10px] sm:text-xs">
                                    {{ $no++ }}
                                </td>

                                {{-- Hidden on mobile --}}
                                <td class="px-4 sm:px-6 py-3 sm:py-4 font-medium text-slate-600 hidden sm:table-cell">
                                    {{ $user->tenant->nama_pemilik ?? '-' }}
                                </td>

                                {{-- Info User: Di mobile menampilkan Nama & Username sekaligus --}}
                                <td class="px-4 sm:px-6 py-3 sm:py-4">
                                    <div class="font-bold text-slate-900">{{ $user->name }}</div>
                                    <div class="text-[10px] text-slate-500 font-mono mt-0.5 sm:hidden">
                                        <span>@</span>{{ $user->username }}
                                    </div>
                                    <div class="text-[10px] text-slate-500 mt-0.5 sm:hidden truncate max-w-[120px]">
                                        {{ $user->cabang->nama_cabang ?? 'Pusat' }}
                                    </div>
                                </td>

                                {{-- Hidden on mobile --}}
                                <td
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-slate-600 font-medium font-mono hidden sm:table-cell">
                                    {{ $user->username }}
                                </td>

                                {{-- Hidden on mobile/tablet small --}}
                                <td
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-center text-slate-400 hidden md:table-cell tracking-widest">
                                    ••••••
                                </td>

                                <td class="px-4 sm:px-6 py-3 sm:py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2 sm:px-3 py-1 text-[9px] sm:text-xs font-bold rounded-lg sm:rounded-full uppercase tracking-wider
                                        {{ $user->role === 'super_admin' ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : '' }}
                                        {{ $user->role === 'admin' ? 'bg-purple-50 text-purple-700 border border-purple-200/60' : '' }}
                                        {{ $user->role === 'user' ? 'bg-blue-50 text-blue-700 border border-blue-200/60' : '' }}">
                                        {{ $user->role === 'super_admin' ? 'Owner' : ucfirst($user->role) }}
                                    </span>
                                </td>

                                {{-- Hidden on mobile (sudah digabung di Info User) --}}
                                <td class="px-4 sm:px-6 py-3 sm:py-4 text-slate-600 hidden sm:table-cell">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200/60">
                                        {{ $user->cabang->nama_cabang ?? '-' }}
                                        @if ($user->cabang && is_null($user->cabang->tenant_id))
                                            <span class="text-slate-400 ml-1">(Pusat)</span>
                                        @endif
                                    </span>
                                </td>

                                <td class="px-4 sm:px-6 py-3 sm:py-4">
                                    <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                                        {{-- Tombol Reset --}}
                                        <button type="button" data-modal-target="reset-modal-{{ $user->id }}"
                                            data-modal-toggle="reset-modal-{{ $user->id }}" title="Reset Password"
                                            class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg sm:rounded-xl transition active:scale-95">
                                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                            </svg>
                                        </button>

                                        {{-- Tombol Edit --}}
                                        <button type="button" data-modal-target="edit-modal-{{ $user->id }}"
                                            data-modal-toggle="edit-modal-{{ $user->id }}" title="Edit User"
                                            class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg sm:rounded-xl transition active:scale-95">
                                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2z" />
                                            </svg>
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus user ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Hapus User"
                                                class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg sm:rounded-xl transition active:scale-95">
                                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a2 2 0 012 2v0a2 2 0 01-2 2H7a2 2 0 01-2-2v0a2 2 0 012-2h10z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL RESET PASSWORD --}}
                            <div id="reset-modal-{{ $user->id }}" tabindex="-1"
                                class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0">
                                <div
                                    class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200">
                                    <div
                                        class="px-5 py-4 sm:px-6 sm:py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                        <h3 class="text-base sm:text-lg font-bold text-slate-800">Reset Password</h3>
                                        <button type="button" data-modal-toggle="reset-modal-{{ $user->id }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-rose-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <form method="POST" action="{{ route('users.reset-password', $user->id) }}"
                                        class="p-5 sm:p-6 space-y-4">
                                        @csrf
                                        <div
                                            class="bg-blue-50/50 border border-blue-100 rounded-xl p-3 mb-2 flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg shrink-0">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-800">{{ $user->name }}</p>
                                                <p class="text-[10px] text-slate-500 font-mono">
                                                    <span>@</span>{{ $user->username }}
                                                </p>
                                            </div>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Password
                                                Baru</label>
                                            <input type="text" name="password"
                                                placeholder="Kosongkan untuk auto-generate"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                                            <p class="text-[10px] sm:text-xs text-slate-400 mt-2">* Kosongkan kolom ini
                                                jika ingin di-generate otomatis oleh sistem.</p>
                                        </div>

                                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 pt-4">
                                            <button type="button" data-modal-toggle="reset-modal-{{ $user->id }}"
                                                class="w-full sm:w-auto px-4 py-3 sm:py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors active:scale-95 text-center">Batal</button>
                                            <button type="submit"
                                                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 sm:py-2.5 rounded-xl text-sm shadow-sm transition-all active:scale-95 text-center">Reset
                                                Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- MODAL EDIT USER --}}
                            <div id="edit-modal-{{ $user->id }}" tabindex="-1"
                                class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0">
                                <div
                                    class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200">
                                    <div
                                        class="px-5 py-4 sm:px-6 sm:py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                        <h3 class="text-base sm:text-lg font-bold text-slate-800">Edit Data User</h3>
                                        <button type="button" data-modal-toggle="edit-modal-{{ $user->id }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-rose-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <form method="POST" action="{{ route('users.update', $user->id) }}"
                                        class="p-5 sm:p-6 space-y-4">
                                        @csrf @method('PUT')
                                        <div>
                                            <label
                                                class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nama
                                                Lengkap</label>
                                            <input type="text" name="name" value="{{ $user->name }}" required
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Username</label>
                                            <input type="text" name="username" value="{{ $user->username }}" required
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Role
                                                Akses</label>
                                            <div class="relative">
                                                <select name="role"
                                                    class="appearance-none w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all cursor-pointer">
                                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>
                                                        User (Operator)</option>
                                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                                                        Admin</option>
                                                    <option value="super_admin"
                                                        {{ $user->role == 'super_admin' ? 'selected' : '' }}>Owner</option>
                                                </select>
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Penempatan
                                                Cabang</label>
                                            <div class="relative">
                                                <select name="cabang_id"
                                                    class="appearance-none w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all cursor-pointer">
                                                    <option value="" disabled>Pilih Cabang</option>
                                                    @foreach ($cabangs as $cabang)
                                                        @php $isGudang = is_null($cabang->tenant_id); @endphp
                                                        <option value="{{ $cabang->id }}"
                                                            {{ $user->cabang_id == $cabang->id ? 'selected' : '' }}>
                                                            {{ $cabang->nama_cabang }} {{ $isGudang ? '(Pusat)' : '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 pt-4">
                                            <button type="button" data-modal-toggle="edit-modal-{{ $user->id }}"
                                                class="w-full sm:w-auto px-4 py-3 sm:py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors active:scale-95 text-center">Batal</button>
                                            <button type="submit"
                                                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 sm:py-2.5 rounded-xl text-sm shadow-sm transition-all active:scale-95 text-center">Simpan
                                                Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8"
                                    class="text-center py-12 text-slate-400 text-xs sm:text-sm font-medium">
                                    Belum ada data user terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL TAMBAH USER UTAMA --}}
        <form method="POST" action="{{ route('users.register') }}" id="formTambahUser">
            @csrf
            <div id="crud-modal" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0">
                <div
                    class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200 my-auto">
                    <div
                        class="px-5 py-4 sm:px-6 sm:py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-base sm:text-lg font-bold text-slate-800">Registrasi User Baru</h3>
                        <button type="button" data-modal-toggle="crud-modal"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-rose-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="p-5 sm:p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                        @if ($errors->any())
                            <div
                                class="mb-2 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs sm:text-sm">
                                <ul class="list-disc pl-4 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <label
                                class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nama
                                Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Masukkan nama" required
                                class="w-full bg-slate-50 border {{ $errors->has('name') ? 'border-rose-300' : 'border-slate-200' }} rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                        </div>
                        <div>
                            <label
                                class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}"
                                placeholder="Untuk login" required
                                class="w-full bg-slate-50 border {{ $errors->has('username') ? 'border-rose-300' : 'border-slate-200' }} rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                        </div>
                        <div>
                            <label
                                class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Password</label>
                            <input type="password" name="password" placeholder="••••••••" required
                                autocomplete="new-password"
                                class="w-full bg-slate-50 border {{ $errors->has('password') ? 'border-rose-300' : 'border-slate-200' }} rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                        </div>
                        <div>
                            <label
                                class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Konfirmasi
                                Password</label>
                            <input type="password" name="password_confirmation" placeholder="••••••••" required
                                autocomplete="new-password"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                        </div>
                        <div>
                            <label
                                class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Role
                                Akses</label>
                            <div class="relative">
                                <select name="role"
                                    class="appearance-none w-full bg-slate-50 border {{ $errors->has('role') ? 'border-rose-300' : 'border-slate-200' }} rounded-xl pl-4 pr-10 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all cursor-pointer">
                                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User (Operator)
                                    </option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>
                                        Owner</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Penempatan
                                Cabang</label>
                            <div class="relative">
                                <select name="cabang_id"
                                    class="appearance-none w-full bg-slate-50 border {{ $errors->has('cabang_id') ? 'border-rose-300' : 'border-slate-200' }} rounded-xl pl-4 pr-10 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all cursor-pointer">
                                    <option value="" disabled {{ old('cabang_id') ? '' : 'selected' }}>Pilih Cabang
                                    </option>
                                    @foreach ($cabangs as $cabang)
                                        @php $isGudang = is_null($cabang->tenant_id); @endphp
                                        <option value="{{ $cabang->id }}"
                                            {{ old('cabang_id') == $cabang->id ? 'selected' : '' }}>
                                            {{ $cabang->nama_cabang }} {{ $isGudang ? '(Pusat)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 pb-2">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl text-sm shadow-sm transition-all active:scale-95 text-center">
                                Simpan User Baru
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
@endsection
