<x-guest-layout>
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Reset Password</h1>
        <p class="mt-2 text-sm text-slate-600">Atur password baru untuk akun Anda.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" class="form-control" required autofocus>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="form-label">Password Baru</label>
            <input id="password" type="password" name="password" class="form-control" required>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="btn-primary w-full">Simpan Password Baru</button>
    </form>
</x-guest-layout>
