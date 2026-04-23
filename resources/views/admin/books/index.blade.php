@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="page-title">Manajemen Buku</h1>
            <p class="page-subtitle">Kelola data koleksi dan stok buku perpustakaan.</p>
        </div>
        <a href="{{ route('books.create') }}" class="btn-primary">Tambah Buku</a>
    </div>

    <div class="glass-card p-5">
        <form method="GET" class="grid grid-cols-1 gap-3 lg:grid-cols-4">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul, pengarang, ISBN" class="form-control lg:col-span-2">
            <select name="category" class="form-control">
                <option value="">Semua kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary w-full">Cari</button>
        </form>
    </div>

    <div class="table-wrap">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Cover</th>
                        <th class="px-6 py-3 text-left font-semibold">Judul</th>
                        <th class="px-6 py-3 text-left font-semibold">Pengarang</th>
                        <th class="px-6 py-3 text-left font-semibold">ISBN</th>
                        <th class="px-6 py-3 text-left font-semibold">Kategori</th>
                        <th class="px-6 py-3 text-center font-semibold">Stok</th>
                        <th class="px-6 py-3 text-center font-semibold">Tersedia</th>
                        <th class="px-6 py-3 text-left font-semibold">Kondisi</th>
                        <th class="px-6 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($books as $book)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-6 py-4">
                                <img src="{{ $book->cover_url }}" alt="Cover {{ $book->judul }}" class="h-14 w-11 rounded-lg border border-slate-200 object-cover">
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $book->judul }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $book->pengarang }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $book->isbn }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $book->category->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-slate-900">{{ $book->stok }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $book->stok_tersedia > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $book->stok_tersedia }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $book->kondisi === 'baik' ? 'bg-emerald-100 text-emerald-700' : ($book->kondisi === 'rusak ringan' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                    {{ ucfirst($book->kondisi) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <a href="{{ route('books.show', $book) }}" class="action-btn action-btn-view">Detail</a>
                                    <a href="{{ route('books.edit', $book) }}" class="action-btn action-btn-edit">Edit</a>
                                    <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-slate-500">Belum ada buku yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($books->hasPages())
        <div>{{ $books->links() }}</div>
    @endif
</div>
@endsection
