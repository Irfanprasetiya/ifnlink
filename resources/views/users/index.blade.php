@extends('layouts.app')

@section('title', 'Manajemen User')

@section('container')
    <div class="w-full max-w-full overflow-x-hidden space-y-6 pb-12">

        {{-- Notifikasi Limit & Kuota User --}}
        @php
            $tenant = Auth::user()->tenant;
            $plan = $tenant->plan;

            // Tentukan max user & status PRO
            if ($plan && $plan->harga > 0) {
                $isPro = true;
                $maxUser = null; // Unlimited
            } else {
                $isPro = false;
                $maxUser = $plan->max_user ?? 1;
            }

            $currentUser = $tenant->users()->count();
            $canAddUser = $isPro ? true : $currentUser < $maxUser;
        @endphp

        @if (!$canAddUser)
            <div
                class="bg-amber-50 border border-amber-200 text-amber-800 px-5 py-4 rounded-2xl text-xs sm:text-sm font-medium flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <span>Kuota user penuh (<strong>{{ $currentUser }}/{{ $maxUser }}</strong>). <strong>Upgrade ke
                            PRO</strong> untuk menambah kapasitas user.</span>
                </div>
                <a href="{{ route('upgrade') }}"
                    class="text-xs sm:text-sm font-bold text-amber-800 hover:text-amber-900 underline whitespace-nowrap ml-3">
                    Upgrade →
                </a>
            </div>
        @endif

        @if ($tenant->plan && $tenant->plan->harga == 0 && $canAddUser)
            <div
                class="bg-blue-50 border border-blue-100 text-blue-800 px-5 py-4 rounded-2xl text-xs sm:text-sm font-medium flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Paket Gratis: <strong>{{ $currentUser }}/{{ $maxUser }}</strong> user digunakan. Sisa
                    <strong>{{ $maxUser - $currentUser }}</strong> slot lagi.</span>
            </div>
        @endif

        {{-- Header & Tombol Tambah --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    Manajemen User & Akses
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">
                    Kelola daftar operator dan administrator yang memiliki akses ke sistem toko Anda.
                </p>
            </div>
            <div>
                @if ($canAddUser)
                    <button data-modal-target="crud-modal" data-modal-toggle="crud-modal"
                        class="inline-flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs sm:text-sm shadow-md shadow-blue-500/20 transition-all"
                        type="button">
                        <span>+</span> Tambah User
                    </button>
                @else
                    <button disabled
                        class="inline-flex items-center justify-center gap-1.5 bg-slate-200 text-slate-400 cursor-not-allowed font-semibold px-5 py-2.5 rounded-xl text-xs sm:text-sm"
                        type="button" title="Kuota user penuh">
                        <span>🔒</span> Tambah User (Penuh)
                    </button>
                @endif
            </div>
        </div>

        {{-- Main Modal: Register User --}}
        <form method="POST" action="{{ route('users.register') }}">
            @csrf
            <div id="crud-modal" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-slate-900/50 backdrop-blur-sm p-4">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8">

                        <!-- Modal header -->
                        <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                            <h3 class="text-base font-bold text-slate-900">
                                Registrasi User Baru
                            </h3>
                            <button type="button"
                                class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-rose-600 flex items-center justify-center font-bold transition"
                                data-modal-toggle="crud-modal">
                                &times;
                            </button>
                        </div>

                        <!-- Modal body -->
                        <div class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label for="name"
                                    class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nama
                                    Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition"
                                    placeholder="Masukkan nama lengkap" required>
                                <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs" />
                            </div>

                            <!-- Username -->
                            <div>
                                <label for="username"
                                    class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Username</label>
                                <input type="text" name="username" id="username" value="{{ old('username') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition"
                                    placeholder="Username untuk login" required>
                                <x-input-error :messages="$errors->get('username')" class="mt-1 text-xs" />
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password"
                                    class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Password</label>
                                <input type="password" name="password" id="password"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition"
                                    placeholder="••••••••" required autocomplete="new-password">
                                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation"
                                    class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Konfirmasi
                                    Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition"
                                    placeholder="••••••••" required autocomplete="new-password">
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
                            </div>

                            <!-- Role -->
                            <div>
                                <label for="role"
                                    class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Role
                                    Akses</label>
                                <select id="role" name="role"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User (Operator)
                                    </option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Owner
                                    </option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-1 text-xs" />
                            </div>

                            <!-- Cabang -->
                            <div>
                                <label for="cabang_id"
                                    class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Penempatan
                                    Cabang</label>
                                <select id="cabang_id" name="cabang_id"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                                    <option value="" disabled selected>Pilih Cabang</option>
                                    @foreach ($cabangs as $cabang)
                                        @php
                                            $isGudang = is_null($cabang->tenant_id);
                                        @endphp
                                        <option value="{{ $cabang->id }}"
                                            {{ old('cabang_id') == $cabang->id ? 'selected' : '' }}>
                                            {{ $cabang->nama_cabang }} {{ $isGudang ? '(Pusat)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('cabang_id')" class="mt-1 text-xs" />
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-3">
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl text-sm shadow-md shadow-blue-500/20 transition-all">
                                    Simpan User Baru
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>

        <!-- Tabel Daftar User -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Daftar Pengguna Aktif</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Total saat ini: {{ $currentUser }} dari {{ $maxUser }}
                        kuota.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th scope="col" class="px-6 py-3.5 text-center">No</th>
                            <th scope="col" class="px-6 py-3.5">Nama Pemilik</th>
                            <th scope="col" class="px-6 py-3.5">Nama Operator</th>
                            <th scope="col" class="px-6 py-3.5">Username</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Password</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Role</th>
                            <th scope="col" class="px-6 py-3.5">Cabang</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @php $no = 1; @endphp
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 text-center font-semibold text-slate-600 text-xs">
                                    {{ $no++ }}
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-700 whitespace-nowrap">
                                    {{ $user->tenant->nama_pemilik ?? '-' }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900 whitespace-nowrap">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium">
                                    {{ $user->username }}
                                </td>
                                <td class="px-6 py-4 text-center text-slate-400">
                                    ••••••
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full 
                                        {{ $user->role === 'super_admin' ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : '' }}
                                        {{ $user->role === 'admin' ? 'bg-purple-50 text-purple-700 border border-purple-200/60' : '' }}
                                        {{ $user->role === 'user' ? 'bg-blue-50 text-blue-700 border border-blue-200/60' : '' }}">
                                        {{ $user->role === 'super_admin' ? 'Owner' : ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ $user->cabang->nama_cabang ?? '-' }}
                                        @if ($user->cabang && is_null($user->cabang->tenant_id))
                                            <span class="text-slate-400 ml-1">(Pusat)</span>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex items-center justify-center gap-2">
                                    <!-- Tombol Edit Modal Toggle -->
                                    <button type="button" data-modal-target="edit-modal-{{ $user->id }}"
                                        data-modal-toggle="edit-modal-{{ $user->id }}"
                                        class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition shadow-sm"
                                        title="Edit User">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2z" />
                                        </svg>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition shadow-sm"
                                            title="Hapus User">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a2 2 0 012 2v0a2 2 0 01-2 2H7a2 2 0 01-2-2v0a2 2 0 012-2h10z" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Modal Edit per User --}}
                            <div id="edit-modal-{{ $user->id }}" tabindex="-1"
                                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
                                <div
                                    class="bg-white rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 w-full max-w-md animate-in fade-in zoom-in duration-200">
                                    <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                                        <h3 class="text-base font-bold text-slate-900">Edit Data User</h3>
                                        <button type="button" data-modal-toggle="edit-modal-{{ $user->id }}"
                                            class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-rose-600 flex items-center justify-center font-bold transition">&times;</button>
                                    </div>

                                    <form method="POST" action="{{ route('users.update', $user->id) }}"
                                        class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label
                                                class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nama
                                                Lengkap</label>
                                            <input type="text" name="name" value="{{ $user->name }}" required
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Username</label>
                                            <input type="text" name="username" value="{{ $user->username }}" required
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Role
                                                Akses</label>
                                            <select name="role"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User
                                                    (Operator)
                                                </option>
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                                                    Admin</option>
                                                <option value="super_admin"
                                                    {{ $user->role == 'super_admin' ? 'selected' : '' }}>Owner</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Penempatan
                                                Cabang</label>
                                            <select name="cabang_id"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                                                <option value="" disabled>Pilih Cabang</option>
                                                @foreach ($cabangs as $cabang)
                                                    @php
                                                        $isGudang = is_null($cabang->tenant_id);
                                                    @endphp
                                                    <option value="{{ $cabang->id }}"
                                                        {{ $user->cabang_id == $cabang->id ? 'selected' : '' }}>
                                                        {{ $cabang->nama_cabang }} {{ $isGudang ? '(Pusat)' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="flex justify-end gap-2.5 pt-3">
                                            <button type="button" data-modal-toggle="edit-modal-{{ $user->id }}"
                                                class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                                            <button type="submit"
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs shadow-md shadow-blue-500/20 transition-all">
                                                Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-slate-400 text-sm font-medium">
                                    Belum ada data user terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
