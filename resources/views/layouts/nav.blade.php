{{-- File: resources/views/layouts/nav.blade.php --}}
<nav class="fixed top-0 z-50 w-full bg-blue-600 text-white border-b border-blue-700 shadow-md transition-all">
    <div class="px-4 py-3 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-4">

            {{-- Kiri: Tombol Sidebar & Branding --}}
            <div class="flex items-center justify-start rtl:justify-end gap-3 sm:gap-4 min-w-0">
                <button x-on:click="sidebarOpen = !sidebarOpen"
                    class="inline-flex items-center justify-center p-2 text-blue-100 rounded-lg hover:bg-blue-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-400 transition-colors shrink-0">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z" />
                    </svg>
                </button>

                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 min-w-0">
                    <span
                        class="self-center font-extrabold tracking-tight select-none cursor-default text-white text-lg sm:text-xl md:text-2xl lg:text-2xl truncate">
                        @auth
                            {{ Auth::user()->tenant->nama_toko ?? (Auth::user()->role === 'developer' ? 'Developer Dashboard' : 'Dashboard') }}
                        @endauth
                    </span>
                </a>
            </div>

            {{-- Tengah: Banner Impersonation --}}
            @if (session('impersonator_id'))
                <div class="hidden lg:block flex-1 max-w-2xl mx-4">
                    <div class="bg-amber-400 text-amber-900 rounded-lg shadow-sm">
                        <div class="px-4 py-2 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-2.5 text-sm font-medium">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="truncate">
                                    @auth
                                        Login sebagai <strong class="font-bold">{{ auth()->user()->name }}</strong>
                                        <span class="opacity-80 text-xs">({{ auth()->user()->role }})</span> &bull;
                                        <strong
                                            class="font-bold">{{ auth()->user()->tenant->nama_toko ?? 'Unknown' }}</strong>
                                    @endauth
                                </span>
                            </div>
                            <form action="{{ route('developer.logout-impersonate') }}" method="POST"
                                class="m-0 shrink-0">
                                @csrf
                                <button type="submit"
                                    class="bg-white/90 hover:bg-white text-amber-900 border border-transparent px-3 py-1.5 rounded-md text-xs font-bold uppercase tracking-wide transition-colors shadow-sm">
                                    Kembali
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Kanan: User Profile Menu --}}
            <div class="flex items-center shrink-0">
                <div class="flex items-center gap-3">

                    {{-- Nama User --}}
                    @auth
                        <div class="hidden md:block text-right">
                            <span
                                class="block text-sm sm:text-base font-semibold text-white tracking-wide">{{ Auth::user()->name }}</span>
                        </div>
                    @endauth

                    <button type="button"
                        class="flex items-center justify-center w-10 h-10 text-sm bg-blue-700 border border-blue-500 rounded-full focus:ring-4 focus:ring-blue-400 hover:bg-blue-800 transition-all shadow-sm"
                        data-dropdown-toggle="dropdown-user">
                        <svg class="w-5 h-5 text-blue-100" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    {{-- Dropdown Modal User --}}
                    <div class="z-50 hidden my-4 list-none bg-white border border-slate-100 rounded-xl shadow-xl w-52 overflow-hidden dark:bg-slate-800 dark:border-slate-700"
                        id="dropdown-user">

                        {{-- Fallback Impersonate untuk Mobile --}}
                        @if (session('impersonator_id'))
                            <div
                                class="block lg:hidden px-4 py-3 bg-amber-50 border-b border-amber-100 text-xs text-amber-800">
                                <span class="block font-bold mb-1.5">Mode Developer</span>
                                <form action="{{ route('developer.logout-impersonate') }}" method="POST"
                                    class="m-0">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-white hover:bg-amber-100 text-amber-700 border border-amber-200 px-2.5 py-1.5 rounded text-xs font-bold transition-colors shadow-sm">
                                        Kembali ke Developer
                                    </button>
                                </form>
                            </div>
                        @endif

                        <ul class="py-1.5 text-slate-700">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-rose-600 hover:bg-rose-50 dark:hover:bg-slate-700 transition-colors">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Log Out
                                </a>
                            </form>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
