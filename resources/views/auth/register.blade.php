<x-guest-layout>
    <div class="auth-shell">
        <div>
            <span class="inline-flex rounded-full border border-violet-200 bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-700">Akun Baru</span>
            <h1 class="auth-title mt-3">Daftar Akun Siswa</h1>
            <p class="auth-subtitle">Buat akun untuk meminjam buku, memantau status, dan melihat riwayat transaksi.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="form-control" placeholder="Nama lengkap">
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="form-control" placeholder="contoh@email.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control" placeholder="Minimal 8 karakter">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control" placeholder="Ulangi password">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
                </div>
            </div>

            <button type="submit" class="btn-primary w-full">Buat Akun</button>

            <p class="text-center text-sm text-slate-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-sky-700 hover:text-sky-800">Login di sini</a>
            </p>
        </form>
    </div>
</x-guest-layout>
