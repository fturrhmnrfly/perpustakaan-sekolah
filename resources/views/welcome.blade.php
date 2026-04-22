<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Perpustakaan Sekolah') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="app-shell">
        <header class="flex w-full items-center justify-between px-5 py-6 sm:px-8 lg:px-10">
            <div class="inline-flex items-center gap-2 text-lg font-extrabold text-slate-800">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-sky-100 text-sky-700">PS</span>
                Perpustakaan Sekolah
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl px-4 py-2 font-semibold text-slate-700 transition hover:bg-white/70">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                @endauth
            </div>
        </header>

        <main class="grid w-full gap-8 px-5 pb-16 pt-8 sm:px-8 lg:grid-cols-2 lg:px-10">
            <section class="glass-card p-8 md:p-12">
                <p class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-sky-700">Sistem Perpustakaan Modern</p>
                <h1 class="mt-6 text-4xl font-extrabold leading-tight text-slate-900 md:text-5xl">
                    Kelola Peminjaman Buku dengan Cepat dan Profesional
                </h1>
                <p class="mt-5 text-base text-slate-600 md:text-lg">
                    Platform ini membantu admin dan siswa mengelola buku, peminjaman, pengembalian, serta denda dalam alur yang jelas.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary">Masuk Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn-primary">Buat Akun Siswa</a>
                        <a href="{{ route('login') }}" class="btn-secondary">Sudah Punya Akun</a>
                    @endauth
                </div>
            </section>

            <section class="grid gap-4">
                <article class="glass-card p-6">
                    <h2 class="text-lg font-bold text-slate-900">Fitur Utama</h2>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        <li>Monitoring stok buku secara real-time.</li>
                        <li>Peminjaman dan pengembalian dengan validasi otomatis.</li>
                        <li>Riwayat transaksi lengkap untuk admin dan siswa.</li>
                        <li>Perhitungan denda keterlambatan yang konsisten.</li>
                    </ul>
                </article>
                <article class="glass-card p-6">
                    <h2 class="text-lg font-bold text-slate-900">Alur Cepat</h2>
                    <ol class="mt-4 space-y-2 text-sm text-slate-600">
                        <li>1. Siswa login lalu cari buku.</li>
                        <li>2. Siswa tentukan tanggal kembali rencana.</li>
                        <li>3. Admin memantau dan memproses pengembalian.</li>
                        <li>4. Sistem menyimpan riwayat dan denda otomatis.</li>
                    </ol>
                </article>
            </section>
        </main>
    </div>
</body>
</html>
