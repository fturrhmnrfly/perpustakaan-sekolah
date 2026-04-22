@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-sky-500 text-start text-base font-medium text-sky-700 bg-sky-50 focus:outline-none transition'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-slate-600 hover:text-slate-800 hover:bg-sky-50 hover:border-sky-200 focus:outline-none transition';
    @endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
