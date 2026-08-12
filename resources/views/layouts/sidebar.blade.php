{{-- File: resources/views/layouts/sidebar.blade.php --}}
<aside x-cloak id="logo-sidebar"
    class="h-full bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700 flex flex-col transition-transform">

    {{-- Container untuk Menu (Bisa di-scroll) --}}
    <div class="px-3 flex-1 overflow-y-auto pb-4">
        <ul class="space-y-3 font-medium pt-4">
            @auth
                @php
                    $isLocked = Auth::user()->tenant && Auth::user()->tenant->isLocked();
                @endphp

                @if (Auth::user()->role === 'super_admin')
                    @if (session('impersonator_id'))
                        <div
                            class="px-4 py-3 mb-2 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg mx-3">
                            <p class="text-xs text-amber-700 dark:text-amber-400 font-medium">Login sebagai:</p>
                            <p class="text-sm font-semibold text-amber-800 dark:text-amber-300 truncate">
                                {{ auth()->user()->tenant->nama_toko ?? 'Unknown' }}</p>
                            <form action="{{ route('developer.logout-impersonate') }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit"
                                    class="w-full text-xs bg-amber-200 dark:bg-amber-800 text-amber-800 dark:text-amber-200 px-2 py-1 rounded">←
                                    Kembali</button>
                            </form>
                        </div>
                    @endif

                    <x-sidebar-menu label="Dashboard" route="dashboard" icon="dashboard" active="dashboard"
                        :locked="$isLocked" />

                    <x-sidebar-menu-dropdown label="Transaksi" icon="transaksi" :locked="$isLocked">
                        <x-sidebar-menu label="Transaksi Agen" route="trx-bank.index" active="trx-bank.*"
                            :locked="$isLocked" />
                    </x-sidebar-menu-dropdown>

                    <x-sidebar-menu-dropdown label="Laporan Transaksi" icon="laporan-transaksi" :locked="$isLocked">
                        <x-sidebar-menu label="Laporan Agen" route="laporan-bank.admin.index" active="laporan-bank*"
                            :locked="$isLocked" />
                    </x-sidebar-menu-dropdown>

                    <x-sidebar-menu-dropdown label="Data Master" icon="data-master" :locked="$isLocked">
                        <x-sidebar-menu label="Cabang" route="data_master.cabang.index" active="data_master.cabang*"
                            :locked="$isLocked" />
                        {{-- <x-sidebar-menu label="Jenis Transaksi" route="data_master.jenis-transaksi.index"
                            active="data_master.jenis-transaksi*" :locked="$isLocked" /> --}}
                        <x-sidebar-menu label="Daftar Bank" route="data_master.daftar_bank.index"
                            active="data_master.daftar_bank*" :locked="$isLocked" />
                    </x-sidebar-menu-dropdown>

                    @if (Auth::user()->tenant->plan && Auth::user()->tenant->plan->harga > 0)
                        <x-sidebar-menu label="Laporan Saldo" route="laporan_saldo.index" icon="laporan-saldo"
                            active="laporan_saldo*" :locked="$isLocked" />
                    @else
                        <x-sidebar-menu label="Laporan Saldo" route="upgrade" icon="laporan-saldo" badge="PRO"
                            :locked="false" />
                    @endif

                    @if (Auth::user()->tenant->plan && Auth::user()->tenant->plan->harga > 0)
                        <x-sidebar-menu label="Laba Rugi" route="laba_rugi.index" icon="laba-rugi" active="laba_rugi*"
                            :locked="$isLocked" />
                    @else
                        <x-sidebar-menu label="Laba Rugi" route="upgrade" icon="laba-rugi" badge="PRO"
                            :locked="false" />
                    @endif

                    <x-sidebar-menu label="Rekap" route="rekap.index" icon="laporan-transaksi" active="rekap*"
                        :locked="$isLocked" />

                    <x-sidebar-menu label="Status Langganan" route="status.langganan" icon="status-langganan"
                        active="status*" :locked="false" />
                @endif

                @if (Auth::user()->role === 'admin')
                    <x-sidebar-menu label="Dashboard" route="dashboard" icon="dashboard" active="dashboard"
                        :locked="$isLocked" />
                    <x-sidebar-menu label="Saldo Awal" route="saldo.index" icon="pengeluaran" active="saldo*"
                        :locked="$isLocked" />

                    <x-sidebar-menu-dropdown label="Transaksi" icon="transaksi" :locked="$isLocked">
                        <x-sidebar-menu label="Transaksi Saldo" route="trx-bank.index" active="trx-bank.*"
                            :locked="$isLocked" />
                    </x-sidebar-menu-dropdown>

                    <x-sidebar-menu-dropdown label="Laporan Transaksi" icon="laporan-transaksi" :locked="$isLocked">
                        <x-sidebar-menu label="Laporan Agen" route="laporan-bank.admin.index" active="laporan-bank*"
                            :locked="$isLocked" />
                    </x-sidebar-menu-dropdown>

                    <x-sidebar-menu-dropdown label="Data Master" icon="data-master" :locked="$isLocked">
                        <x-sidebar-menu label="Cabang" route="data_master.cabang.index" active="data_master.cabang*"
                            :locked="$isLocked" />
                        {{-- <x-sidebar-menu label="Jenis Transaksi" route="data_master.jenis-transaksi.index"
                            active="data_master.jenis-transaksi*" :locked="$isLocked" /> --}}
                        <x-sidebar-menu label="Daftar Bank" route="data_master.daftar_bank.index"
                            active="data_master.daftar_bank*" :locked="$isLocked" />
                        <x-sidebar-menu label="Akun Pengeluaran" route="data_master.akun_pengeluaran.index"
                            active="data_master.akun_pengeluaran*" :locked="$isLocked" />
                    </x-sidebar-menu-dropdown>

                    <x-sidebar-menu label="Manajemen Akun" route="users.index" icon="users" active="users*"
                        :locked="$isLocked" />
                    <x-sidebar-menu label="Rekap" route="rekap.index" icon="laporan-transaksi" active="rekap*"
                        :locked="$isLocked" />
                    <x-sidebar-menu label="Status Langganan" route="status.langganan" icon="status-langganan"
                        active="status*" :locked="false" />
                @endif

                @if (Auth::user()->role === 'developer')
                    <x-developer.dev-menu />
                @endif
            @endauth
        </ul>
    </div>

    {{-- BRANDING FOOTER SAAS (Menempel di Bawah) --}}
    <div class="mt-auto border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 p-4 shrink-0">
        <div class="flex items-center gap-3">
            {{-- Ikon/Logo Sistem --}}
            <div
                class="h-12 w-12 rounded-xl overflow-hidden shadow-md transition group-hover:scale-105 flex items-center justify-center bg-white">
                <img src="{{ asset('assets/images/logo/omzetly.png') }}" alt="Omzetly"
                    class="h-full w-full object-contain">
            </div>

            {{-- Informasi Platform --}}
            <div>
                <h5 class="text-sm font-extrabold text-gray-900 dark:text-white tracking-tight leading-none mb-1">
                    Omzetly.id</h5>
                @auth
                    @php
                        // Mengambil nama paket menggunakan safe null operator (?->) agar aman jika tenant/plan tidak ada
                        $planName = Auth::user()->tenant?->plan?->nama_paket ?? 'Free Plan';
                        if (Auth::user()->role === 'developer') {
                            $planName = 'Developer Mode';
                        }
                    @endphp
                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        v1.0 &bull; <span class="text-blue-600 dark:text-blue-400">{{ $planName }}</span>
                    </p>
                @endauth
            </div>
        </div>
    </div>
</aside>
