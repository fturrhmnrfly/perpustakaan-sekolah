<x-guest-layout>
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Lupa Password</h1>
        <p class="mt-2 text-sm text-slate-600">Masukkan email akun Anda, kami akan mengirim tautan reset password.</p>
    </div>

    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
        @csrf
        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <button type="submit" class="btn-primary w-full">Kirim Link Reset</button>
    </form>
</x-guest-layout>
