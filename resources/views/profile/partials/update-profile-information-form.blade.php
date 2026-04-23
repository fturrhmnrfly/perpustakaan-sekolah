<section>
    <header>
        <h2 class="text-xl font-bold text-slate-800">Informasi Profil</h2>
        <p class="mt-1 text-sm text-slate-600">Lengkapi dan perbarui data profil sesuai peran akun Anda.</p>
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
            <label for="username" class="form-label">Username</label>
            <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}" class="form-control" placeholder="Masukkan username">
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        @if ($user->role === 'siswa')
            <div>
                <label for="phone" class="form-label">Nomor Telepon</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control" placeholder="08xxxxxxxxxx">
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div>
                <label for="no_identitas" class="form-label">NIS</label>
                <input id="no_identitas" type="text" name="no_identitas" value="{{ old('no_identitas', $user->no_identitas) }}" class="form-control" placeholder="Nomor Induk Siswa">
                <x-input-error :messages="$errors->get('no_identitas')" class="mt-2" />
            </div>

            <div>
                <label for="kelas" class="form-label">Kelas</label>
                <input id="kelas" type="text" name="kelas" value="{{ old('kelas', $user->kelas) }}" class="form-control" placeholder="Contoh: X IPA 1">
                <x-input-error :messages="$errors->get('kelas')" class="mt-2" />
            </div>

            <div>
                <label for="alamat" class="form-label">Alamat</label>
                <textarea id="alamat" name="alamat" rows="3" class="form-control" placeholder="Masukkan alamat lengkap">{{ old('alamat', $user->alamat) }}</textarea>
                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
            </div>
        @else
            <div>
                <label for="no_identitas" class="form-label">NIP</label>
                <input id="no_identitas" type="text" name="no_identitas" value="{{ old('no_identitas', $user->no_identitas) }}" class="form-control" placeholder="Nomor Induk Pegawai">
                <x-input-error :messages="$errors->get('no_identitas')" class="mt-2" />
            </div>
        @endif

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
