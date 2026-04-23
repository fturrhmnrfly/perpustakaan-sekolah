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
            <div class="relative hidden overflow-hidden bg-gradient-to-br from-rose-200 via-amber-100 to-sky-200 p-10 text-slate-800 md:block">
                <div class="pointer-events-none absolute -right-14 -top-16 h-56 w-56 rounded-full bg-white/35 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-20 -left-14 h-64 w-64 rounded-full bg-sky-300/30 blur-3xl"></div>
                <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_15%,rgba(255,255,255,0.45),transparent_32%),radial-gradient(circle_at_80%_80%,rgba(14,165,233,0.15),transparent_40%)]"></div>

                <div class="relative">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-lg font-bold">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/75 text-slate-900 shadow-sm">PS</span>
                    Perpustakaan Sekolah
                </a>
                <h1 class="mt-10 text-3xl font-extrabold leading-tight">Aplikasi Peminjaman Buku yang Cepat dan Rapi</h1>
                <p class="mt-4 text-sm text-slate-700">Kelola koleksi, peminjaman, dan pengembalian buku dalam satu dashboard modern.</p>

                <div class="mt-8 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl border border-white/70 bg-white/55 px-4 py-3 shadow-sm">
                        <p class="text-xl font-extrabold text-sky-700">Realtime</p>
                        <p class="text-xs font-semibold text-slate-600">Status peminjaman</p>
                    </div>
                    <div class="rounded-2xl border border-white/70 bg-white/55 px-4 py-3 shadow-sm">
                        <p class="text-xl font-extrabold text-emerald-700">Terpusat</p>
                        <p class="text-xs font-semibold text-slate-600">Data siswa dan buku</p>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border border-white/70 bg-white/55 p-4 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Keunggulan</p>
                    <ul class="mt-3 space-y-2 text-sm font-medium text-slate-700">
                        <li>Alur pinjam dan kembali lebih tertib.</li>
                        <li>Riwayat transaksi mudah dipantau.</li>
                        <li>Rekap denda lebih cepat dan konsisten.</li>
                    </ul>
                </div>
                </div>
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
