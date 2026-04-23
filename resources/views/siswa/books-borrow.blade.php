@extends('layouts.app')

@section('title', 'Pinjam Buku')

@section('content')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <aside class="glass-card p-6">
        <div class="rounded-2xl border border-rose-100 bg-white/80 p-4 shadow-sm">
            <img src="{{ $book->cover_url }}" alt="Cover {{ $book->judul }}" class="mx-auto h-80 w-60 rounded-xl border border-rose-100 object-cover shadow-sm">
            <div class="mt-4 flex justify-center">
                @if($book->stok_tersedia > 0)
                    <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                        Tersedia: {{ $book->stok_tersedia }} buku
                    </span>
                @else
                    <span class="rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700">
                        Stok sedang habis
                    </span>
                @endif
            </div>
        </div>

        <dl class="mt-5 divide-y divide-rose-100 overflow-hidden rounded-2xl border border-rose-100 bg-white/70">
            <div class="grid grid-cols-3 gap-3 px-4 py-3">
                <dt class="col-span-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Penerbit</dt>
                <dd class="col-span-2 text-sm text-slate-700">{{ $book->penerbit }}</dd>
            </div>
            <div class="grid grid-cols-3 gap-3 px-4 py-3">
                <dt class="col-span-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Tahun</dt>
                <dd class="col-span-2 text-sm text-slate-700">{{ $book->tahun_terbit }}</dd>
            </div>
            <div class="grid grid-cols-3 gap-3 px-4 py-3">
                <dt class="col-span-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Kategori</dt>
                <dd class="col-span-2 text-sm text-slate-700">{{ $book->category->name ?? '-' }}</dd>
            </div>
        </dl>
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
