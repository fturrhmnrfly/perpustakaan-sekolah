@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <div class="profile-highlight">
        <div class="relative z-10 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="inline-flex rounded-full border border-white/70 bg-white/70 px-3 py-1 text-xs font-bold uppercase tracking-[0.12em] text-slate-600">Admin</p>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 md:text-4xl">Dashboard Admin</h1>
                <p class="mt-2 text-sm text-slate-700 md:text-base">Ringkasan operasional perpustakaan hari ini.</p>
            </div>
            <div class="grid grid-cols-2 gap-3 text-center">
                <div class="rounded-2xl border border-white/70 bg-white/70 px-4 py-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Buku</p>
                    <p class="mt-1 text-2xl font-extrabold text-sky-700">{{ $totalBuku }}</p>
                </div>
                <div class="rounded-2xl border border-white/70 bg-white/70 px-4 py-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Siswa</p>
                    <p class="mt-1 text-2xl font-extrabold text-emerald-700">{{ $totalSiswa }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
        <div class="metric-card border-l-4 border-l-sky-400 bg-gradient-to-br from-sky-50/80 to-white">
            <p class="text-xs font-bold uppercase tracking-wide text-sky-700">Total Buku</p>
            <p class="mt-2 text-3xl font-extrabold text-sky-700">{{ $totalBuku }}</p>
        </div>
        <div class="metric-card border-l-4 border-l-emerald-400 bg-gradient-to-br from-emerald-50/80 to-white">
            <p class="text-xs font-bold uppercase tracking-wide text-emerald-700">Total Siswa</p>
            <p class="mt-2 text-3xl font-extrabold text-emerald-700">{{ $totalSiswa }}</p>
        </div>
        <div class="metric-card border-l-4 border-l-amber-400 bg-gradient-to-br from-amber-50/80 to-white">
            <p class="text-xs font-bold uppercase tracking-wide text-amber-700">Peminjaman Aktif</p>
            <p class="mt-2 text-3xl font-extrabold text-amber-700">{{ $peminjaman_aktif }}</p>
        </div>
        <div class="metric-card border-l-4 border-l-violet-400 bg-gradient-to-br from-violet-50/80 to-white">
            <p class="text-xs font-bold uppercase tracking-wide text-violet-700">Permintaan Masuk</p>
            <p class="mt-2 text-3xl font-extrabold text-violet-700">{{ $permintaan_masuk }}</p>
        </div>
        <div class="metric-card border-l-4 border-l-rose-400 bg-gradient-to-br from-rose-50/80 to-white">
            <p class="text-xs font-bold uppercase tracking-wide text-rose-700">Peminjaman Terlambat</p>
            <p class="mt-2 text-3xl font-extrabold text-rose-700">{{ $peminjaman_terlambat }}</p>
        </div>
    </div>

    <div class="table-wrap">
        <div class="flex items-center justify-between border-b border-slate-200 bg-gradient-to-r from-sky-50/80 to-transparent px-6 py-4">
            <h2 class="text-lg font-bold text-slate-900">Peminjaman Terbaru</h2>
            <span class="rounded-full border border-sky-200 bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">Live Monitor</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Siswa</th>
                        <th class="px-6 py-3 text-left font-semibold">Buku</th>
                        <th class="px-6 py-3 text-left font-semibold">Tanggal Pinjam</th>
                        <th class="px-6 py-3 text-left font-semibold">Rencana Kembali</th>
                        <th class="px-6 py-3 text-left font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($recentBorrowings as $borrowing)
                        <tr class="transition hover:bg-sky-50/60">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $borrowing->user->name }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $borrowing->book->judul }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $borrowing->tanggal_peminjaman->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if($borrowing->status === 'menunggu_persetujuan')
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Menunggu Persetujuan</span>
                                @elseif($borrowing->status === 'aktif')
                                    @if($borrowing->isOverdue())
                                        <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">Terlambat</span>
                                    @else
                                        <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">Aktif</span>
                                    @endif
                                @elseif($borrowing->status === 'menunggu_pengembalian')
                                    <span class="rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700">Menunggu Pengembalian</span>
                                @elseif($borrowing->status === 'menunggu_pembayaran')
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Menunggu Pembayaran</span>
                                @elseif($borrowing->status === 'ditolak')
                                    <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">Ditolak</span>
                                @elseif($borrowing->status === 'hilang')
                                    <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">Hilang</span>
                                @else
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada transaksi peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <a href="{{ route('books.create') }}" class="glass-card group relative overflow-hidden p-6 transition hover:-translate-y-0.5 hover:shadow-xl">
            <div class="pointer-events-none absolute -right-8 -top-8 h-24 w-24 rounded-full bg-sky-100/70 blur-xl"></div>
            <p class="text-xs font-bold uppercase tracking-wide text-sky-700">Aksi Cepat</p>
            <h3 class="mt-2 text-lg font-bold text-slate-900">Tambah Buku Baru</h3>
            <p class="mt-1 text-sm text-slate-600">Tambahkan koleksi terbaru ke sistem perpustakaan.</p>
            <span class="mt-4 inline-flex rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 transition group-hover:bg-sky-100">Buka Form</span>
        </a>
        <a href="{{ route('users.create') }}" class="glass-card group relative overflow-hidden p-6 transition hover:-translate-y-0.5 hover:shadow-xl">
            <div class="pointer-events-none absolute -right-8 -top-8 h-24 w-24 rounded-full bg-emerald-100/70 blur-xl"></div>
            <p class="text-xs font-bold uppercase tracking-wide text-emerald-700">Aksi Cepat</p>
            <h3 class="mt-2 text-lg font-bold text-slate-900">Daftarkan Siswa</h3>
            <p class="mt-1 text-sm text-slate-600">Buat akun siswa agar dapat meminjam buku.</p>
            <span class="mt-4 inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 transition group-hover:bg-emerald-100">Buka Form</span>
        </a>
    </div>
</div>
@endsection
