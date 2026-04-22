@extends('layouts.app')

@section('title', 'Kembalikan Buku')

@section('content')
@php
    $isLate = now()->toDateString() > $borrowing->tanggal_kembali_rencana;
    $daysLate = $isLate ? \Carbon\Carbon::parse($borrowing->tanggal_kembali_rencana)->diffInDays(now()) : 0;
    $estimatedFine = $daysLate * 5000;
@endphp

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <aside class="glass-card p-6">
        <img src="{{ $borrowing->book->cover_url }}" alt="Cover {{ $borrowing->book->judul }}" class="mb-4 h-48 w-36 rounded-2xl border border-rose-100 object-cover">
        <h2 class="text-xl font-bold text-slate-900">Detail Peminjaman</h2>
        <div class="mt-4 space-y-2 text-sm text-slate-600">
            <p><span class="font-semibold text-slate-800">Judul:</span> {{ $borrowing->book->judul }}</p>
            <p><span class="font-semibold text-slate-800">Pengarang:</span> {{ $borrowing->book->pengarang }}</p>
            <p><span class="font-semibold text-slate-800">Tanggal Pinjam:</span> {{ $borrowing->tanggal_peminjaman->format('d M Y') }}</p>
            <p><span class="font-semibold text-slate-800">Rencana Kembali:</span> {{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}</p>
        </div>

        <div class="mt-5 rounded-xl p-4 {{ $estimatedFine > 0 ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700' }}">
            @if($estimatedFine > 0)
                <p class="text-sm font-semibold">Estimasi Denda: Rp {{ number_format($estimatedFine, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs">{{ $daysLate }} hari terlambat x Rp 5.000</p>
            @else
                <p class="text-sm font-semibold">Tidak ada denda keterlambatan.</p>
            @endif
        </div>
    </aside>

    <section class="glass-card p-6 lg:col-span-2">
        <h1 class="text-2xl font-extrabold text-slate-900">Form Pengajuan Pengembalian</h1>
        <p class="mt-2 text-sm text-slate-600">Setelah diajukan, admin akan memverifikasi kondisi buku dan menyetujui pengembalian.</p>

        @if($borrowing->status === 'menunggu_pengembalian')
            <div class="mt-6 rounded-2xl border border-violet-200 bg-violet-50 p-5">
                <p class="text-sm font-semibold text-violet-700">Pengajuan pengembalian sudah dikirim.</p>
                <p class="mt-1 text-sm text-violet-600">Admin akan memverifikasi buku dan menyelesaikan transaksi.</p>
            </div>
        @else
            <form action="{{ route('borrowing.process-return', $borrowing) }}" method="POST" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="keterangan" class="mb-2 block text-sm font-semibold text-slate-700">Catatan untuk Admin (Opsional)</label>
                    <textarea id="keterangan" name="keterangan" rows="4" class="form-control" placeholder="Contoh: Buku sudah saya serahkan ke meja petugas.">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-2 text-sm text-rose-700">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-200 pt-4 sm:flex-row">
                    <button type="submit" class="btn-primary">Ajukan Pengembalian</button>
                    <a href="{{ route('dashboard') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        @endif
    </section>
</div>
@endsection
