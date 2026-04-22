@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-2 py-1 border-b-2 border-sky-500 text-sm font-medium leading-5 text-slate-800 transition'
            : 'inline-flex items-center px-2 py-1 border-b-2 border-transparent text-sm font-medium leading-5 text-slate-600 hover:text-slate-800 hover:border-sky-200 transition';
    @endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
