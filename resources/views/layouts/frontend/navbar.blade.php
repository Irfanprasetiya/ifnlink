<nav class="bg-[#1d62fb] border-b border-blue-700 fixed w-full z-20 top-0 start-0">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-14 items-center justify-between ">
            <div class="flex items-center space-x-4 max-md:flex-row-reverse ">
                <span class="text-white font-extrabold text-3xl select-none cursor-default">Ifn<span
                        class="text-base text-yellow-500">Link</span> </span>
                <ul class="hidden md:w-auto md:flex space-x-3 text-blue-300 text-sm items-center">
                    {{-- Home --}}
                    <li>
                        <a href="{{ route('main') }}"
                            class="flex items-center {{ request()->routeIs('main') ? 'text-white font-semibold' : 'hover:text-white' }}">
                            <svg class="w-7 h-7 pb-1  mr-1 {{ request()->routeIs('main') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                            </svg>
                            Home
                        </a>
                    </li>

                    {{-- Transaksi --}}
                    <li>
                        <button id="transactionDropdownButton" data-dropdown-toggle="transactionDropdown" type="button"
                            class="inline-flex items-center p-1 text-sm font-medium focus:outline-none
                                       {{ request()->routeIs('transaksi-bank') || request()->routeIs('vouchers') ? 'text-white font-semibold' : 'text-blue-300 hover:text-white' }}">
                            <svg class="w-6 h-6 mr-1 {{ request()->routeIs('transaksi-bank') || request()->routeIs('vouchers') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M21 12H3"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8H5m12 0a1 1 0 0 1 1 1v2.6M17 8l-4-4M5 8a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.6M5 8l4-4 4 4m6 4h-4a2 2 0 1 0 0 4h4a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1Z" />
                            </svg>
                            Transaksi
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M6 9l6 6 6-6"></path>
                            </svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="transactionDropdown" class="hidden z-10 w-44 bg-white rounded shadow dark:bg-gray-700">
                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                aria-labelledby="transactionDropdownButton">
                                <li>
                                    <a href="{{ route('vouchers') }}"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600
                                               {{ request()->routeIs('vouchers') ? 'text-gray-800 font-semibold' : '' }}">
                                        Transaksi Konter
                                        <span class="ml-1 text-xs text-gray-400">Beta</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('transaksi-bank') }}"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600
                                              {{ request()->routeIs('transaksi-bank') ? 'text-gray-800 font-semibold' : '' }}">
                                        Transaksi Bank
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    {{-- Laporan --}}
                    <li>
                        <button id="reportDropdownButton" data-dropdown-toggle="reportDropdown" type="button"
                            class="inline-flex items-center p-1 text-sm font-medium focus:outline-none
                                       {{ request()->routeIs('laporan-bank') || request()->routeIs('laporan-bank.rekap') || request()->routeIs('laporan_konter') ? 'text-white font-semibold' : 'text-blue-300 hover:text-white' }}">
                            <svg class="w-6 h-6 mr-1 {{ request()->routeIs('laporan-bank') || request()->routeIs('laporan-bank.rekap') || request()->routeIs('laporan_konter') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 4v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2m6-16v2m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v10m6-16v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2" />
                            </svg>
                            Laporan
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M6 9l6 6 6-6"></path>
                            </svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="reportDropdown" class="hidden z-10 w-44 bg-white rounded shadow dark:bg-gray-700">
                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                aria-labelledby="reportDropdownButton">
                                <li>
                                    <a href="{{ route('laporan_konter') }}"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600
                                              {{ request()->routeIs('laporan_konter') ? 'text-gray-800 font-semibold' : '' }}">
                                        Laporan Konter
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('laporan-bank') }}"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600
                                              {{ request()->routeIs('laporan-bank') || request()->routeIs('laporan-bank.rekap') ? 'text-gray-800 font-semibold' : '' }}">
                                        Laporan Bank
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="flex items-center">
                <button id="userMenuButton" data-dropdown-toggle="userDropdown"
                    class="flex items-center text-white text-sm font-medium hover:text-gray-200 focus:outline-none"
                    type="button">
                    <svg class="w-6 h-6 mr-1" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 12c2.21 0 4-1.79 4-4S14.21 4 12 4 8 5.79 8 8s1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                    {{ Auth::user()->name }}
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M6 9l6 6 6-6"></path>
                    </svg>
                </button>
                <!-- Dropdown menu -->
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <div id="userDropdown" class="hidden z-25 w-40 bg-white rounded shadow dark:bg-gray-700">
                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="userMenuButton">
                            <li><a href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logout</a>
                            </li>
                        </ul>
                    </div>

                </form>

            </div>
        </div>
    </div>
