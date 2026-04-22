@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-xl border border-emerald-300/30 bg-emerald-900/30 px-4 py-3 text-sm font-medium text-emerald-100']) }}>
        {{ $status }}
    </div>
@endif
