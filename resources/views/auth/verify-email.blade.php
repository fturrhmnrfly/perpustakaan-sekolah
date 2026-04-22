<x-guest-layout>
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Verifikasi Email</h1>
        <p class="mt-2 text-sm text-slate-600">Cek email Anda dan klik tautan verifikasi sebelum mulai menggunakan aplikasi.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mt-4 rounded-xl border border-emerald-300/30 bg-emerald-900/30 px-4 py-3 text-sm text-emerald-100">
            Link verifikasi baru sudah dikirim ke email Anda.
        </div>
    @endif

    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary">Kirim Ulang Verifikasi</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-secondary">Logout</button>
        </form>
    </div>
</x-guest-layout>
