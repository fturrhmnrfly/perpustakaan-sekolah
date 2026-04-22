<nav class="top-nav">
    <div class="flex h-16 w-full items-center justify-between px-5 sm:px-8 lg:px-10">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3 text-sm font-extrabold text-white sm:text-base">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/20 text-white shadow ring-1 ring-white/40">
                PS
            </span>
            Perpustakaan Sekolah
        </a>

        @auth
            <div class="hidden items-center gap-2 rounded-full border border-white/30 bg-white/15 px-2 py-1 md:flex">
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('dashboard') }}" class="rounded-full px-3 py-2 text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-white text-sky-700' : 'text-white/95 hover:bg-white/15' }}">Dashboard</a>
                    <a href="{{ route('books.index') }}" class="rounded-full px-3 py-2 text-sm font-semibold transition {{ request()->routeIs('books.*') ? 'bg-white text-sky-700' : 'text-white/95 hover:bg-white/15' }}">Buku</a>
                    <a href="{{ route('categories.index') }}" class="rounded-full px-3 py-2 text-sm font-semibold transition {{ request()->routeIs('categories.*') ? 'bg-white text-sky-700' : 'text-white/95 hover:bg-white/15' }}">Kategori</a>
                    <a href="{{ route('users.index') }}" class="rounded-full px-3 py-2 text-sm font-semibold transition {{ request()->routeIs('users.*') ? 'bg-white text-sky-700' : 'text-white/95 hover:bg-white/15' }}">Siswa</a>
                    <a href="{{ route('borrowings.index') }}" class="rounded-full px-3 py-2 text-sm font-semibold transition {{ request()->routeIs('borrowings.*') || request()->routeIs('borrowing.*') ? 'bg-white text-sky-700' : 'text-white/95 hover:bg-white/15' }}">Peminjaman</a>
                @else
                    <a href="{{ route('dashboard') }}" class="rounded-full px-3 py-2 text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-white text-sky-700' : 'text-white/95 hover:bg-white/15' }}">Dashboard</a>
                    <a href="{{ route('books.search') }}" class="rounded-full px-3 py-2 text-sm font-semibold transition {{ request()->routeIs('books.search') || request()->routeIs('books.borrow') ? 'bg-white text-sky-700' : 'text-white/95 hover:bg-white/15' }}">Cari Buku</a>
                    <a href="{{ route('borrowing.returns') }}" class="rounded-full px-3 py-2 text-sm font-semibold transition {{ request()->routeIs('borrowing.returns') || request()->routeIs('borrowing.return') ? 'bg-white text-sky-700' : 'text-white/95 hover:bg-white/15' }}">Pengembalian</a>
                    <a href="{{ route('borrowing.history') }}" class="rounded-full px-3 py-2 text-sm font-semibold transition {{ request()->routeIs('borrowing.history') || request()->routeIs('borrowing.fine-payment*') ? 'bg-white text-sky-700' : 'text-white/95 hover:bg-white/15' }}">Riwayat</a>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('profile.edit') }}" class="hidden rounded-full border border-white/35 bg-white/15 px-3 py-2 text-sm font-semibold text-white hover:bg-white/25 sm:inline">{{ auth()->user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-full border border-white/40 bg-white/20 px-3 py-2 text-xs font-semibold text-white transition hover:bg-white/30 sm:text-sm">
                        Logout
                    </button>
                </form>
            </div>
        @else
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-white hover:text-white/80">Login</a>
                <a href="{{ route('register') }}" class="rounded-full border border-white/40 bg-white/20 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/30">Daftar</a>
            </div>
        @endauth
    </div>
</nav>
