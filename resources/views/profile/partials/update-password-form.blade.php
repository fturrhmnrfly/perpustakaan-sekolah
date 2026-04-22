<section>
    <header>
        <h2 class="text-xl font-bold text-slate-800">Ubah Password</h2>
        <p class="mt-1 text-sm text-slate-600">Gunakan password yang kuat untuk keamanan akun.</p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="current_password" class="form-label">Password Saat Ini</label>
            <input id="current_password" type="password" name="current_password" class="form-control" autocomplete="current-password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="form-label">Password Baru</label>
            <input id="password" type="password" name="password" class="form-control" autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="btn-primary">Simpan Password</button>
            @if (session('status') === 'password-updated')
                <span class="text-sm text-emerald-700">Password diperbarui.</span>
            @endif
        </div>
    </form>
</section>
