@extends('layouts.app')

@section('title', 'Dashboard | AARI')

@section('content')
    <section class="dashboard-hero">
        <div class="dashboard-hero-copy">
            <p class="eyebrow">Website Manajemen aset AARI</p>
            <h1>Kelola aset<br><em>AARI</em></h1>
            <a href="{{ route('asset.index') }}" class="hero-action">
                Buka inventaris
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14m-7-7 7 7-7 7"></path></svg>
            </a>
        </div>
        <div class="hero-visual" aria-hidden="true">
            <div class="hero-visual-grid"></div>
            <div class="hero-visual-mark">
                <svg viewBox="0 0 80 80" fill="none"><path d="m40 7 31 17v32L40 73 9 56V24L40 7Z" stroke="currentColor" stroke-width="1.5"></path><path d="m10 24 30 17 30-17M40 41v31M25 15l30 17" stroke="currentColor" stroke-width="1.5"></path></svg>
            </div>
            <span class="hero-visual-caption">AARI <i></i> ASSET MANAGEMENT</span>
        </div>
    </section>

    <section class="stat-grid" aria-label="Ringkasan data">
        <a href="{{ route('division.index') }}" class="stat-card">
            <span class="stat-card-top"><span class="stat-icon stat-icon-blue"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"></rect><path d="M9 20V8h6v12M3 10h6m6 0h6"></path></svg></span><span class="stat-trend">DATA</span></span>
            <span class="stat-label">Total divisi</span>
            <strong>{{ $divisionCount }}</strong>
            <span class="stat-link">Lihat divisi <span>↗</span></span>
        </a>
        <a href="{{ route('employee.index') }}" class="stat-card">
            <span class="stat-card-top"><span class="stat-icon stat-icon-cyan"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="10" cy="7" r="4"></circle><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span><span class="stat-trend">DATA</span></span>
            <span class="stat-label">Total karyawan</span>
            <strong>{{ $employeeCount }}</strong>
            <span class="stat-link">Lihat karyawan <span>↗</span></span>
        </a>
        <a href="{{ route('asset.index') }}" class="stat-card">
            <span class="stat-card-top"><span class="stat-icon stat-icon-violet"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 9 5-9 5-9-5 9-5Z"></path><path d="m3 8 9 5 9-5M3 8v9l9 5 9-5V8M12 13v9"></path></svg></span><span class="stat-trend">DATA</span></span>
            <span class="stat-label">Total aset</span>
            <strong>{{ $assetCount }}</strong>
            <span class="stat-link">Lihat aset <span>↗</span></span>
        </a>
        <a href="{{ route('peminjaman.index') }}" class="stat-card">
            <span class="stat-card-top"><span class="stat-icon stat-icon-amber"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3"></path><path d="M16 3h5v5M10 14 21 3"></path></svg></span><span class="stat-trend">DATA</span></span>
            <span class="stat-label">Total peminjaman</span>
            <strong>{{ $peminjamanCount }}</strong>
            <span class="stat-link">Lihat peminjaman <span>↗</span></span>
        </a>
    </section>

    <section class="dashboard-section">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Workspace / Shortcuts</p>
                <h2>Akses cepat</h2>
            </div>
            <span class="section-rule"></span>
        </div>
        <div class="feature-grid">
            <a href="{{ route('division.index') }}" class="feature-card">
                <span class="feature-number">01</span>
                <span class="feature-icon">◆</span>
                <h3>Kelola divisi</h3>
                <p>Atur unit kerja dan lihat pembagian tim dalam satu daftar.</p>
                <span class="feature-arrow">Buka fitur <strong>→</strong></span>
            </a>
            <a href="{{ route('employee.index') }}" class="feature-card">
                <span class="feature-number">02</span>
                <span class="feature-icon feature-icon-coral">●</span>
                <h3>Kelola karyawan</h3>
                <p>Tambahkan anggota tim dan hubungkan mereka dengan divisinya.</p>
                <span class="feature-arrow">Buka fitur <strong>→</strong></span>
            </a>
            <a href="{{ route('asset.index') }}" class="feature-card">
                <span class="feature-number">03</span>
                <span class="feature-icon feature-icon-teal">□</span>
                <h3>Kelola aset</h3>
                <p>Lihat status aset dan telusuri riwayat peminjaman setiap item.</p>
                <span class="feature-arrow">Buka fitur <strong>→</strong></span>
            </a>
            <a href="{{ route('peminjaman.index') }}" class="feature-card">
                <span class="feature-number">04</span>
                <span class="feature-icon feature-icon-coral">↗</span>
                <h3>Monitor peminjaman</h3>
                <p>Periksa daftar transaksi dan detail aset yang sedang dipinjam.</p>
                <span class="feature-arrow">Buka fitur <strong>→</strong></span>
            </a>
            <a href="{{ route('createqr.index') }}" class="feature-card">
                <span class="feature-number">05</span>
                <span class="feature-icon">⌁</span>
                <h3>Buat QR aset</h3>
                <p>Generate QR code untuk memudahkan akses informasi setiap aset.</p>
                <span class="feature-arrow">Buka fitur <strong>→</strong></span>
            </a>
        </div>
    </section>

    <section class="dashboard-section division-overview">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Team structure / Distribution</p>
                <h2>Anggota per divisi</h2>
            </div>
            <a href="{{ route('division.index') }}" class="text-link">Lihat semua <span>↗</span></a>
        </div>
        @if($divisions->isEmpty())
            <div class="dashboard-empty">Belum ada divisi untuk diringkas. <a href="{{ route('division.create') }}">Tambah divisi pertama →</a></div>
        @else
            <div class="division-bars">
                @foreach($divisions as $division)
                    <div class="division-bar-row">
                        <span class="division-bar-name">{{ $division->d_name }}</span>
                        <span class="division-bar-track"><span style="width: {{ $employeeCount ? max(8, min(100, ($division->employees_count / $employeeCount) * 100)) : 8 }}%"></span></span>
                        <strong>{{ $division->employees_count }}</strong>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection
