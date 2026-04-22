@extends('layouts.app')

@section('title', 'Manajemen Siswa')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="page-title">Manajemen Siswa</h1>
            <p class="page-subtitle">Kelola data siswa dan lihat riwayat peminjaman mereka.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn-primary">Tambah Siswa</a>
    </div>

    <div class="section-card">
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 gap-3 md:grid-cols-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau NIS" class="form-control md:col-span-3">
            <button type="submit" class="btn-primary w-full">Cari</button>
        </form>
    </div>

    <div class="table-wrap">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Nama</th>
                        <th class="px-6 py-3 text-left font-semibold">Email</th>
                        <th class="px-6 py-3 text-left font-semibold">NIS</th>
                        <th class="px-6 py-3 text-left font-semibold">Telepon</th>
                        <th class="px-6 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-sky-50">
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->no_identitas }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <a href="{{ route('users.edit', $user) }}" class="action-btn action-btn-edit">Edit</a>
                                    <a href="{{ route('users.borrowing-history', $user) }}" class="action-btn action-btn-history">Riwayat</a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($users->hasPages())
        <div>{{ $users->links() }}</div>
    @endif
</div>
@endsection
