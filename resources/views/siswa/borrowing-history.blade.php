@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="page-title">Riwayat Peminjaman</h1>
            <p class="page-subtitle">Lihat seluruh transaksi peminjaman dan pengembalian buku kamu.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('borrowing.history.print') }}" target="_blank" class="action-btn action-btn-view">Cetak</a>
            <a href="{{ route('borrowing.history.pdf') }}" class="action-btn action-btn-approve">Unduh PDF</a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="metric-card">
            <p class="text-sm font-semibold text-slate-500">Total Peminjaman</p>
            <p class="mt-2 text-3xl font-extrabold text-sky-700">{{ $borrowingsCount }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold text-slate-500">Sedang Dipinjam</p>
            <p class="mt-2 text-3xl font-extrabold text-amber-700">{{ $activeBorrowingsCount }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold text-slate-500">Total Denda</p>
            @if($totalFines > 0)
                <p class="mt-2 text-3xl font-extrabold text-rose-700">Rp {{ number_format($totalFines, 0, ',', '.') }}</p>
            @else
                <p class="mt-2 text-2xl font-extrabold text-emerald-700">Tidak ada</p>
            @endif
        </div>
    </div>

    <div class="table-wrap">
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
                    @forelse($borrowings as $borrowing)
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
                                @if($borrowing->denda > 0 && $borrowing->fine_payment_status !== 'paid')
                                    <span class="status-badge status-pending">Menunggu Pembayaran</span>
                                @elseif($borrowing->status === 'menunggu_persetujuan')
                                    <span class="status-badge status-pending">Menunggu Persetujuan</span>
                                @elseif($borrowing->status === 'aktif')
                                    <span class="status-badge status-active">Aktif</span>
                                @elseif($borrowing->status === 'menunggu_pengembalian')
                                    <span class="status-badge status-return-pending">Menunggu Pengembalian</span>
                                @elseif($borrowing->status === 'menunggu_pembayaran')
                                    <span class="status-badge status-pending">Menunggu Pembayaran</span>
                                @elseif($borrowing->status === 'dikembalikan')
                                    <span class="status-badge status-returned">Dikembalikan</span>
                                @elseif($borrowing->status === 'hilang')
                                    <span class="status-badge status-rejected">Hilang</span>
                                @else
                                    <span class="status-badge status-rejected">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-slate-900">Rp {{ number_format($borrowing->denda, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($borrowing->denda > 0)
                                    @if($borrowing->fine_payment_status === 'paid')
                                        <span class="status-badge status-returned">Lunas</span>
                                    @elseif(in_array($borrowing->status, ['menunggu_pembayaran', 'dikembalikan', 'hilang'], true))
                                        <a href="{{ route('borrowing.fine-payment', $borrowing) }}" class="action-btn action-btn-approve">Bayar Sekarang</a>
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

    @if($borrowings->hasPages())
        <div>{{ $borrowings->links() }}</div>
    @endif
</div>
@endsection
