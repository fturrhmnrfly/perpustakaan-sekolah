<x-guest-layout>
    <div class="auth-shell">
        <div>
            <span class="inline-flex rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700">Selamat Datang Kembali</span>
            <h1 class="auth-title mt-3">Masuk ke Akun</h1>
            <p class="auth-subtitle">Akses dashboard perpustakaan dengan cepat dan aman.</p>
        </div>

        <x-auth-session-status class="mt-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-control" placeholder="contoh@email.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control" placeholder="Masukkan password">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-600">
                    <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    Ingat saya
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-sky-700 hover:text-sky-800">Lupa password?</a>
                @endif
            </div>

            <button type="submit" class="btn-primary w-full">Login</button>

            <p class="text-center text-sm text-slate-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-sky-700 hover:text-sky-800">Daftar di sini</a>
            </p>
        </form>
    </div>
</x-guest-layout>
