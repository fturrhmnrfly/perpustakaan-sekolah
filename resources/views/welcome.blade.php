<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Perpustakaan Sekolah') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="app-shell animated-bg pattern-dots">
        <header class="relative flex w-full items-center justify-between px-5 py-6 sm:px-8 lg:px-10 border-b border-white/10 backdrop-blur-md">
            <div class="inline-flex items-center gap-3 text-xl font-extrabold text-slate-800">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-sky-600 text-white shadow-lg shadow-sky-500/30">PS</span>
                <span>Perpustakaan Sekolah</span>
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl px-4 py-2 font-semibold text-slate-700 transition-all hover:bg-white/80 hover:text-sky-600">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                @endauth
            </div>
        </header>

        <main class="grid w-full gap-8 px-5 pb-20 pt-12 sm:px-8 lg:grid-cols-5 lg:px-10">
            <section class="glass-card relative overflow-hidden p-8 md:p-16 lg:col-span-3 pattern-grid">
                <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-sky-200/40 blur-3xl animate-pulse"></div>
                <div class="pointer-events-none absolute -bottom-20 -left-12 h-64 w-64 rounded-full bg-purple-200/40 blur-3xl" style="animation: sway 6s ease-in-out infinite;"></div>
                <!-- Decorative ornaments -->
                <svg class="ornament ornament-rotate" style="width: 200px; height: 200px; right: -50px; top: -50px;" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 10 L130 80 L200 80 L150 130 L170 200 L100 160 L30 200 L50 130 L0 80 L70 80 Z" fill="currentColor" class="text-sky-400"/>
                </svg>
                <svg class="ornament" style="width: 150px; height: 150px; left: -30px; bottom: -30px; opacity: 0.08;" viewBox="0 0 150 150" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="75" cy="75" r="70" fill="none" stroke="currentColor" stroke-width="2" class="text-purple-400"/>
                    <circle cx="75" cy="75" r="50" fill="none" stroke="currentColor" stroke-width="1" class="text-purple-400" opacity="0.5"/>
                </svg>
                <div class="relative">
                    <p class="inline-flex rounded-full border border-sky-300 bg-sky-100 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-sky-700">✨ Sistem Perpustakaan Modern</p>
                    <h1 class="mt-8 text-5xl font-extrabold leading-tight text-slate-900 md:text-6xl lg:text-5xl">
                        Kelola Peminjaman Buku dengan Cepat dan Profesional
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg text-slate-700 leading-relaxed">
                        Platform ini membantu admin dan siswa mengelola buku, peminjaman, pengembalian, serta denda dalam alur yang jelas dan terorganisir.
                    </p>
                    <div class="mt-10 flex flex-wrap gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-primary">Masuk Dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="btn-primary">Buat Akun Siswa</a>
                            <a href="{{ route('login') }}" class="btn-secondary">Sudah Punya Akun</a>
                        @endauth
                    </div>

                    <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <div class="group rounded-2xl border border-sky-100/60 bg-gradient-to-br from-sky-50 to-sky-100/50 p-4 text-center transition-all duration-300 hover:border-sky-200 hover:shadow-lg hover:shadow-sky-200/30">
                            <p class="text-2xl font-extrabold bg-gradient-to-br from-sky-600 to-sky-700 bg-clip-text text-transparent">24/7</p>
                            <p class="mt-1 text-xs font-semibold text-slate-600">Akses Sistem</p>
                        </div>
                        <div class="group rounded-2xl border border-emerald-100/60 bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-4 text-center transition-all duration-300 hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-200/30">
                            <p class="text-2xl font-extrabold bg-gradient-to-br from-emerald-600 to-emerald-700 bg-clip-text text-transparent">Realtime</p>
                            <p class="mt-1 text-xs font-semibold text-slate-600">Update Stok</p>
                        </div>
                        <div class="group rounded-2xl border border-violet-100/60 bg-gradient-to-br from-violet-50 to-violet-100/50 p-4 text-center transition-all duration-300 hover:border-violet-200 hover:shadow-lg hover:shadow-violet-200/30">
                            <p class="text-2xl font-extrabold bg-gradient-to-br from-violet-600 to-violet-700 bg-clip-text text-transparent">Tertib</p>
                            <p class="mt-1 text-xs font-semibold text-slate-600">Alur Pinjam</p>
                        </div>
                        <div class="group rounded-2xl border border-amber-100/60 bg-gradient-to-br from-amber-50 to-amber-100/50 p-4 text-center transition-all duration-300 hover:border-amber-200 hover:shadow-lg hover:shadow-amber-200/30">
                            <p class="text-2xl font-extrabold bg-gradient-to-br from-amber-600 to-amber-700 bg-clip-text text-transparent">Aman</p>
                            <p class="mt-1 text-xs font-semibold text-slate-600">Data Riwayat</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid gap-6 lg:col-span-2">
                <article class="glass-card p-8">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="text-2xl">✨</span>
                        <h2 class="text-xl font-bold text-slate-900">Fitur Utama</h2>
                    </div>
                    <ul class="space-y-3">
                        <li class="rounded-xl border border-sky-100/60 bg-gradient-to-br from-sky-50/80 to-sky-100/50 px-4 py-3 text-sm font-medium text-slate-700 transition-all hover:shadow-md hover:border-sky-200">
                            <span class="text-sky-600 font-bold">→</span> Monitoring stok buku secara real-time
                        </li>
                        <li class="rounded-xl border border-emerald-100/60 bg-gradient-to-br from-emerald-50/80 to-emerald-100/50 px-4 py-3 text-sm font-medium text-slate-700 transition-all hover:shadow-md hover:border-emerald-200">
                            <span class="text-emerald-600 font-bold">→</span> Peminjaman dan pengembalian otomatis
                        </li>
                        <li class="rounded-xl border border-purple-100/60 bg-gradient-to-br from-purple-50/80 to-purple-100/50 px-4 py-3 text-sm font-medium text-slate-700 transition-all hover:shadow-md hover:border-purple-200">
                            <span class="text-purple-600 font-bold">→</span> Riwayat transaksi lengkap dan akurat
                        </li>
                        <li class="rounded-xl border border-rose-100/60 bg-gradient-to-br from-rose-50/80 to-rose-100/50 px-4 py-3 text-sm font-medium text-slate-700 transition-all hover:shadow-md hover:border-rose-200">
                            <span class="text-rose-600 font-bold">→</span> Perhitungan denda keterlambatan otomatis
                        </li>
                    </ul>
                </article>
                <article class="glass-card p-8">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="text-2xl">🚀</span>
                        <h2 class="text-xl font-bold text-slate-900">Alur Cepat</h2>
                    </div>
                    <ol class="space-y-3">
                        <li class="rounded-xl border border-sky-100/60 bg-gradient-to-br from-sky-50/80 to-sky-100/50 px-4 py-3 text-sm font-medium text-slate-700 transition-all hover:shadow-md hover:border-sky-200">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-sky-600 text-white text-xs font-bold mr-2">1</span> Siswa login dan cari buku
                        </li>
                        <li class="rounded-xl border border-amber-100/60 bg-gradient-to-br from-amber-50/80 to-amber-100/50 px-4 py-3 text-sm font-medium text-slate-700 transition-all hover:shadow-md hover:border-amber-200">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-amber-600 text-white text-xs font-bold mr-2">2</span> Tentukan tanggal rencana kembali
                        </li>
                        <li class="rounded-xl border border-violet-100/60 bg-gradient-to-br from-violet-50/80 to-violet-100/50 px-4 py-3 text-sm font-medium text-slate-700 transition-all hover:shadow-md hover:border-violet-200">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-violet-600 text-white text-xs font-bold mr-2">3</span> Admin memproses pengembalian
                        </li>
                        <li class="rounded-xl border border-emerald-100/60 bg-gradient-to-br from-emerald-50/80 to-emerald-100/50 px-4 py-3 text-sm font-medium text-slate-700 transition-all hover:shadow-md hover:border-emerald-200">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-emerald-600 text-white text-xs font-bold mr-2">4</span> Sistem menyimpan riwayat dan denda
                        </li>
                    </ol>
                </article>
            </section>
        </main>

        <!-- Features Section -->
        <section class="relative px-5 py-20 sm:px-8 lg:px-10 overflow-hidden">
            <svg class="ornament ornament-rotate absolute right-0 top-0" style="width: 300px; height: 300px;" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg">
                <rect x="50" y="50" width="200" height="200" fill="none" stroke="currentColor" stroke-width="2" class="text-sky-200" opacity="0.2" rx="20"/>
                <circle cx="150" cy="150" r="100" fill="none" stroke="currentColor" stroke-width="1" class="text-purple-200" opacity="0.2"/>
            </svg>
            <div class="mx-auto max-w-7xl relative z-10">
                <div class="mb-16 text-center">
                    <p class="fade-in-up inline-flex rounded-full border border-sky-300 bg-sky-100 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-sky-700">📚 Keunggulan Platform</p>
                    <h2 class="fade-in-up mt-6 text-4xl font-extrabold text-slate-900 md:text-5xl">Fitur Lengkap untuk Setiap Kebutuhan</h2>
                    <p class="fade-in-up mx-auto mt-4 max-w-2xl text-lg text-slate-600">Sistem perpustakaan yang dirancang untuk kemudahan maksimal bagi admin dan siswa</p>
                </div>

                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <div class="fade-in-up glass-card p-8 hover:scale-105 relative overflow-hidden group" style="animation-delay: 0.1s">
                        <div class="absolute inset-0 bg-gradient-to-br from-sky-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-sky-600 text-2xl shadow-lg shadow-sky-500/30 group-hover:shadow-sky-500/50 transition-all">📖</div>
                        <h3 class="text-xl font-bold text-slate-900">Katalog Buku</h3>
                        <p class="mt-2 text-sm text-slate-600">Manajemen katalog buku yang komprehensif dengan pencarian dan filter yang canggih.</p>
                    </div>

                    <div class="fade-in-up glass-card p-8 hover:scale-105 relative overflow-hidden group" style="animation-delay: 0.2s">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 text-2xl shadow-lg shadow-emerald-500/30 group-hover:shadow-emerald-500/50 transition-all">✅</div>
                        <h3 class="text-xl font-bold text-slate-900">Validasi Otomatis</h3>
                        <p class="mt-2 text-sm text-slate-600">Sistem otomatis untuk memvalidasi peminjaman, pengembalian, dan perhitungan denda.</p>
                    </div>

                    <div class="fade-in-up glass-card p-8 hover:scale-105 relative overflow-hidden group" style="animation-delay: 0.3s">
                        <div class="absolute inset-0 bg-gradient-to-br from-violet-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-violet-600 text-2xl shadow-lg shadow-violet-500/30 group-hover:shadow-violet-500/50 transition-all">📊</div>
                        <h3 class="text-xl font-bold text-slate-900">Dashboard Analytics</h3>
                        <p class="mt-2 text-sm text-slate-600">Laporan real-time dan analitik mendalam untuk monitoring performa sistem.</p>
                    </div>

                    <div class="fade-in-up glass-card p-8 hover:scale-105" style="animation-delay: 0.4s">
                        <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 text-2xl shadow-lg shadow-amber-500/30">🔐</div>
                        <h3 class="text-xl font-bold text-slate-900">Keamanan Data</h3>
                        <p class="mt-2 text-sm text-slate-600">Enkripsi dan autentikasi tingkat lanjut untuk melindungi data pengguna.</p>
                    </div>

                    <div class="fade-in-up glass-card p-8 hover:scale-105" style="animation-delay: 0.5s">
                        <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-rose-500 to-rose-600 text-2xl shadow-lg shadow-rose-500/30">📲</div>
                        <h3 class="text-xl font-bold text-slate-900">Interface User-Friendly</h3>
                        <p class="mt-2 text-sm text-slate-600">Desain intuitif dan responsif yang mudah digunakan di berbagai perangkat.</p>
                    </div>

                    <div class="fade-in-up glass-card p-8 hover:scale-105" style="animation-delay: 0.6s">
                        <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-500 to-cyan-600 text-2xl shadow-lg shadow-cyan-500/30">⚡</div>
                        <h3 class="text-xl font-bold text-slate-900">Performa Cepat</h3>
                        <p class="mt-2 text-sm text-slate-600">Sistem yang dioptimalkan untuk kecepatan dan efisiensi maksimal.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="relative bg-gradient-to-r from-sky-50 via-purple-50 to-sky-50 px-5 py-20 sm:px-8 lg:px-10 overflow-hidden">
            <svg class="ornament absolute left-0 bottom-0" style="width: 250px; height: 250px; opacity: 0.05;" viewBox="0 0 250 250" xmlns="http://www.w3.org/2000/svg">
                <path d="M125 20 Q190 80 190 150 Q190 220 125 220 Q60 220 60 150 Q60 80 125 20" fill="none" stroke="currentColor" stroke-width="2" class="text-emerald-400"/>
            </svg>
            <svg class="ornament ornament-rotate absolute right-0 top-0" style="width: 200px; height: 200px; opacity: 0.08;" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <circle cx="100" cy="100" r="80" fill="none" stroke="currentColor" stroke-width="2" class="text-sky-300"/>
                <circle cx="100" cy="100" r="60" fill="none" stroke="currentColor" stroke-width="1" class="text-sky-200"/>
            </svg>
            <div class="mx-auto max-w-7xl relative z-10">
                <div class="mb-16 text-center">
                    <p class="fade-in-up inline-flex rounded-full border border-purple-300 bg-purple-100 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-purple-700">🎯 Cara Kerja</p>
                    <h2 class="fade-in-up mt-6 text-4xl font-extrabold text-slate-900 md:text-5xl">Alur Sistem yang Mudah</h2>
                </div>

                <div class="grid gap-8 md:grid-cols-4">
                    <div class="fade-in-up text-center" style="animation-delay: 0.1s">
                        <div class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-500 to-sky-600 text-white shadow-lg shadow-sky-500/30">
                            <span class="text-2xl font-bold">1</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Login</h3>
                        <p class="mt-2 text-sm text-slate-600">Masuk ke sistem dengan akun sekolah Anda</p>
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="h-1 w-8 bg-gradient-to-r from-sky-500 to-purple-500 md:h-8 md:w-1"></div>
                    </div>

                    <div class="fade-in-up text-center" style="animation-delay: 0.2s">
                        <div class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/30">
                            <span class="text-2xl font-bold">2</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Pilih Buku</h3>
                        <p class="mt-2 text-sm text-slate-600">Cari dan pilih buku yang ingin dipinjam</p>
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="h-1 w-8 bg-gradient-to-r from-purple-500 to-violet-500 md:h-8 md:w-1"></div>
                    </div>

                    <div class="fade-in-up text-center" style="animation-delay: 0.3s">
                        <div class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500 to-violet-600 text-white shadow-lg shadow-violet-500/30">
                            <span class="text-2xl font-bold">3</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Konfirmasi</h3>
                        <p class="mt-2 text-sm text-slate-600">Admin memverifikasi dan memproses peminjaman</p>
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="h-1 w-8 bg-gradient-to-r from-violet-500 to-amber-500 md:h-8 md:w-1"></div>
                    </div>

                    <div class="fade-in-up text-center" style="animation-delay: 0.4s">
                        <div class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 text-white shadow-lg shadow-amber-500/30">
                            <span class="text-2xl font-bold">4</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Selesai</h3>
                        <p class="mt-2 text-sm text-slate-600">Riwayat dan denda tercatat otomatis</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="relative px-5 py-20 sm:px-8 lg:px-10 pattern-grid">
            <svg class="ornament ornament-rotate absolute right-0 top-0" style="width: 250px; height: 250px; opacity: 0.06;" viewBox="0 0 250 250" xmlns="http://www.w3.org/2000/svg">
                <polygon points="125,20 200,90 180,180 70,180 50,90" fill="none" stroke="currentColor" stroke-width="2" class="text-violet-300"/>
            </svg>
            <div class="mx-auto max-w-5xl relative z-10">
                <div class="grid gap-8 md:grid-cols-4">
                    <div class="fade-in-up glass-card text-center p-8 bounce-in" style="animation-delay: 0.1s">
                        <p class="text-4xl font-extrabold text-sky-600">100+</p>
                        <p class="mt-2 font-semibold text-slate-700">Sekolah Pengguna</p>
                    </div>

                    <div class="fade-in-up glass-card text-center p-8 bounce-in" style="animation-delay: 0.2s">
                        <p class="text-4xl font-extrabold text-emerald-600">10K+</p>
                        <p class="mt-2 font-semibold text-slate-700">Buku Tercatat</p>
                    </div>

                    <div class="fade-in-up glass-card text-center p-8 bounce-in" style="animation-delay: 0.3s">
                        <p class="text-4xl font-extrabold text-violet-600">50K+</p>
                        <p class="mt-2 font-semibold text-slate-700">Peminjaman/Bulan</p>
                    </div>

                    <div class="fade-in-up glass-card text-center p-8 bounce-in" style="animation-delay: 0.4s">
                        <p class="text-4xl font-extrabold text-rose-600">99.9%</p>
                        <p class="mt-2 font-semibold text-slate-700">Uptime</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="relative overflow-hidden px-5 py-20 sm:px-8 lg:px-10">
            <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-sky-200/30 blur-3xl animate-pulse"></div>
            <div class="pointer-events-none absolute -left-32 -bottom-32 h-96 w-96 rounded-full bg-purple-200/30 blur-3xl" style="animation: sway 8s ease-in-out infinite;"></div>
            <svg class="ornament ornament-rotate absolute top-1/2 left-1/4 -translate-y-1/2" style="width: 200px; height: 200px; opacity: 0.04;" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 10 L130 80 L200 80 L150 130 L170 200 L100 160 L30 200 L50 130 L0 80 L70 80 Z" fill="currentColor" class="text-amber-400"/>
            </svg>

            <div class="relative mx-auto max-w-4xl">
                <div class="glass-card p-12 text-center md:p-16">
                    <p class="fade-in-up inline-flex rounded-full border border-emerald-300 bg-emerald-100 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-emerald-700">🚀 Siap Memulai?</p>
                    <h2 class="fade-in-up mt-6 text-4xl font-extrabold text-slate-900 md:text-5xl">Bergabunglah Dengan Perpustakaan Digital</h2>
                    <p class="fade-in-up mx-auto mt-4 max-w-2xl text-lg text-slate-600">Tingkatkan manajemen perpustakaan sekolah Anda dengan teknologi terkini. Daftar sekarang dan rasakan perbedaannya!</p>
                    
                    <div class="fade-in-up mt-8 flex flex-wrap justify-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-primary">Masuk Dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="btn-primary">Daftar Gratis</a>
                            <a href="{{ route('login') }}" class="btn-secondary">Sudah Punya Akun</a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="relative border-t border-white/20 bg-gradient-to-b from-white/50 to-sky-50/30 px-5 py-12 sm:px-8 lg:px-10 overflow-hidden">
            <svg class="ornament absolute right-0 bottom-0" style="width: 300px; height: 300px; opacity: 0.03;" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg">
                <circle cx="150" cy="150" r="140" fill="none" stroke="currentColor" stroke-width="2" class="text-sky-400"/>
                <circle cx="150" cy="150" r="100" fill="none" stroke="currentColor" stroke-width="1" class="text-sky-300"/>
            </svg>
            <div class="mx-auto max-w-7xl relative z-10">
                <div class="grid gap-8 md:grid-cols-4">
                    <div class="fade-in-up" style="animation-delay: 0.1s">
                        <div class="mb-4 inline-flex items-center gap-2 font-bold text-slate-900">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-sky-500 to-sky-600 text-sm text-white">PS</span>
                            Perpustakaan Sekolah
                        </div>
                        <p class="text-sm text-slate-600">Platform manajemen perpustakaan modern untuk sekolah Indonesia.</p>
                    </div>

                    <div class="fade-in-up" style="animation-delay: 0.2s">
                        <h4 class="font-bold text-slate-900">Produk</h4>
                        <ul class="mt-3 space-y-2 text-sm text-slate-600">
                            <li><a href="#" class="transition hover:text-sky-600">Fitur</a></li>
                            <li><a href="#" class="transition hover:text-sky-600">Harga</a></li>
                            <li><a href="#" class="transition hover:text-sky-600">Demo</a></li>
                        </ul>
                    </div>

                    <div class="fade-in-up" style="animation-delay: 0.3s">
                        <h4 class="font-bold text-slate-900">Perusahaan</h4>
                        <ul class="mt-3 space-y-2 text-sm text-slate-600">
                            <li><a href="#" class="transition hover:text-sky-600">Tentang</a></li>
                            <li><a href="#" class="transition hover:text-sky-600">Blog</a></li>
                            <li><a href="#" class="transition hover:text-sky-600">Kontak</a></li>
                        </ul>
                    </div>

                    <div class="fade-in-up" style="animation-delay: 0.4s">
                        <h4 class="font-bold text-slate-900">Legal</h4>
                        <ul class="mt-3 space-y-2 text-sm text-slate-600">
                            <li><a href="#" class="transition hover:text-sky-600">Privacy</a></li>
                            <li><a href="#" class="transition hover:text-sky-600">Terms</a></li>
                            <li><a href="#" class="transition hover:text-sky-600">Support</a></li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 border-t border-white/20 pt-8 text-center text-sm text-slate-600">
                    <p>&copy; 2026 Perpustakaan Sekolah. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
