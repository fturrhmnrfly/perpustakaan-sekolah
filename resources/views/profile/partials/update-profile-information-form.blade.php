<section>
    <header>
        <h2 class="text-xl font-bold text-slate-800">Informasi Profil</h2>
        <p class="mt-1 text-sm text-slate-600">Perbarui nama dan email akun Anda.</p>
    </header>

    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('PATCH')

        <div>
            <label for="name" class="form-label">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required autofocus>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <p class="text-sm text-amber-700">
                Email belum terverifikasi.
                <button form="send-verification" class="ml-1 font-semibold underline">Kirim ulang verifikasi</button>
            </p>
        @endif

        <div class="flex items-center gap-3">
            <button type="submit" class="btn-primary">Simpan Profil</button>
            @if (session('status') === 'profile-updated')
                <span class="text-sm text-emerald-700">Tersimpan.</span>
            @endif
        </div>
    </form>
</section>
