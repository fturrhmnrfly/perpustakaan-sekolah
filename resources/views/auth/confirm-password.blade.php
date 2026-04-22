<x-guest-layout>
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Konfirmasi Password</h1>
        <p class="mt-2 text-sm text-slate-600">Masukkan password Anda untuk melanjutkan proses aman ini.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-6 space-y-5">
        @csrf
        <div>
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <button type="submit" class="btn-primary w-full">Konfirmasi</button>
    </form>
</x-guest-layout>
