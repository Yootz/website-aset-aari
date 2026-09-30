<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AARI')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@yield('body-class')">
    @php($isAdmin = auth()->user()?->is_admin ?? false)
    <div class="app-shell">
        <aside class="sidebar">
            <a href="{{ $isAdmin ? route('dashboard') : route('asset.index') }}" class="brand" aria-label="AARI home">
                <img class="brand-logo" src="{{ asset('klk-logo.svg') }}" alt="Kuala Lumpur Kepong Berhad">
                <span class="brand-copy">
                    <strong>AARI</strong>
                    <small>Inventaris aset</small>
                </span>
            </a>
            <!-- buatkan blank space -->
            <div class="sidebar-spacer"> </div>
            <div class="sidebar-navigation">
                <section class="sidebar-group">
                    <div class="sidebar-label">Workspace</div>
                    <nav class="main-nav" aria-label="Navigasi workspace">
                        @if($isAdmin)
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" aria-label="Dashboard">
                                <svg class="nav-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="8" height="8" rx="1"></rect><rect x="14" y="3" width="7" height="5" rx="1"></rect><rect x="14" y="11" width="7" height="10" rx="1"></rect><rect x="3" y="14" width="8" height="7" rx="1"></rect></svg>
                                <span>Dashboard</span>
                            </a>
                        @endif
                        <a href="{{ route('division.index') }}" class="nav-link {{ request()->routeIs('division.*') ? 'is-active' : '' }}" aria-label="Divisi">
                            <svg class="nav-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"></rect><path d="M9 20V8h6v12M3 10h6m6 0h6M3 15h6m6 0h6"></path></svg>
                            <span>Divisi</span>
                        </a>
                        <a href="{{ route('employee.index') }}" class="nav-link {{ request()->routeIs('employee.*') ? 'is-active' : '' }}" aria-label="Karyawan">
                            <svg class="nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="10" cy="7" r="4"></circle><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <span>Karyawan</span>
                        </a>
                        <a href="{{ route('asset.index') }}" class="nav-link {{ request()->routeIs('asset.*') ? 'is-active' : '' }}" aria-label="Aset">
                            <svg class="nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 9 5-9 5-9-5 9-5Z"></path><path d="m3 8 9 5 9-5M3 8v9l9 5 9-5V8M12 13v9"></path></svg>
                            <span>Aset</span>
                        </a>
                    </nav>
                </section>

                <section class="sidebar-group">
                    <div class="sidebar-label">Operasional</div>
                    <nav class="main-nav" aria-label="Navigasi operasional">
                        @if($isAdmin)
                            <a href="{{ route('peminjaman.index') }}" class="nav-link {{ request()->routeIs('peminjaman.index', 'peminjaman.show') ? 'is-active' : '' }}" aria-label="Peminjaman">
                                <svg class="nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3"></path><path d="M16 3h5v5M10 14 21 3"></path></svg>
                                <span>Peminjaman</span>
                            </a>
                            <a href="{{ route('peminjaman.monitoring') }}" class="nav-link {{ request()->routeIs('peminjaman.monitoring') ? 'is-active' : '' }}" aria-label="Monitoring peminjaman">
                                <svg class="nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12h4l3-8 4 16 3-8h4"></path></svg>
                                <span>Monitoring</span>
                            </a>
                            <a href="{{ route('laporan.peminjaman.bulanan') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'is-active' : '' }}" aria-label="Laporan peminjaman bulanan">
                                <svg class="nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8z"></path><path d="M8 3v5h5M8 13h8M8 17h8"></path></svg>
                                <span>Laporan</span>
                            </a>
                            <a href="{{ route('createqr.index') }}" class="nav-link {{ request()->routeIs('createqr.*') ? 'is-active' : '' }}" aria-label="QR aset">
                                <svg class="nav-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect><path d="M14 14h3v3h-3zm4 4h3v3h-3zm3-4v2m-7 3v2"></path></svg>
                                <span>QR aset</span>
                            </a>
                        @else
                            <a href="{{ route('peminjaman.index') }}" class="nav-link {{ request()->routeIs('peminjaman.index', 'peminjaman.show') ? 'is-active' : '' }}" aria-label="Peminjaman">
                                <svg class="nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3"></path><path d="M16 3h5v5M10 14 21 3"></path></svg>
                                <span>Peminjaman</span>
                            </a>
                            <a href="{{ route('peminjaman.create') }}" class="nav-link {{ request()->routeIs('peminjaman.create') ? 'is-active' : '' }}" aria-label="Buat peminjaman">
                                <svg class="nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3"></path><path d="M16 3h5v5M10 14 21 3"></path></svg>
                                <span>Pinjam aset</span>
                            </a>
                        @endif
                    </nav>
                </section>
            </div>

            <div class="sidebar-footer">
                <span class="status-dot"></span>
                <span>Workspace aktif</span>
            </div>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <div class="topbar-context">
                </div>
                <div class="topbar-actions">
                    @if($isAdmin)
                        <span class="topbar-meta"><span class="role-indicator"></span> Admin</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="secondary-button topbar-auth-button">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="secondary-button topbar-auth-button">Login admin</a>
                    @endif
                </div>
            </header>

            <div class="page-content">
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
