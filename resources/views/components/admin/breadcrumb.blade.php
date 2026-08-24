@props(['segments' => Request::segments()])

@php
    $url = '';
@endphp

<nav class="flex p-3 bg-gray-200 border border-default-medium rounded-md" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
        <!-- Dashboard -->
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center text-sm font-medium text-body hover:text-blue-500">
                <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                </svg>
                Dashboard
            </a>
        </li>

        <!-- Segments -->
        @foreach ($segments as $segment)
            @php
                $url .= '/' . $segment;
                $isLast = $loop->last;
                $label = ucwords(str_replace(['-', '_'], ' ', $segment));
            @endphp
            <li>
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                    @if ($isLast)
                        <span class="text-sm font-medium text-gray-500">{{ $label }}</span>
                    @else
                        <a href="{{ url($url) }}" class="text-sm font-medium text-body hover:text-blue-500">
                            {{ $label }}
                        </a>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
