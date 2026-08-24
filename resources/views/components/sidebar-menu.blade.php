@props(['label', 'route', 'icon' => null, 'active' => null, 'locked' => false, 'badge' => null])

<li>
    @if ($locked)
        <a href="#" onclick="event.preventDefault(); alert('Selesaikan pembayaran untuk mengakses fitur ini.')"
            class="flex items-center p-2.5 sm:p-3 lg:p-2.5 rounded-xl opacity-50 cursor-not-allowed text-gray-400 group">

            @if ($icon)
                <!-- Ikon dibuat sedikit lebih besar di HP (sm:w-6) untuk kemudahan sentuh, dan normal di Desktop -->
                <div class="shrink-0 w-5 h-5 sm:w-6 sm:h-6 lg:w-5 lg:h-5 flex items-center justify-center">
                    @include('components.icons.' . $icon)
                </div>
            @endif

            <!-- Font responsif: 13px (HP kecil) -> 14px (Tablet) -> 15px (Desktop) -->
            <span class="ml-3 sm:ml-3.5 text-[13px] sm:text-sm lg:text-[15px] font-medium">{{ $label }}</span>

            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-4 lg:h-4 ml-auto text-gray-400 shrink-0" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2" />
            </svg>
        </a>
    @else
        <a href="{{ route($route) }}"
            class="flex items-center p-2.5 sm:p-3 lg:p-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs($active)
                      ? 'bg-blue-600 text-white shadow-md font-semibold'
                      : 'text-gray-700 hover:bg-blue-600 hover:text-white hover:shadow-md dark:text-gray-300 dark:hover:bg-blue-600 dark:hover:text-white font-medium' }}">

            @if ($icon)
                <div class="shrink-0 w-5 h-5 sm:w-6 sm:h-6 lg:w-5 lg:h-5 flex items-center justify-center">
                    @include('components.icons.' . $icon)
                </div>
            @endif

            <!-- Font responsif -->
            <span class="ml-3 sm:ml-3.5 text-[13px] sm:text-sm lg:text-[15px]">{{ $label }}</span>

            @if ($badge)
                <!-- Badge responsif: menyesuaikan besaran font label di depannya -->
                <span
                    class="text-[9px] sm:text-[10px] lg:text-[11px] bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-400 px-2 py-0.5 rounded-full font-bold ml-auto shrink-0 tracking-wide">
                    {{ $badge }}
                </span>
            @endif
        </a>
    @endif
</li>
