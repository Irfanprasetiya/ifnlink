@props(['label', 'route', 'icon' => null, 'active' => null])

<li>
    <a href="{{ route($route) }}"
        class="flex items-center p-2 rounded-lg
              {{ Request::is($active) ? 'bg-blue-600 text-white border-l-4 border-blue-600' : 'hover:text-white hover:bg-blue-600' }}">
        @if ($icon)
            @include('components.icons.' . $icon)
        @endif
        <span class="ml-3">{{ $label }}</span>
    </a>
</li>
