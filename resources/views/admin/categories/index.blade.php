@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="page-title">Manajemen Kategori</h1>
            <p class="page-subtitle">Kelompokkan koleksi buku agar pencarian lebih cepat.</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn-primary">Tambah Kategori</a>
    </div>

    <div class="section-card">
        <form method="GET" action="{{ route('categories.index') }}" class="grid grid-cols-1 gap-3 md:grid-cols-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kategori" class="form-control md:col-span-3">
            <button type="submit" class="btn-primary w-full">Cari</button>
        </form>
    </div>

    <div class="table-wrap">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Nama Kategori</th>
                        <th class="px-6 py-3 text-left font-semibold">Deskripsi</th>
                        <th class="px-6 py-3 text-center font-semibold">Jumlah Buku</th>
                        <th class="px-6 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($categories as $category)
                        <tr class="hover:bg-sky-50">
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ Str::limit($category->description, 90) }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-sky-700">{{ $category->books_count }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <a href="{{ route('categories.edit', $category) }}" class="action-btn action-btn-edit">Edit</a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($categories->hasPages())
        <div>{{ $categories->links() }}</div>
    @endif
</div>
@endsection
