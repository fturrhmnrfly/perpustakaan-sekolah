@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="page-title">{{ $book->judul }}</h1>
        <p class="page-subtitle">Detail lengkap buku dalam katalog perpustakaan.</p>
    </div>

    <div class="section-card w-full">
        <div class="mb-6">
            <p class="form-label">Cover Buku</p>
            <img src="{{ $book->cover_url }}" alt="Cover {{ $book->judul }}" class="h-48 w-36 rounded-2xl border border-rose-100 object-cover shadow-sm">
        </div>

        <dl class="grid grid-cols-1 gap-x-8 gap-y-4 md:grid-cols-2">
            <div><dt class="form-label">Judul</dt><dd class="text-slate-700">{{ $book->judul }}</dd></div>
            <div><dt class="form-label">Pengarang</dt><dd class="text-slate-700">{{ $book->pengarang }}</dd></div>
            <div><dt class="form-label">Penerbit</dt><dd class="text-slate-700">{{ $book->penerbit }}</dd></div>
            <div><dt class="form-label">ISBN</dt><dd class="text-slate-700">{{ $book->isbn }}</dd></div>
            <div><dt class="form-label">Kategori</dt><dd class="text-slate-700">{{ $book->category->name ?? '-' }}</dd></div>
            <div><dt class="form-label">Tahun Terbit</dt><dd class="text-slate-700">{{ $book->tahun_terbit }}</dd></div>
            <div><dt class="form-label">Stok</dt><dd class="text-slate-700">{{ $book->stok }}</dd></div>
            <div><dt class="form-label">Stok Tersedia</dt><dd class="text-slate-700">{{ $book->stok_tersedia }}</dd></div>
            <div><dt class="form-label">Kondisi</dt><dd class="text-slate-700">{{ ucfirst($book->kondisi) }}</dd></div>
            <div><dt class="form-label">Lokasi</dt><dd class="text-slate-700">{{ $book->lokasi ?? '-' }}</dd></div>
            <div class="md:col-span-2"><dt class="form-label">Deskripsi</dt><dd class="text-slate-600">{{ $book->deskripsi ?: '-' }}</dd></div>
        </dl>

        <div class="mt-6 flex flex-col gap-3 border-t border-rose-100 pt-4 sm:flex-row">
            <a href="{{ route('books.edit', $book) }}" class="btn-primary">Edit Buku</a>
            <a href="{{ route('books.index') }}" class="btn-secondary">Kembali</a>
            <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Hapus Buku</button>
            </form>
        </div>
    </div>
</div>
@endsection
