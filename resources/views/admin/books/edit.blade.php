@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="page-title">Edit Buku</h1>
        <p class="page-subtitle">Perbarui detail buku dan stok yang tersedia.</p>
    </div>

    <div class="section-card w-full">
        <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="judul" class="form-label">Judul</label>
                    <input id="judul" type="text" name="judul" value="{{ old('judul', $book->judul) }}" class="form-control" required>
                </div>

                <div>
                    <label for="pengarang" class="form-label">Pengarang</label>
                    <input id="pengarang" type="text" name="pengarang" value="{{ old('pengarang', $book->pengarang) }}" class="form-control" required>
                </div>

                <div>
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input id="penerbit" type="text" name="penerbit" value="{{ old('penerbit', $book->penerbit) }}" class="form-control" required>
                </div>

                <div>
                    <label for="isbn" class="form-label">ISBN</label>
                    <input id="isbn" type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" class="form-control" required>
                </div>

                <div>
                    <label for="category_id" class="form-label">Kategori</label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                    <input id="tahun_terbit" type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $book->tahun_terbit) }}" class="form-control" required>
                </div>

                <div>
                    <label for="stok" class="form-label">Stok</label>
                    <input id="stok" type="number" name="stok" value="{{ old('stok', $book->stok) }}" class="form-control" required>
                </div>

                <div>
                    <label for="kondisi" class="form-label">Kondisi</label>
                    <select id="kondisi" name="kondisi" class="form-control" required>
                        <option value="">Pilih kondisi</option>
                        <option value="baik" {{ old('kondisi', $book->kondisi) === 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak ringan" {{ old('kondisi', $book->kondisi) === 'rusak ringan' ? 'selected' : '' }}>Rusak ringan</option>
                        <option value="rusak berat" {{ old('kondisi', $book->kondisi) === 'rusak berat' ? 'selected' : '' }}>Rusak berat</option>
                    </select>
                </div>

                <div>
                    <label for="lokasi" class="form-label">Lokasi Rak</label>
                    <input id="lokasi" type="text" name="lokasi" value="{{ old('lokasi', $book->lokasi) }}" class="form-control">
                </div>

                <div>
                    <label for="cover_image" class="form-label">Foto Cover Buku</label>
                    <input id="cover_image" type="file" name="cover_image" accept="image/*" class="form-control">
                    <p class="form-hint">Kosongkan jika tidak ingin mengganti cover.</p>
                </div>

                @if($book->cover_image)
                    <div>
                        <label class="form-label">Cover Saat Ini</label>
                        <img src="{{ $book->cover_url }}" alt="Cover {{ $book->judul }}" class="h-28 w-20 rounded-xl border border-rose-100 object-cover">
                    </div>
                @endif

                <div class="md:col-span-2">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" class="form-control">{{ old('deskripsi', $book->deskripsi) }}</textarea>
                </div>
            </div>

            <div class="flex flex-col gap-3 border-t border-rose-100 pt-4 sm:flex-row">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <a href="{{ route('books.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
