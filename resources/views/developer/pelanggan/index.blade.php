@extends('layouts.app')

@section('title', 'Kelola Pelanggan')

@section('container')
    <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6">

        {{-- Page Header --}}
        <div class="mb-6 sm:mb-8">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                Kelola Pelanggan
            </h1>
            <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-500 dark:text-gray-400">
                Manajemen data pelanggan dan status langganan
            </p>
        </div>

        {{-- Statistik --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2 sm:gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-3 sm:p-4">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Total</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white mt-0.5">
                    {{ $stats['total'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-3 sm:p-4">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Aktif</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-emerald-600 mt-0.5">{{ $stats['active'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-3 sm:p-4">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Trial</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-amber-600 mt-0.5">{{ $stats['trial'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-3 sm:p-4">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Expired</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-red-600 mt-0.5">{{ $stats['expired'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-3 sm:p-4">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Trash</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-500 mt-0.5">{{ $stats['trashed'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-3 sm:p-4">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Bulan Ini</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600 mt-0.5">{{ $stats['new_this_month'] }}</p>
            </div>
        </div>

        {{-- Main Content Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">

            {{-- Tab Navigation --}}
            <div class="border-b border-gray-100 dark:border-gray-700 overflow-x-auto">
                <nav class="flex px-3 sm:px-6 gap-1 min-w-max">
                    <a href="{{ route('developer.pelanggan.index') }}"
                        class="py-3 sm:py-4 px-3 sm:px-4 text-sm sm:text-base font-medium border-b-2 transition-colors whitespace-nowrap
                          {{ !request('tab') && !request('status') ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Semua
                    </a>
                    <a href="{{ route('developer.pelanggan.index', ['status' => 'active']) }}"
                        class="py-3 sm:py-4 px-3 sm:px-4 text-sm sm:text-base font-medium border-b-2 transition-colors whitespace-nowrap
                          {{ request('status') == 'active' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Aktif
                    </a>
                    <a href="{{ route('developer.pelanggan.index', ['status' => 'trial']) }}"
                        class="py-3 sm:py-4 px-3 sm:px-4 text-sm sm:text-base font-medium border-b-2 transition-colors whitespace-nowrap
                          {{ request('status') == 'trial' ? 'border-amber-500 text-amber-600 dark:text-amber-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Trial
                    </a>
                    <a href="{{ route('developer.pelanggan.index', ['status' => 'expired']) }}"
                        class="py-3 sm:py-4 px-3 sm:px-4 text-sm sm:text-base font-medium border-b-2 transition-colors whitespace-nowrap
                          {{ request('status') == 'expired' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Expired
                    </a>
                    <a href="{{ route('developer.pelanggan.index', ['tab' => 'trash']) }}"
                        class="py-3 sm:py-4 px-3 sm:px-4 text-sm sm:text-base font-medium border-b-2 transition-colors whitespace-nowrap
                          {{ request('tab') == 'trash' ? 'border-gray-500 text-gray-600 dark:text-gray-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Trash
                        @if ($stats['trashed'] > 0)
                            <span
                                class="ml-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs px-2 py-0.5 rounded-full">
                                {{ $stats['trashed'] }}
                            </span>
                        @endif
                    </a>
                </nav>
            </div>

            {{-- Filter Bar --}}
            <div class="p-3 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                <form method="GET" class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 sm:h-5 w-4 sm:w-5 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari toko atau pemilik..."
                            class="w-full pl-9 sm:pl-10 pr-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white dark:focus:bg-gray-700 transition">
                    </div>
                    <select name="plan"
                        class="text-sm sm:text-base border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white py-2 sm:py-2.5 px-3 focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Paket</option>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}" {{ request('plan') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->nama_paket }}</option>
                        @endforeach
                    </select>
                    <div class="flex items-center gap-1 sm:gap-2">
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="text-sm sm:text-base border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white py-2 sm:py-2.5 px-2 sm:px-3 w-32 sm:w-36 focus:ring-2 focus:ring-blue-500">
                        <span class="text-gray-400 text-sm">s/d</span>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="text-sm sm:text-base border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white py-2 sm:py-2.5 px-2 sm:px-3 w-32 sm:w-36 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="px-4 sm:px-5 py-2 sm:py-2.5 text-sm sm:text-base font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">Filter</button>
                        @if (request()->anyFilled(['search', 'plan', 'date_from', 'date_to', 'status', 'tab']))
                            <a href="{{ route('developer.pelanggan.index') }}"
                                class="px-4 sm:px-5 py-2 sm:py-2.5 text-sm sm:text-base font-medium border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-750">
                            <th
                                class="py-3 sm:py-4 pl-4 sm:pl-6 pr-2 text-left text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400">
                                Toko</th>
                            <th
                                class="py-3 sm:py-4 px-2 text-left text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 hidden md:table-cell">
                                Pemilik</th>
                            <th
                                class="py-3 sm:py-4 px-2 text-left text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400">
                                Paket</th>
                            <th
                                class="py-3 sm:py-4 px-2 text-center text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 hidden sm:table-cell">
                                User</th>
                            <th
                                class="py-3 sm:py-4 px-2 text-center text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 hidden lg:table-cell">
                                Transaksi</th>
                            <th
                                class="py-3 sm:py-4 px-2 text-center text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400">
                                Status</th>
                            <th
                                class="py-3 sm:py-4 px-2 text-center text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 hidden lg:table-cell">
                                Bergabung</th>
                            <th
                                class="py-3 sm:py-4 pr-4 sm:pr-6 pl-2 text-right text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 w-10">
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse($tenants as $tenant)
                            <tr
                                class="hover:bg-gray-50/50 dark:hover:bg-gray-750 transition-colors {{ $tenant->trashed() ? 'opacity-50' : '' }}">
                                {{-- Toko --}}
                                <td class="py-3 sm:py-4 pl-4 sm:pl-6 pr-2">
                                    <div class="flex items-center gap-2 sm:gap-3">
                                        <div
                                            class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                            <span
                                                class="text-xs sm:text-sm font-semibold text-blue-600 dark:text-blue-400">{{ strtoupper(mb_substr($tenant->nama_toko, 0, 2)) }}</span>
                                        </div>
                                        <div class="min-w-0">
                                            <p
                                                class="text-sm sm:text-base font-medium text-gray-900 dark:text-white truncate max-w-[150px] sm:max-w-[200px]">
                                                {{ $tenant->nama_toko }}</p>
                                            <p
                                                class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate max-w-[150px] sm:max-w-[200px]">
                                                {{ $tenant->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                {{-- Pemilik --}}
                                <td class="py-3 sm:py-4 px-2 hidden md:table-cell">
                                    <p class="text-sm sm:text-base text-gray-900 dark:text-white">
                                        {{ $tenant->nama_pemilik }}</p>
                                </td>
                                {{-- Paket --}}
                                <td class="py-3 sm:py-4 px-2">
                                    @if ($tenant->plan)
                                        <span
                                            class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ $tenant->plan->harga == 0 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                            {{ $tenant->plan->nama_paket }}
                                        </span>
                                    @else
                                        <span class="text-xs sm:text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                {{-- User --}}
                                <td class="py-3 sm:py-4 px-2 text-center hidden sm:table-cell">
                                    <span
                                        class="text-sm sm:text-base text-gray-900 dark:text-white font-medium">{{ $tenant->users_count }}</span>
                                </td>
                                {{-- Transaksi --}}
                                <td class="py-3 sm:py-4 px-2 text-center hidden lg:table-cell">
                                    <span
                                        class="text-sm sm:text-base text-gray-900 dark:text-white">{{ $tenant->transaksi_count ?? 0 }}</span>
                                </td>
                                {{-- Status --}}
                                <td class="py-3 sm:py-4 px-2 text-center">
                                    @if ($tenant->trashed())
                                        <span
                                            class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">Trash</span>
                                    @elseif($tenant->status_langganan == 'active')
                                        <span
                                            class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400"><span
                                                class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-emerald-500"></span>
                                            Aktif</span>
                                    @elseif($tenant->status_langganan == 'trial')
                                        <span
                                            class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400"><span
                                                class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-amber-500"></span>
                                            Trial</span>
                                    @elseif($tenant->status_langganan == 'expired')
                                        <span
                                            class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400"><span
                                                class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-red-500"></span>
                                            Expired</span>
                                    @else
                                        <span
                                            class="text-xs sm:text-sm text-gray-500">{{ ucfirst($tenant->status_langganan) }}</span>
                                    @endif
                                </td>
                                {{-- Bergabung --}}
                                <td class="py-3 sm:py-4 px-2 text-center hidden lg:table-cell">
                                    <span
                                        class="text-sm text-gray-500 dark:text-gray-400">{{ $tenant->created_at ? $tenant->created_at->format('d M Y') : '-' }}</span>
                                </td>
                                {{-- Actions --}}
                                <td class="py-3 sm:py-4 pr-4 sm:pr-6 pl-2 text-right">
                                    <div class="flex items-center justify-end gap-0.5 sm:gap-1">
                                        @if (!$tenant->trashed())
                                            {{-- Detail --}}
                                            <a href="{{ route('developer.pelanggan.show', $tenant->id_tenant) }}"
                                                class="p-1.5 sm:p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors"
                                                title="Lihat Detail">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            {{-- Login As --}}
                                            <form action="{{ route('developer.pelanggan.login-as', $tenant->id_tenant) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="p-1.5 sm:p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition-colors"
                                                    title="Login Sebagai Owner">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                                    </svg>
                                                </button>
                                            </form>
                                            {{-- Edit Status --}}
                                            <button
                                                onclick="document.getElementById('status-modal-{{ $tenant->id_tenant }}').classList.remove('hidden')"
                                                class="p-1.5 sm:p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition-colors"
                                                title="Ubah Status">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            {{-- Soft Delete --}}
                                            <button
                                                onclick="confirmDelete('{{ $tenant->id_tenant }}', '{{ $tenant->nama_toko }}')"
                                                class="p-1.5 sm:p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                                                title="Nonaktifkan">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @else
                                            {{-- Restore --}}
                                            <form action="{{ route('developer.pelanggan.restore', $tenant->id_tenant) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="p-1.5 sm:p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition-colors"
                                                    title="Pulihkan">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </button>
                                            </form>
                                            {{-- Force Delete --}}
                                            <button
                                                onclick="confirmForceDelete('{{ $tenant->id_tenant }}', '{{ $tenant->nama_toko }}')"
                                                class="p-1.5 sm:p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                                                title="Hapus Permanen">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Edit Status --}}
                            @if (!$tenant->trashed())
                                <div id="status-modal-{{ $tenant->id_tenant }}" class="fixed inset-0 z-50 hidden"
                                    aria-modal="true">
                                    <div class="flex min-h-full items-center justify-center p-4">
                                        <div class="fixed inset-0 bg-gray-900/50 dark:bg-black/70"
                                            onclick="document.getElementById('status-modal-{{ $tenant->id_tenant }}').classList.add('hidden')">
                                        </div>
                                        <div
                                            class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-sm w-full p-6">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ubah
                                                Status: {{ $tenant->nama_toko }}</h3>
                                            <form action="{{ route('developer.pelanggan.status', $tenant->id_tenant) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status_langganan"
                                                    class="w-full border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 mb-4 text-sm">
                                                    <option value="active"
                                                        {{ $tenant->status_langganan == 'active' ? 'selected' : '' }}>Aktif
                                                    </option>
                                                    <option value="trial"
                                                        {{ $tenant->status_langganan == 'trial' ? 'selected' : '' }}>Trial
                                                    </option>
                                                    <option value="expired"
                                                        {{ $tenant->status_langganan == 'expired' ? 'selected' : '' }}>
                                                        Expired</option>
                                                    <option value="suspended"
                                                        {{ $tenant->status_langganan == 'suspended' ? 'selected' : '' }}>
                                                        Suspended</option>
                                                </select>
                                                <div class="flex justify-end gap-2">
                                                    <button type="button"
                                                        onclick="document.getElementById('status-modal-{{ $tenant->id_tenant }}').classList.add('hidden')"
                                                        class="px-4 py-2 text-sm font-medium border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">Batal</button>
                                                    <button type="submit"
                                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="8" class="py-16 sm:py-20 text-center">
                                    <div class="max-w-sm mx-auto">
                                        <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-gray-300 dark:text-gray-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <h3 class="mt-4 text-sm sm:text-base font-medium text-gray-900 dark:text-white">
                                            {{ request('tab') == 'trash' ? 'Trash Kosong' : 'Belum Ada Pelanggan' }}</h3>
                                        <p class="mt-1 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                            {{ request('tab') == 'trash' ? 'Tidak ada data pelanggan yang dihapus.' : 'Pelanggan baru akan muncul di sini setelah mendaftar.' }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($tenants->hasPages())
                <div class="border-t border-gray-100 dark:border-gray-700 px-4 sm:px-6 py-3 sm:py-4">
                    {{ $tenants->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Soft Delete --}}
    <div id="delete-modal" class="fixed inset-0 z-50 hidden" aria-modal="true">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/50 dark:bg-black/70" onclick="closeModal('delete-modal')"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 sm:p-8">
                <div class="text-center sm:text-left">
                    <div
                        class="mx-auto sm:mx-0 h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-2">Nonaktifkan Pelanggan
                    </h3>
                    <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mb-6">Data <span id="delete-name"
                            class="font-semibold text-gray-700 dark:text-gray-300"></span> akan dipindahkan ke trash dan
                        masih bisa dipulihkan kembali.</p>
                </div>
                <form id="delete-form" method="POST">
                    @csrf @method('DELETE')
                    <div class="mb-5">
                        <label class="block text-sm sm:text-base font-medium text-gray-700 dark:text-gray-300 mb-2">Alasan
                            penonaktifan</label>
                        <select name="delete_reason" required
                            class="w-full text-sm sm:text-base border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white py-2.5 px-4 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                            <option value="">Pilih alasan</option>
                            <option value="Permintaan pelanggan">Permintaan pelanggan</option>
                            <option value="Tidak melanjutkan berlangganan">Tidak melanjutkan berlangganan</option>
                            <option value="Pelanggaran aturan">Pelanggaran aturan</option>
                            <option value="Duplikat akun">Duplikat akun</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 justify-end">
                        <button type="button" onclick="closeModal('delete-modal')"
                            class="px-5 py-2.5 text-sm sm:text-base font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition order-2 sm:order-1">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm sm:text-base font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition order-1 sm:order-2">Ya,
                            Nonaktifkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Force Delete --}}
    <div id="force-delete-modal" class="fixed inset-0 z-50 hidden" aria-modal="true">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/50 dark:bg-black/70" onclick="closeModal('force-delete-modal')"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 sm:p-8">
                <div class="text-center sm:text-left">
                    <div
                        class="mx-auto sm:mx-0 h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-2">Hapus Permanen</h3>
                    <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mb-2">Data <span
                            id="force-delete-name" class="font-semibold text-gray-700 dark:text-gray-300"></span> akan
                        dihapus selamanya dan tidak bisa dikembalikan.</p>
                    <p class="text-sm text-red-600 dark:text-red-400 font-medium mb-6">Ketik <span
                            id="force-delete-confirm" class="font-bold"></span> untuk melanjutkan.</p>
                </div>
                <form id="force-delete-form" method="POST">
                    @csrf @method('DELETE')
                    <div class="mb-5">
                        <input type="text" name="confirm_name" required placeholder="Ketik nama toko..."
                            class="w-full text-sm sm:text-base border border-red-200 dark:border-red-800 rounded-lg bg-red-50 dark:bg-red-900/10 text-gray-900 dark:text-white py-2.5 px-4 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 justify-end">
                        <button type="button" onclick="closeModal('force-delete-modal')"
                            class="px-5 py-2.5 text-sm sm:text-base font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition order-2 sm:order-1">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm sm:text-base font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition order-1 sm:order-2">Hapus
                            Permanen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id, name) {
            document.getElementById('delete-name').textContent = name;
            document.getElementById('delete-form').action = '/developer/pelanggan/' + id;
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function confirmForceDelete(id, name) {
            document.getElementById('force-delete-name').textContent = name;
            document.getElementById('force-delete-confirm').textContent = name;
            document.getElementById('force-delete-form').action = '/developer/pelanggan/' + id + '/force';
            document.getElementById('force-delete-modal').classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal('delete-modal');
                closeModal('force-delete-modal');
            }
        });
    </script>
@endsection
