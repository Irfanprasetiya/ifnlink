@props(['label', 'route', 'icon' => null, 'active' => null, 'locked' => false, 'badge' => null])

<li>
    @if ($locked)
        <a href="#" onclick="event.preventDefault(); alert('Selesaikan pembayaran untuk mengakses fitur ini.')"
            class="flex items-center p-2 rounded-lg opacity-50 cursor-not-allowed text-gray-400">
            @if ($icon)
                @include('components.icons.' . $icon)
            @endif
            <span class="ml-3">{{ $label }}</span>
            <svg class="w-4 h-4 ml-auto text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2" />
            </svg>
        </a>
    @else
        <a href="{{ route($route) }}"
            class="flex items-center p-2 rounded-lg transition-all duration-200
                  {{ request()->routeIs($active)
                      ? 'bg-blue-600 text-white shadow-md font-semibold'
                      : 'text-gray-700 hover:bg-blue-600 hover:text-white hover:shadow-md dark:text-gray-300 dark:hover:bg-blue-600 dark:hover:text-white' }}">
            @if ($icon)
                @include('components.icons.' . $icon)
            @endif
            <span class="ml-3">{{ $label }}</span>

            @if ($badge)
                <span
                    class="text-[10px] bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-400 px-1.5 py-0.5 rounded-full font-bold ml-auto">
                    {{ $badge }}
                </span>
            @endif
        </a>
    @endif
</li>
