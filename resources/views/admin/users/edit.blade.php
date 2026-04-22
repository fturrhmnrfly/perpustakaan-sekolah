@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="page-title">Edit Siswa</h1>
        <p class="page-subtitle">Perbarui data siswa dan kredensial akun.</p>
    </div>

    <div class="section-card w-full">
        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label for="name" class="form-label">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                @error('email') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="no_identitas" class="form-label">Nomor Identitas (NIS)</label>
                <input id="no_identitas" type="text" name="no_identitas" value="{{ old('no_identitas', $user->no_identitas) }}" class="form-control" required>
                @error('no_identitas') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="phone" class="form-label">Nomor Telepon</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                @error('phone') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="password" class="form-label">Password Baru (opsional)</label>
                    <input id="password" type="password" name="password" class="form-control">
                    @error('password') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control">
                </div>
            </div>

            <div class="flex flex-col gap-3 border-t border-rose-100 pt-4 sm:flex-row">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <a href="{{ route('users.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

