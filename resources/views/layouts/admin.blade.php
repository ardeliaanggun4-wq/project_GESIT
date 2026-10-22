<!doctype html>
{{-- Letak file: resources/views/layouts/admin.blade.php (timpa file lama) --}}
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>GESIT · @yield('title', 'Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.head')
</head>
<body class="min-h-screen">
    <header class="topbar">
        <div class="mx-auto max-w-6xl px-5 flex items-center justify-between gap-4 h-16">
            <a href="{{ route('admin.ringkasan') }}" class="brand">
                <span class="brand-mark">G</span>
                <span>
                    <span class="brand-name">GESIT</span>
                    <span class="brand-sub hidden sm:block">Gerakan Sarana Inventaris Terpadu</span>
                </span>
            </a>

            <div class="user-chip flex items-center gap-3">
                <span class="avatar">{{ auth()->user()->initials() }}</span>
                <div class="hidden sm:block leading-tight">
                    <div class="text-sm font-semibold">{{ auth()->user()->name }}</div>
                    <div class="mono-label">Admin</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-ghost btn-sm">Keluar</button>
                </form>
            </div>
        </div>

        <nav class="mx-auto max-w-6xl px-3 nav" aria-label="Menu admin">
            @php
                $menu = [
                    ['admin.ringkasan', 'Ringkasan'],
                    ['admin.verifikasi', 'Verifikasi'],
                    ['admin.data-alat', 'Data Alat'],
                    [null, 'Laporan'],
                ];
            @endphp
            @foreach ($menu as $i => [$rute, $label])
                <a href="{{ $rute ? route($rute) : '#' }}"
                   class="nav-link {{ $rute && request()->routeIs($rute) ? 'is-active' : '' }}">
                    <span class="nav-no">0{{ $i + 1 }}</span>{{ $label }}
                </a>
            @endforeach
        </nav>
    </header>

    <main class="mx-auto max-w-6xl px-5 py-10">
        @if (session('status'))
            <div class="flash mb-6" role="status">
                <span>{{ session('status') }}</span>
                <span class="mono-label">tutup ×</span>
            </div>
        @endif
        @yield('content')
    </main>

    @include('partials.motion')
</body>
</html>