{{-- File: resources/views/components/sidebar-section.blade.php --}}
@props(['label'])

<div class="mt-5 mb-2 px-4 first:mt-0">
    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
        {{ $label }}
    </p>
</div>
