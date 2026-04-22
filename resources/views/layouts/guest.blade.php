<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Perpustakaan Sekolah') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="app-shell flex min-h-screen items-center justify-center px-4 py-10">
        <div class="grid w-full max-w-5xl overflow-hidden rounded-3xl border border-rose-100 bg-white/85 shadow-2xl shadow-sky-100/60 backdrop-blur md:grid-cols-2">
            <div class="hidden bg-gradient-to-br from-rose-200 via-amber-100 to-sky-200 p-10 text-slate-800 md:block">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-lg font-bold">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white/70">PS</span>
                    Perpustakaan Sekolah
                </a>
                <h1 class="mt-10 text-3xl font-extrabold leading-tight">Aplikasi Peminjaman Buku yang Cepat dan Rapi</h1>
                <p class="mt-4 text-sm text-slate-700">Kelola koleksi, peminjaman, dan pengembalian buku dalam satu dashboard modern.</p>
            </div>
            <div class="p-6 sm:p-10">
                <div class="mb-6 md:hidden">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-800">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-sky-500 text-white">PS</span>
                        Perpustakaan Sekolah
                    </a>
                </div>
                <div class="page-enter">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
