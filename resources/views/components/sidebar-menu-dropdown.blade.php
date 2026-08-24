@props(['label', 'icon', 'locked' => false])

<li x-data="{ open: {{ $childActive ?? 'false' }} }">
    @php
        $childActive = false;

        if (!$locked) {
            $childContent = (string) $slot;
            foreach (explode("\n", $childContent) as $line) {
                if (str_contains($line, 'route(')) {
                    preg_match("/route\('([^']+)'\)/", $line, $matches);
                    if (isset($matches[1]) && request()->routeIs($matches[1] . '*')) {
                        $childActive = true;
                        break;
                    }
                }
            }
        }
    @endphp

    @if ($locked)
        <button type="button" onclick="event.preventDefault(); alert('Selesaikan pembayaran untuk mengakses fitur ini.')"
            class="flex items-center w-full p-2.5 sm:p-3 lg:p-2.5 text-gray-400 cursor-not-allowed rounded-xl group opacity-50">

            @if ($icon)
                <!-- Wrapper ikon responsif yang sama dengan menu single -->
                <div class="shrink-0 w-5 h-5 sm:w-6 sm:h-6 lg:w-5 lg:h-5 flex items-center justify-center">
                    @include('components.icons.' . $icon)
                </div>
            @endif

            <!-- Font responsif -->
            <span
                class="flex-1 ml-3 sm:ml-3.5 text-left rtl:text-right whitespace-nowrap text-[13px] sm:text-sm lg:text-[15px] font-medium">{{ $label }}</span>

            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-4 lg:h-4 ml-auto text-gray-400 shrink-0" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2" />
            </svg>
        </button>
    @else
        <button type="button" @click.stop="open = !open"
            class="flex items-center w-full p-2.5 sm:p-3 lg:p-2.5 transition-all duration-200 rounded-xl group font-medium
                   {{ $childActive
                       ? 'bg-blue-600 text-white shadow-md font-semibold'
                       : 'text-gray-700 hover:bg-blue-600 hover:text-white hover:shadow-md dark:text-gray-300 dark:hover:bg-blue-600 dark:hover:text-white' }}"
            :class="{ 'bg-blue-600 text-white shadow-md font-semibold': open }">

            @if ($icon)
                <!-- Wrapper ikon responsif -->
                <div class="shrink-0 w-5 h-5 sm:w-6 sm:h-6 lg:w-5 lg:h-5 flex items-center justify-center">
                    @include('components.icons.' . $icon)
                </div>
            @endif

            <!-- Font responsif -->
            <span
                class="flex-1 ml-3 sm:ml-3.5 text-left rtl:text-right whitespace-nowrap text-[13px] sm:text-sm lg:text-[15px]">{{ $label }}</span>

            <!-- Ikon Chevron (Dropdown) juga disesuaikan ukurannya di mobile -->
            <svg class="w-3 h-3 sm:w-4 sm:h-4 lg:w-3 lg:h-3 ml-auto transition-transform duration-200 shrink-0"
                :class="open ? 'rotate-180' : ''" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 4 4 4-4" />
            </svg>
        </button>
    @endif

    <!-- Logika transisi dan slot SAMA PERSIS -->
    <ul x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2" class="py-2 space-y-1" @click.stop>
        {{ $slot }}
    </ul>
</li>
