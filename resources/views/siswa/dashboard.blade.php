@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="page-title">Dashboard Siswa</h1>
        <p class="page-subtitle">Selamat datang, {{ auth()->user()->name }}. Pantau peminjaman kamu di sini.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div class="metric-card">
            <p class="text-sm font-semibold text-slate-500">Buku Dipinjam</p>
            <p class="mt-2 text-3xl font-extrabold text-sky-700">{{ $activeBorrowingsCount }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold text-slate-500">Menunggu Persetujuan</p>
            <p class="mt-2 text-3xl font-extrabold text-amber-700">{{ $pendingBorrowingsCount }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold text-slate-500">Total Denda</p>
            <p class="mt-2 text-3xl font-extrabold text-rose-700">Rp {{ number_format($totalFines, 0, ',', '.') }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold text-slate-500">Buku Tersedia</p>
            <p class="mt-2 text-3xl font-extrabold text-emerald-700">{{ $availableBooksCount }}</p>
            <a href="{{ route('books.search') }}" class="mt-3 inline-flex text-sm font-semibold text-sky-700 hover:text-sky-800">Lihat katalog</a>
        </div>
    </div>

    <div class="table-wrap">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-lg font-bold text-slate-900">Riwayat Terakhir</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Judul Buku</th>
                        <th class="px-6 py-3 text-left font-semibold">Pengarang</th>
                        <th class="px-6 py-3 text-left font-semibold">Tgl Pinjam</th>
                        <th class="px-6 py-3 text-left font-semibold">Rencana Kembali</th>
                        <th class="px-6 py-3 text-left font-semibold">Tgl Kembali</th>
                        <th class="px-6 py-3 text-left font-semibold">Kondisi Kembali</th>
                        <th class="px-6 py-3 text-left font-semibold">Status</th>
                        <th class="px-6 py-3 text-right font-semibold">Denda</th>
                        <th class="px-6 py-3 text-center font-semibold">Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($recentBorrowings as $borrowing)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $borrowing->book->judul }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $borrowing->book->pengarang }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $borrowing->tanggal_peminjaman->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $borrowing->tanggal_kembali_aktual ? $borrowing->tanggal_kembali_aktual->format('d M Y') : '-' }}</td>
                            <td class="px-6 py-4">
                                @if($borrowing->kondisi_kembali)
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $borrowing->kondisi_kembali === 'baik' ? 'bg-emerald-100 text-emerald-700' : ($borrowing->kondisi_kembali === 'rusak ringan' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                        {{ ucfirst($borrowing->kondisi_kembali) }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($borrowing->status === 'menunggu_persetujuan')
                                    <span class="status-badge status-pending">Menunggu Persetujuan</span>
                                @elseif($borrowing->status === 'aktif')
                                    <span class="status-badge status-active">Aktif</span>
                                @elseif($borrowing->status === 'menunggu_pengembalian')
                                    <span class="status-badge status-return-pending">Menunggu Pengembalian</span>
                                @elseif($borrowing->status === 'dikembalikan')
                                    <span class="status-badge status-returned">Dikembalikan</span>
                                @else
                                    <span class="status-badge status-rejected">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-slate-900">Rp {{ number_format($borrowing->denda, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($borrowing->denda > 0)
                                    @if($borrowing->fine_payment_status === 'paid')
                                        <span class="status-badge status-returned">Lunas</span>
                                    @elseif($borrowing->status === 'dikembalikan')
                                        <a href="{{ route('borrowing.fine-payment', $borrowing) }}" class="action-btn action-btn-approve">Bayar QRIS</a>
                                    @else
                                        <span class="text-xs text-slate-400">Menunggu selesai</span>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-slate-500">Belum ada riwayat peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
