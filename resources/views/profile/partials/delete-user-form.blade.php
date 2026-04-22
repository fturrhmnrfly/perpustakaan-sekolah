<section>
    <header>
        <h2 class="text-xl font-bold text-slate-800">Hapus Akun</h2>
        <p class="mt-1 text-sm text-slate-600">Tindakan ini permanen. Semua data akun akan terhapus.</p>
    </header>

    <form method="POST" action="{{ route('profile.destroy') }}" class="mt-6 space-y-5">
        @csrf
        @method('DELETE')

        <div>
            <label for="delete_password" class="form-label">Masukkan Password untuk Konfirmasi</label>
            <input id="delete_password" type="password" name="password" class="form-control" required>
            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
        </div>

        <button type="submit" class="btn-danger" onclick="return confirm('Yakin ingin menghapus akun ini secara permanen?')">
            Hapus Akun Permanen
        </button>
    </form>
</section>
