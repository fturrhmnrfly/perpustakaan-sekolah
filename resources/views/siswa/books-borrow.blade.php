@extends('layouts.app')

@section('title', 'Pinjam Buku')

@section('content')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <aside class="glass-card p-6">
        <img src="{{ $book->cover_url }}" alt="Cover {{ $book->judul }}" class="mb-4 h-48 w-36 rounded-2xl border border-rose-100 object-cover">
        <h2 class="text-xl font-bold text-slate-900">Detail Buku</h2>
        <div class="mt-4 space-y-2 text-sm text-slate-600">
            <p><span class="font-semibold text-slate-800">Judul:</span> {{ $book->judul }}</p>
            <p><span class="font-semibold text-slate-800">Pengarang:</span> {{ $book->pengarang }}</p>
            <p><span class="font-semibold text-slate-800">Penerbit:</span> {{ $book->penerbit }}</p>
            <p><span class="font-semibold text-slate-800">Tahun:</span> {{ $book->tahun_terbit }}</p>
            <p><span class="font-semibold text-slate-800">Kategori:</span> {{ $book->category->name ?? '-' }}</p>
            <p><span class="font-semibold text-slate-800">Stok Tersedia:</span> {{ $book->stok_tersedia }}</p>
        </div>
    </aside>

    <section class="glass-card p-6 lg:col-span-2">
        <h1 class="text-2xl font-extrabold text-slate-900">Form Pengajuan Peminjaman</h1>
        <p class="mt-2 text-sm text-slate-600">Setelah submit, permintaan akan diverifikasi admin terlebih dahulu. Maksimal transaksi aktif/pending adalah 3 buku.</p>

        <form action="{{ route('borrowings.store', $book) }}" method="POST" class="mt-6 space-y-5">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Peminjam</label>
                <input type="text" value="{{ auth()->user()->name }} ({{ auth()->user()->email }})" disabled class="form-control bg-slate-100">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Peminjaman</label>
                <input type="date" value="{{ now()->format('Y-m-d') }}" disabled class="form-control bg-slate-100">
            </div>

            <div>
                <label for="tanggal_kembali_rencana" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Kembali Rencana</label>
                <input id="tanggal_kembali_rencana" type="date" name="tanggal_kembali_rencana" value="{{ old('tanggal_kembali_rencana', now()->addDays(7)->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" class="form-control">
                @error('tanggal_kembali_rencana')
                    <p class="mt-2 text-sm text-rose-700">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-200 pt-4 sm:flex-row">
                <button type="submit" class="btn-primary">Ajukan Peminjaman</button>
                <a href="{{ route('books.search') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </section>
</div>
@endsection
