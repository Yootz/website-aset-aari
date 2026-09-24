<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AARI')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <a href="{{ route('dashboard') }}" class="brand" aria-label="AARI home">
                <span class="brand-mark">A</span>
                <span>
                    <strong>AARI</strong>
                    <small>Asset operations</small>
                </span>
            </a>

            <div class="sidebar-label">Workspace</div>
            <nav class="main-nav" aria-label="Navigasi utama">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" aria-label="Dashboard">
                    <span class="nav-icon" aria-hidden="true">▦</span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('division.index') }}" class="nav-link {{ request()->routeIs('division.*') ? 'is-active' : '' }}" aria-label="Divisi">
                    <span class="nav-icon" aria-hidden="true">◆</span>
                    <span>Divisi</span>
                </a>
                <a href="{{ route('employee.index') }}" class="nav-link {{ request()->routeIs('employee.*') ? 'is-active' : '' }}" aria-label="Karyawan">
                    <span class="nav-icon" aria-hidden="true">●</span>
                    <span>Karyawan</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <span class="status-dot"></span>
                <span>Workspace aktif</span>
            </div>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <div class="topbar-meta">2026 <span class="meta-divider"></span> Internal</div>
            </header>

            <div class="page-content">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
