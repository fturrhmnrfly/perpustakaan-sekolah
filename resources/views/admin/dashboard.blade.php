@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="page-title">Dashboard Admin</h1>
        <p class="page-subtitle">Ringkasan operasional perpustakaan hari ini.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
        <div class="metric-card">
            <p class="text-sm font-semibold text-slate-500">Total Buku</p>
            <p class="mt-2 text-3xl font-extrabold text-sky-700">{{ $totalBuku }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold text-slate-500">Total Siswa</p>
            <p class="mt-2 text-3xl font-extrabold text-emerald-700">{{ $totalSiswa }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold text-slate-500">Peminjaman Aktif</p>
            <p class="mt-2 text-3xl font-extrabold text-amber-700">{{ $peminjaman_aktif }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold text-slate-500">Permintaan Masuk</p>
            <p class="mt-2 text-3xl font-extrabold text-violet-700">{{ $permintaan_masuk }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold text-slate-500">Peminjaman Terlambat</p>
            <p class="mt-2 text-3xl font-extrabold text-rose-700">{{ $peminjaman_terlambat }}</p>
        </div>
    </div>

    <div class="table-wrap">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-lg font-bold text-slate-900">Peminjaman Terbaru</h2>
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
                        <tr class="hover:bg-slate-50/80">
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
                                @elseif($borrowing->status === 'ditolak')
                                    <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">Ditolak</span>
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
        <a href="{{ route('books.create') }}" class="glass-card p-6 transition hover:-translate-y-0.5 hover:shadow-xl">
            <p class="text-sm font-semibold text-sky-700">Aksi Cepat</p>
            <h3 class="mt-2 text-lg font-bold text-slate-900">Tambah Buku Baru</h3>
            <p class="mt-1 text-sm text-slate-600">Tambahkan koleksi terbaru ke sistem perpustakaan.</p>
        </a>
        <a href="{{ route('users.create') }}" class="glass-card p-6 transition hover:-translate-y-0.5 hover:shadow-xl">
            <p class="text-sm font-semibold text-emerald-700">Aksi Cepat</p>
            <h3 class="mt-2 text-lg font-bold text-slate-900">Daftarkan Siswa</h3>
            <p class="mt-1 text-sm text-slate-600">Buat akun siswa agar dapat meminjam buku.</p>
        </a>
    </div>
</div>
@endsection