</nav>

<!-- Mobile Navigation Menu -->
{{-- <div id="navbar-default" class="mt-14 md:hidden hidden bg-white shadow-lg fixed w-full z-20 top-0 start-0">
    <ul class="text-blue-600 text-sm flex flex-col space-y-2 py-4 px-4">
        <li>
            <a href="{{ route('main') }}" class="flex py-2 items-center hover:bg-gray-300 w-full">
                <svg class="w-5 h-5 mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10.707 1.293a1 1 0 00-1.414 0L3 7.586V18a1 1 0 001 1h4a1 1 0 001-1v-4h2v4a1 1 0 001 1h4a1 1 0 001-1V7.586l-6.293-6.293z" />
                </svg>
                Home
            </a>
        </li>
        <li>
            <button id="mobileTransactionDropdownButton" data-dropdown-toggle="transactionDropdown"
                class="flex items-center text-blue-600 py-2 hover:bg-gray-300 w-full text-sm font-medium focus:outline-none"
                type="button">
                <svg class="w-5 h-5 mr-1 text-blue-600 group-hover:bg-gray-300" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M21 12H3"></path>
                    <path d="M12 21l-9-9 9-9"></path>
                </svg>
                Trsnsaksi
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M6 9l6 6 6-6"></path>
                </svg>
            </button>
            <!-- Dropdown menu -->
            <div id="mobileTransactionDropdown" class="hidden z-10 w-44 bg-white rounded shadow dark:bg-gray-700">
                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                    aria-labelledby="mobileTransactionDropdownButton">
                    <li><a href="{{ route('vouchers') }}"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Transaksi
                            Konter</a></li>
                    <li><a href="{{ route('transaksi-bank') }}"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Transaksi
                            Bank</a></li>
                </ul>
            </div>
        </li>
        <li>
            <button id="mobileReportDropdownButton" data-dropdown-toggle="reportDropdown"
                class="inline-flex items-center text-blue-600 py-2 hover:bg-gray-300 w-full text-sm font-medium focus:outline-none"
                type="button">
                <svg class="w-5 h-5 mr-1 text-blue-600 group-hover:bg-gray-300" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M9 17v-2a3 3 0 0 1 6 0v2"></path>
                    <circle cx="12" cy="11" r="4"></circle>
                </svg>
                Laporan
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M6 9l6 6 6-6"></path>
                </svg>
            </button>
            <!-- Dropdown menu -->
            <div id="mobileReportDropdown" class="hidden z-99 w-44 bg-white rounded shadow dark:bg-gray-700">
                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                    aria-labelledby="mobileReportDropdownButton">
                    <li><a href="{{ route('laporan_konter') }}"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Laporan
                            Konter</a></li>
                    <li><a href="{{ route('laporan-bank') }}"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Laporan
                            Bank</a></li>
                </ul>
            </div>
        </li>

        <li>
            <a href="#" class="flex py-2 items-center hover:bg-gray-300 w-full">
                <svg class="w-6 h-6 text-white max-md:text-gray-800 dark:text-white" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M7.5 4.586A2 2 0 0 1 8.914 4h6.172a2 2 0 0 1 1.414.586L17.914 6H19a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h1.086L7.5 4.586ZM10 12a2 2 0 1 1 4 0 2 2 0 0 1-4 0Zm2-4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z"
                        clip-rule="evenodd" />
                </svg>
                Absensi
            </a>
        </li>

        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div id="userDropdown" class="hidden z-25 w-40 bg-white rounded shadow dark:bg-gray-700">
                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="userMenuButton">
                    <li><a href="route('logout')"
                            onclick="event.preventDefault();
                                                this.closest('form').submit();"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logout</a>
                    </li>
                </ul>
            </div>

        </form>
    </ul>
</div> --}}

