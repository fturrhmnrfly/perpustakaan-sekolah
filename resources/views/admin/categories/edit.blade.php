@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="page-title">Edit Kategori</h1>
        <p class="page-subtitle">Perbarui informasi kategori buku.</p>
    </div>

    <div class="section-card w-full">
        <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label for="name" class="form-label">Nama Kategori</label>
                <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control" required>
                @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="form-control">{{ old('description', $category->description) }}</textarea>
                @error('description') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-col gap-3 border-t border-rose-100 pt-4 sm:flex-row">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <a href="{{ route('categories.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

