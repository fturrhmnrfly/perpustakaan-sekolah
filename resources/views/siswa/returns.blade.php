@extends('layouts.app')

@section('title', 'Pengembalian Buku')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="page-title">Pengembalian Buku</h1>
            <p class="page-subtitle">Ajukan pengembalian buku aktif dan pantau riwayat peminjaman kamu di satu halaman.</p>
        </div>
        <a href="{{ route('borrowing.history') }}" class="action-btn action-btn-history w-fit">Lihat Riwayat Lengkap</a>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="metric-card">
            <p class="text-sm font-semibold text-slate-500">Bisa Diajukan</p>
            <p class="mt-2 text-3xl font-extrabold text-sky-700">{{ $returnableCount }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold text-slate-500">Menunggu Verifikasi</p>
            <p class="mt-2 text-3xl font-extrabold text-violet-700">{{ $waitingApprovalCount }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold text-slate-500">Total Aktif</p>
            <p class="mt-2 text-3xl font-extrabold text-emerald-700">{{ $borrowings->total() }}</p>
        </div>
    </div>

    @if($borrowings->count() > 0)
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($borrowings as $borrowing)
                <article class="glass-card p-5">
                    <div class="flex gap-4">
                        <img src="{{ $borrowing->book->cover_url }}" alt="Cover {{ $borrowing->book->judul }}" class="h-24 w-16 rounded-lg border border-rose-100 object-cover">
                        <div class="min-w-0 flex-1">
                            <h3 class="line-clamp-2 text-base font-bold text-slate-900">{{ $borrowing->book->judul }}</h3>
                            <p class="mt-1 text-sm text-slate-600">{{ $borrowing->book->pengarang }}</p>
                            <p class="mt-2 text-xs text-slate-500">Rencana kembali: {{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}</p>
                        </div>
                    </div>

                    @if($borrowing->status === 'menunggu_pengembalian')
                        <div class="mt-4 rounded-xl border border-violet-200 bg-violet-50 px-4 py-3 text-sm font-semibold text-violet-700">
                            Pengembalian sudah diajukan. Menunggu persetujuan admin.
                        </div>
                    @else
                        <a href="{{ route('borrowing.return', $borrowing) }}" class="btn-primary mt-4 w-full">Ajukan Pengembalian</a>
                    @endif
                </article>
            @endforeach
        </div>

        @if($borrowings->hasPages())
            <div>{{ $borrowings->links() }}</div>
        @endif
    @else
        <div class="glass-card p-10 text-center">
            <p class="text-slate-600">Belum ada buku aktif yang bisa diajukan pengembalian.</p>
            <a href="{{ route('books.search') }}" class="btn-primary mt-4">Cari Buku</a>
        </div>
    @endif

    <div class="table-wrap">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-lg font-bold text-slate-900">Riwayat Peminjaman Terakhir</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Judul Buku</th>
                        <th class="px-6 py-3 text-left font-semibold">Tgl Pinjam</th>
                        <th class="px-6 py-3 text-left font-semibold">Rencana Kembali</th>
                        <th class="px-6 py-3 text-left font-semibold">Status</th>
                        <th class="px-6 py-3 text-right font-semibold">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($recentHistory as $history)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $history->book->judul }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $history->tanggal_peminjaman->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $history->tanggal_kembali_rencana->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if($history->status === 'menunggu_persetujuan')
                                    <span class="status-badge status-pending">Menunggu Persetujuan</span>
                                @elseif($history->status === 'aktif')
                                    <span class="status-badge status-active">Aktif</span>
                                @elseif($history->status === 'menunggu_pengembalian')
                                    <span class="status-badge status-return-pending">Menunggu Pengembalian</span>
                                @elseif($history->status === 'menunggu_pembayaran')
                                    <span class="status-badge status-pending">Menunggu Pembayaran</span>
                                @elseif($history->status === 'dikembalikan')
                                    <span class="status-badge status-returned">Dikembalikan</span>
                                @elseif($history->status === 'hilang')
                                    <span class="status-badge status-rejected">Hilang</span>
                                @else
                                    <span class="status-badge status-rejected">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-slate-900">Rp {{ number_format($history->denda, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada riwayat peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