{{-- Mobile Nav Bottom --}}
<div class="fixed md:hidden bottom-0 left-0 z-50 w-full h-16 border-t border-gray-200 bg-white" x-data="{ transaksiOpen: false, laporanOpen: false }">
    <div class="grid h-full max-w-lg grid-cols-3 mx-auto font-medium">

        <div class="mx-auto">
            {{-- Home --}}
            <a href="{{ route('main') }}"
                class="relative inline-flex flex-col items-center justify-center px-5 group
                      {{ request()->routeIs('main') ? 'text-blue-600' : 'text-gray-600' }}">
                <svg class="w-6 h-6 mb-1
                            {{ request()->routeIs('main') ? 'text-blue-600' : 'text-gray-600 group-hover:text-blue-600' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                </svg>
                <span class="text-sm">Home</span>
            </a>
        </div>

        {{-- Transaksi --}}
        <div class="relative">
            <button type="button" x-on:click="transaksiOpen = !transaksiOpen"
                class="inline-flex flex-col items-center justify-center w-full px-5 group
                           {{ request()->routeIs('transaksi-bank') || request()->routeIs('vouchers') ? 'text-blue-600' : 'text-gray-600' }}">
                <svg class="w-6 h-6 mb-1
                            {{ request()->routeIs('transaksi-bank') || request()->routeIs('vouchers') ? 'text-blue-600' : 'text-gray-600 group-hover:text-blue-600' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8H5m12 0a1 1 0 0 1 1 1v2.6M17 8l-4-4M5 8a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.6M5 8l4-4 4 4m6 4h-4a2 2 0 1 0 0 4h4a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1Z" />
                </svg>
                <span class="text-sm">Transaksi</span>
            </button>

            {{-- Dropdown ke atas --}}
            <div x-show="transaksiOpen" x-on:click.outside="transaksiOpen = false"
                class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 w-40 bg-white border rounded shadow-lg">
                <a href="{{ route('transaksi-bank') }}"
                    class="block px-4 py-2 text-sm hover:bg-gray-100
                          {{ request()->routeIs('transaksi-bank') ? 'text-blue-600' : '' }}">
                    Transaksi BriLink
                </a>
                <a href="{{ route('vouchers') }}"
                    class="block px-4 py-2 text-sm hover:bg-gray-100
                          {{ request()->routeIs('vouchers') ? 'text-blue-600' : '' }}">
                    Transaksi Konter
                </a>
            </div>
        </div>

        {{-- Laporan --}}
        <div class="relative">
            <button type="button" x-on:click="laporanOpen = !laporanOpen"
                class="inline-flex flex-col items-center justify-center w-full px-5 group 
            {{ request()->routeIs('laporan-bank') ||
            request()->routeIs('laporan-bank.rekap') ||
            request()->routeIs('laporan_konter')
                ? 'text-blue-600'
                : 'text-gray-600' }}">
                <svg class="w-6 h-6 mb-1
                            {{ request()->routeIs('laporan-bank') || request()->routeIs('laporan-bank.rekap') || request()->routeIs('laporan_konter') ? 'text-blue-600' : 'text-gray-600 group-hover:text-blue-600' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 4v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2m6-16v2m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v10m6-16v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2" />
                </svg>
                <span class="text-sm">Laporan</span>
            </button>

            {{-- Dropdown ke atas --}}
            <div x-show="laporanOpen" x-on:click.outside="laporanOpen = false"
                class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 w-40 bg-white border rounded shadow-lg">
                <a href="{{ route('laporan-bank') }}"
                    class="block px-4 py-2 text-sm hover:bg-gray-100
                          {{ request()->routeIs('laporan-bank') ? 'text-blue-600' : '' }}">
                    Laporan BriLink
                </a>
                <a href="{{ route('laporan_konter') }}"
                    class="block px-4 py-2 text-sm hover:bg-gray-100 
                          {{ request()->routeIs('laporan_konter') ? 'text-blue-600' : '' }}">
                    Laporan Konter
                </a>
            </div>
        </div>
    </div>
</div>

{{-- end --}}
