<nav class="top-nav">
    <div class="flex h-16 w-full items-center justify-between px-4 sm:px-8 lg:px-10">
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
                @if (in_array(auth()->user()->role, ['siswa', 'admin'], true))
                    @php($unreadNotificationsCount = auth()->user()->unreadNotifications()->count())
                    @php($notificationRoute = auth()->user()->role === 'admin' ? route('admin.notifications.index') : route('siswa.notifications.index'))
                    @php($isNotificationRoute = auth()->user()->role === 'admin' ? request()->routeIs('admin.notifications.*') : request()->routeIs('siswa.notifications.*'))
                    <a
                        href="{{ $notificationRoute }}"
                        class="relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/40 bg-white/20 text-white transition hover:bg-white/30 {{ $isNotificationRoute ? 'ring-2 ring-white/70' : '' }}"
                        aria-label="Notifikasi"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5" />
                            <path d="M9.5 17a2.5 2.5 0 0 0 5 0" />
                        </svg>
                        @if($unreadNotificationsCount > 0)
                            <span class="absolute -right-1 -top-1 inline-flex min-h-[1.2rem] min-w-[1.2rem] items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold leading-none text-white">
                                {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                            </span>
                        @endif
                    </a>
                @endif
                <a href="{{ route('profile.edit') }}" class="hidden max-w-[10rem] truncate rounded-full border border-white/35 bg-white/15 px-3 py-2 text-sm font-semibold text-white hover:bg-white/25 sm:inline">{{ auth()->user()->name }}</a>
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

    @auth
        <div class="border-t border-white/20 px-4 pb-3 md:hidden">
            <div class="mobile-nav-scroll pt-3">
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('dashboard') }}" class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'mobile-nav-link-active' : '' }}">Dashboard</a>
                    <a href="{{ route('books.index') }}" class="mobile-nav-link {{ request()->routeIs('books.*') ? 'mobile-nav-link-active' : '' }}">Buku</a>
                    <a href="{{ route('categories.index') }}" class="mobile-nav-link {{ request()->routeIs('categories.*') ? 'mobile-nav-link-active' : '' }}">Kategori</a>
                    <a href="{{ route('users.index') }}" class="mobile-nav-link {{ request()->routeIs('users.*') ? 'mobile-nav-link-active' : '' }}">Siswa</a>
                    <a href="{{ route('borrowings.index') }}" class="mobile-nav-link {{ request()->routeIs('borrowings.*') || request()->routeIs('borrowing.*') ? 'mobile-nav-link-active' : '' }}">Peminjaman</a>
                    <a href="{{ route('admin.notifications.index') }}" class="mobile-nav-link {{ request()->routeIs('admin.notifications.*') ? 'mobile-nav-link-active' : '' }}">Notifikasi</a>
                @else
                    <a href="{{ route('dashboard') }}" class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'mobile-nav-link-active' : '' }}">Dashboard</a>
                    <a href="{{ route('books.search') }}" class="mobile-nav-link {{ request()->routeIs('books.search') || request()->routeIs('books.borrow') ? 'mobile-nav-link-active' : '' }}">Cari Buku</a>
                    <a href="{{ route('borrowing.returns') }}" class="mobile-nav-link {{ request()->routeIs('borrowing.returns') || request()->routeIs('borrowing.return') ? 'mobile-nav-link-active' : '' }}">Pengembalian</a>
                    <a href="{{ route('borrowing.history') }}" class="mobile-nav-link {{ request()->routeIs('borrowing.history') || request()->routeIs('borrowing.fine-payment*') ? 'mobile-nav-link-active' : '' }}">Riwayat</a>
                    <a href="{{ route('siswa.notifications.index') }}" class="mobile-nav-link {{ request()->routeIs('siswa.notifications.*') ? 'mobile-nav-link-active' : '' }}">Notifikasi</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="mobile-nav-link {{ request()->routeIs('profile.*') ? 'mobile-nav-link-active' : '' }}">Profil</a>
            </div>
        </div>
    @endauth
</nav>
