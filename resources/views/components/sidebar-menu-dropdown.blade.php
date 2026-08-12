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
            class="flex items-center w-full p-2 text-base text-gray-400 cursor-not-allowed rounded-lg group">
            @if ($icon)
                @include('components.icons.' . $icon)
            @endif
            <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{ $label }}</span>
            <svg class="w-4 h-4 ml-auto text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2" />
            </svg>
        </button>
    @else
        <button type="button" @click.stop="open = !open"
            class="flex items-center w-full p-2 text-base transition-all duration-200 rounded-lg group
                   {{ $childActive
                       ? 'bg-blue-600 text-white shadow-md'
                       : 'text-gray-700 hover:bg-blue-600 hover:text-white hover:shadow-md dark:text-gray-300 dark:hover:bg-blue-600 dark:hover:text-white' }}"
            :class="{ 'bg-blue-600 text-white shadow-md': open }">
            @if ($icon)
                @include('components.icons.' . $icon)
            @endif
            <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{ $label }}</span>
            <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 4 4 4-4" />
            </svg>
        </button>
    @endif

    <ul x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2" class="py-2 space-y-1" @click.stop>
        {{ $slot }}
    </ul>
</li>
