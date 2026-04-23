<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Perpustakaan Sekolah') }} - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="app-shell">
        @include('components.navbar')

        <main class="pb-10 pt-6 sm:pt-8">
            <div class="w-full px-5 sm:px-8 lg:px-10">
                @isset($header)
                    <div class="mb-6 section-card">
                        {{ $header }}
                    </div>
                @endisset

                @if ($errors->any())
                    <div class="mb-6 glass-card border-rose-200 bg-rose-50 p-4 text-rose-700">
                        <p class="mb-2 text-sm font-bold">Terdapat kesalahan input:</p>
                        <ul class="list-disc space-y-1 pl-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-6 glass-card border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 glass-card border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-700">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="page-enter">
                    @hasSection('content')
                        @yield('content')
                    @elseif (isset($slot))
                        {{ $slot }}
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>
