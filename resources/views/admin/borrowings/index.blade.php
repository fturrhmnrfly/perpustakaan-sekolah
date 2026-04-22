@extends('layouts.app')

@section('title', 'Manajemen Peminjaman')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="page-title">Manajemen Peminjaman</h1>
        <p class="page-subtitle">Verifikasi permintaan peminjaman dan pengembalian siswa.</p>
    </div>

    <div class="glass-card p-5">
        <form method="GET" action="{{ route('borrowings.index') }}" class="grid grid-cols-1 gap-3 md:grid-cols-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari siswa atau judul buku" class="form-control md:col-span-2">
            <select name="status" class="form-control">
                <option value="">Semua status</option>
                <option value="menunggu_persetujuan" {{ request('status') === 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="menunggu_pengembalian" {{ request('status') === 'menunggu_pengembalian' ? 'selected' : '' }}>Menunggu Pengembalian</option>
                <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="btn-primary w-full">Filter</button>
        </form>
    </div>

    <div class="table-wrap">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Siswa</th>
                        <th class="px-6 py-3 text-left font-semibold">Buku</th>
                        <th class="px-6 py-3 text-left font-semibold">Tgl Ajukan</th>
                        <th class="px-6 py-3 text-left font-semibold">Rencana Kembali</th>
                        <th class="px-6 py-3 text-left font-semibold">Status</th>
                        <th class="px-6 py-3 text-left font-semibold">Kondisi Kembali</th>
                        <th class="px-6 py-3 text-left font-semibold">Catatan</th>
                        <th class="px-6 py-3 text-right font-semibold">Denda</th>
                        <th class="px-6 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($borrowings as $borrowing)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $borrowing->user->name }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $borrowing->book->judul }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $borrowing->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}</td>
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
                            <td class="px-6 py-4">
                                @if($borrowing->kondisi_kembali)
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $borrowing->kondisi_kembali === 'baik' ? 'bg-emerald-100 text-emerald-700' : ($borrowing->kondisi_kembali === 'rusak ringan' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                        {{ ucfirst($borrowing->kondisi_kembali) }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $borrowing->keterangan ?: '-' }}</td>
                            <td class="px-6 py-4 text-right font-semibold text-slate-900">Rp {{ number_format($borrowing->denda, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($borrowing->status === 'menunggu_persetujuan')
                                    <div class="flex flex-wrap justify-center gap-2">
                                        <form action="{{ route('borrowing.approve', $borrowing) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="action-btn action-btn-approve">Setujui</button>
                                        </form>
                                        <form action="{{ route('borrowing.reject', $borrowing) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="action-btn action-btn-reject">Tolak</button>
                                        </form>
                                    </div>
                                @elseif($borrowing->status === 'menunggu_pengembalian')
                                    <form action="{{ route('borrowing.process-return-admin', $borrowing) }}" method="POST" class="flex flex-wrap items-center justify-center gap-2">
                                        @csrf
                                        <select name="kondisi_kembali" class="rounded-lg border border-rose-100 bg-white px-2 py-1 text-xs text-slate-700">
                                            <option value="baik">Baik</option>
                                            <option value="rusak ringan">Rusak ringan</option>
                                            <option value="rusak berat">Rusak berat</option>
                                        </select>
                                        <button type="submit" class="action-btn action-btn-approve">Setujui Kembali</button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">Tidak ada aksi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-slate-500">Tidak ada data peminjaman.</td>
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
