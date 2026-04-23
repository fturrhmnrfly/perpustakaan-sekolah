@extends('layouts.app')

@section('title', 'Cari Buku')

@section('content')
<div class="space-y-4">
    <div>
        <h1 class="text-xl font-extrabold tracking-tight text-slate-800 md:text-2xl">Cari Buku</h1>
        <p class="mt-1 text-sm text-slate-600">Temukan buku yang tersedia dan ajukan peminjaman secara langsung.</p>
    </div>

    <div class="glass-card p-3">
        <form method="GET" action="{{ route('books.search') }}" class="grid grid-cols-1 gap-3 md:grid-cols-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau pengarang" class="form-control !py-2 !text-sm md:col-span-1">
            <select name="category" class="form-control !py-2 !text-sm">
                <option value="">Semua kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary-sm w-full">Cari Buku</button>
        </form>
    </div>

    <p class="text-xs text-slate-600">Menampilkan {{ $books->count() }} dari total {{ $books->total() }} buku tersedia.</p>

    @if($books->count() > 0)
        <div class="grid grid-cols-2 gap-2 md:grid-cols-4 xl:grid-cols-6">
            @foreach($books as $book)
                <article class="glass-card flex h-full flex-col overflow-hidden p-0">
                    <div class="relative aspect-[3/4] w-full overflow-hidden bg-sky-50">
                        <img
                            src="{{ $book->cover_url }}"
                            alt="Sampul buku {{ $book->judul }}"
                            class="h-full w-full object-cover transition duration-300 hover:scale-105"
                            loading="lazy"
                        >
                        <span class="absolute right-2 top-2 rounded-full bg-white/90 px-2 py-0.5 text-[10px] font-semibold text-slate-700">
                            {{ $book->category->name ?? 'Umum' }}
                        </span>
                    </div>

                    <div class="flex flex-1 flex-col p-2.5">
                        <h3 class="line-clamp-2 text-sm font-bold text-slate-900">{{ $book->judul }}</h3>
                        <p class="mt-1 line-clamp-1 text-xs text-slate-600">{{ $book->pengarang }}</p>
                        <div class="mt-2 min-h-[60px] space-y-0.5 text-[11px] text-slate-600">
                            <p class="truncate">Penerbit: {{ $book->penerbit }}</p>
                            <p>Tahun: {{ $book->tahun_terbit }}</p>
                            <p>
                                Stok tersedia:
                                <span class="font-semibold {{ $book->stok_tersedia > 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ $book->stok_tersedia }}
                                </span>
                            </p>
                        </div>
                        <a href="{{ route('books.borrow', $book) }}" class="btn-primary-sm mt-auto w-full">Pinjam</a>
                    </div>
                </article>
            @endforeach
        </div>

        @if($books->hasPages())
            <div>{{ $books->links() }}</div>
        @endif
    @else
        <div class="glass-card p-8 text-center text-slate-600">Tidak ada buku yang cocok dengan pencarian.</div>
    @endif
</div>
@endsection
