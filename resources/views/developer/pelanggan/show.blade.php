@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('container')
    <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6">

        {{-- Notifikasi --}}
        @if (session('success'))
            <div
                class="mb-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm sm:text-base mb-4 sm:mb-6">
            <a href="{{ route('developer.pelanggan.index') }}"
                class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition">Pelanggan</a>
            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-gray-900 dark:text-white font-medium truncate">{{ $tenant->nama_toko }}</span>
        </nav>

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div class="flex items-center gap-3 sm:gap-4">
                <div
                    class="h-12 w-12 sm:h-14 sm:w-14 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                    <span
                        class="text-lg sm:text-xl font-bold text-blue-600 dark:text-blue-400">{{ strtoupper(mb_substr($tenant->nama_toko, 0, 2)) }}</span>
                </div>
                <div class="min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate">
                        {{ $tenant->nama_toko }}</h1>
                    <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400">{{ $tenant->email }}</p>
                </div>
            </div>

            @if (!$tenant->trashed())
                <div class="flex items-center gap-2 sm:gap-3">
                    <form action="{{ route('developer.pelanggan.login-as', $tenant->id_tenant) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm sm:text-base font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Login Sebagai Owner
                        </button>
                    </form>
                    <button type="button" onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm sm:text-base font-medium text-red-600 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/40 transition">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Nonaktifkan
                    </button>
                </div>
            @else
                <div class="flex items-center gap-2 sm:gap-3">
                    <form action="{{ route('developer.pelanggan.restore', $tenant->id_tenant) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm sm:text-base font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Pulihkan
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

            <div class="lg:col-span-2 space-y-4 sm:space-y-6">

                {{-- Informasi Toko --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Toko</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Nama Toko</p>
                            <p class="text-sm sm:text-base font-medium text-gray-900 dark:text-white mt-0.5">
                                {{ $tenant->nama_toko }}</p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Nama Pemilik</p>
                            <p class="text-sm sm:text-base font-medium text-gray-900 dark:text-white mt-0.5">
                                {{ $tenant->nama_pemilik }}</p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Email</p>
                            <p class="text-sm sm:text-base font-medium text-gray-900 dark:text-white mt-0.5">
                                {{ $tenant->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">No. HP</p>
                            <p class="text-sm sm:text-base font-medium text-gray-900 dark:text-white mt-0.5">
                                {{ $tenant->no_hp ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Bergabung</p>
                            <p class="text-sm sm:text-base font-medium text-gray-900 dark:text-white mt-0.5">
                                {{ $tenant->created_at ? $tenant->created_at->format('d F Y') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Update</p>
                            <p class="text-sm sm:text-base font-medium text-gray-900 dark:text-white mt-0.5">
                                {{ $tenant->updated_at ? $tenant->updated_at->diffForHumans() : '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Langganan --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Langganan</h2>
                        @if (!$tenant->trashed())
                            <button type="button"
                                onclick="document.getElementById('status-modal').classList.remove('hidden')"
                                class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">Ubah
                                Status</button>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Paket</p>
                            @if ($tenant->plan)
                                <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white mt-0.5">
                                    {{ $tenant->plan->nama_paket }}</p>
                                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Rp
                                    {{ number_format($tenant->plan->harga, 0, ',', '.') }}/bulan</p>
                            @else
                                <p class="text-sm sm:text-base text-gray-400 mt-0.5">-</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Status</p>
                            <div class="mt-1">
                                @if ($tenant->trashed())
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">🗑️
                                        Trash</span>
                                @elseif($tenant->status_langganan == 'active')
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400"><span
                                            class="w-2 h-2 rounded-full bg-emerald-500"></span> Aktif</span>
                                @elseif($tenant->status_langganan == 'trial')
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400"><span
                                            class="w-2 h-2 rounded-full bg-amber-500"></span> Trial</span>
                                @elseif($tenant->status_langganan == 'expired')
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400"><span
                                            class="w-2 h-2 rounded-full bg-red-500"></span> Expired</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">{{ ucfirst($tenant->status_langganan) }}</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                @if ($tenant->tanggal_berakhir)
                                    Masa Berlaku
                                @else
                                    Kuota User
                                @endif
                            </p>
                            <p class="text-sm sm:text-base font-medium text-gray-900 dark:text-white mt-0.5">
                                @if ($tenant->tanggal_berakhir)
                                    @php $tgl = \Carbon\Carbon::parse($tenant->tanggal_berakhir); @endphp
                                    {{ $tgl->format('d F Y') }}
                                    @if ($tgl->isPast())
                                    <span class="text-xs text-red-500 block">Sudah berakhir</span>@else<span
                                            class="text-xs text-gray-500 block">{{ $tgl->diffForHumans() }}</span>
                                    @endif
                                @else
                                    {{ $tenant->max_user ?? '-' }} User
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Daftar User --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="flex items-center justify-between p-5 sm:p-6 border-b border-gray-100 dark:border-gray-700">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Daftar User</h2>
                        <span
                            class="text-sm text-gray-500 dark:text-gray-400">{{ $tenant->users->count() }}/{{ $tenant->max_user ?? '-' }}
                            user</span>
                    </div>
                    @if ($tenant->users->count() > 0)
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($tenant->users as $user)
                                <div
                                    class="flex items-center justify-between p-4 sm:p-5 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-9 w-9 sm:h-10 sm:w-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                            <span
                                                class="text-sm font-semibold text-gray-600 dark:text-gray-400">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm sm:text-base font-medium text-gray-900 dark:text-white">
                                                {{ $user->name }}</p>
                                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">@
                                                {{ $user->username }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role == 'super_admin' ? 'bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">{{ $user->role == 'super_admin' ? 'Owner' : ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                                        <span
                                            class="w-2 h-2 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada user terdaftar.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4 sm:space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistik</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between"><span
                                class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Total User</span><span
                                class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white">{{ $tenant->users->count() }}</span>
                        </div>
                        <div class="flex justify-between"><span
                                class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Total Transaksi</span><span
                                class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white">{{ $transactionStats['total'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between"><span
                                class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Bulan Ini</span><span
                                class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white">{{ $transactionStats['this_month'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                @if (!$tenant->trashed())
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5 sm:p-6">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi Cepat</h2>
                        <div class="space-y-2">
                            <form action="{{ route('developer.pelanggan.login-as', $tenant->id_tenant) }}"
                                method="POST">@csrf<button type="submit"
                                    class="w-full text-left px-4 py-2.5 text-sm sm:text-base text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition flex items-center gap-2">🔑
                                    Login sebagai Owner</button></form>
                            <button type="button"
                                onclick="document.getElementById('status-modal').classList.remove('hidden')"
                                class="w-full text-left px-4 py-2.5 text-sm sm:text-base text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition flex items-center gap-2">🔄
                                Ubah Status</button>
                            <button type="button"
                                onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                                class="w-full text-left px-4 py-2.5 text-sm sm:text-base text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition flex items-center gap-2">🗑️
                                Nonaktifkan</button>
                        </div>
                    </div>
                @else
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5 sm:p-6">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi</h2>
                        <form action="{{ route('developer.pelanggan.restore', $tenant->id_tenant) }}" method="POST">
                            @csrf<button type="submit"
                                class="w-full text-left px-4 py-2.5 text-sm sm:text-base text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg transition mb-2">🔄
                                Pulihkan</button></form>
                        <button type="button"
                            onclick="document.getElementById('force-delete-modal').classList.remove('hidden')"
                            class="w-full text-left px-4 py-2.5 text-sm sm:text-base text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition">⚠️
                            Hapus Permanen</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL UBAH STATUS --}}
    <div id="status-modal" class="fixed inset-0 z-50 hidden" aria-modal="true">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/50 dark:bg-black/70"
                onclick="document.getElementById('status-modal').classList.add('hidden')"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 sm:p-8">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-5">Ubah Status Langganan</h3>
                <form action="/pelanggan/{{ $tenant->id_tenant }}/status" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label
                            class="block text-sm sm:text-base font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status_langganan" required
                            class="w-full text-sm sm:text-base border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white py-2.5 px-4">
                            <option value="active" {{ $tenant->status_langganan == 'active' ? 'selected' : '' }}>✅ Aktif
                            </option>
                            <option value="trial" {{ $tenant->status_langganan == 'trial' ? 'selected' : '' }}>⏳ Trial
                            </option>
                            <option value="expired" {{ $tenant->status_langganan == 'expired' ? 'selected' : '' }}>❌
                                Expired</option>
                            <option value="inactive" {{ $tenant->status_langganan == 'inactive' ? 'selected' : '' }}>🔒
                                Nonaktif</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm sm:text-base font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal
                            Berakhir</label>
                        <input type="date" name="tanggal_berakhir"
                            value="{{ $tenant->tanggal_berakhir ? \Carbon\Carbon::parse($tenant->tanggal_berakhir)->format('Y-m-d') : '' }}"
                            class="w-full text-sm sm:text-base border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white py-2.5 px-4">
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('status-modal').classList.add('hidden')"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL NONAKTIFKAN --}}
    <div id="delete-modal" class="fixed inset-0 z-50 hidden" aria-modal="true">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/50 dark:bg-black/70"
                onclick="document.getElementById('delete-modal').classList.add('hidden')"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 sm:p-8">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-2">Nonaktifkan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Data <strong>{{ $tenant->nama_toko }}</strong>
                    akan dipindahkan ke trash.</p>
                <form action="/pelanggan/{{ $tenant->id_tenant }}" method="POST">
                    @csrf @method('DELETE')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alasan</label>
                        <select name="delete_reason" required
                            class="w-full text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white py-2.5 px-4">
                            <option value="">Pilih alasan</option>
                            <option value="Permintaan pelanggan">Permintaan pelanggan</option>
                            <option value="Tidak melanjutkan berlangganan">Tidak melanjutkan berlangganan</option>
                            <option value="Pelanggaran aturan">Pelanggaran aturan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">Nonaktifkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL HAPUS PERMANEN --}}
    <div id="force-delete-modal" class="fixed inset-0 z-50 hidden" aria-modal="true">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/50 dark:bg-black/70"
                onclick="document.getElementById('force-delete-modal').classList.add('hidden')"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 sm:p-8">
                <h3 class="text-lg sm:text-xl font-semibold text-red-600 mb-2">Hapus Permanen</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Ketik <strong>{{ $tenant->nama_toko }}</strong>
                    untuk konfirmasi.</p>
                <form action="/pelanggan/{{ $tenant->id_tenant }}/force" method="POST">
                    @csrf @method('DELETE')
                    <div class="mb-4">
                        <input type="text" name="confirm_name" required placeholder="Ketik nama toko..."
                            class="w-full text-sm border border-red-200 dark:border-red-800 rounded-lg bg-red-50 dark:bg-red-900/10 text-gray-900 dark:text-white py-2.5 px-4">
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button"
                            onclick="document.getElementById('force-delete-modal').classList.add('hidden')"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">Hapus
                            Permanen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT INLINE --}}
    <script>
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('status-modal').classList.add('hidden');
                document.getElementById('delete-modal').classList.add('hidden');
                document.getElementById('force-delete-modal').classList.add('hidden');
            }
        });
    </script>
@endsection
