@extends('layouts.app')

@section('title', 'Dashboard | AARI')

@section('content')
    <section class="dashboard-hero">
        <div>
            <p class="eyebrow">Workspace overview</p>
            <h1>Selamat datang di <em>AARI.</em></h1>
            <p class="hero-copy">Satu ruang untuk melihat struktur tim dan menjaga data operasional tetap terarah.</p>
        </div>
        <div class="hero-orbit" aria-hidden="true">
            <span class="orbit-line orbit-line-one"></span>
            <span class="orbit-line orbit-line-two"></span>
            <span class="orbit-core">A</span>
        </div>
    </section>

    <section class="stat-grid" aria-label="Ringkasan data">
        <a href="{{ route('division.index') }}" class="stat-card stat-card-lime">
            <span class="stat-label">Total divisi</span>
            <strong>{{ $divisionCount }}</strong>
            <span class="stat-link">Lihat divisi <span>↗</span></span>
        </a>
        <a href="{{ route('employee.index') }}" class="stat-card stat-card-coral">
            <span class="stat-label">Total karyawan</span>
            <strong>{{ $employeeCount }}</strong>
            <span class="stat-link">Lihat karyawan <span>↗</span></span>
        </a>
        <div class="dashboard-note">
            <span class="note-mark">✦</span>
            <p>Data yang terorganisir membuat setiap keputusan terasa lebih ringan.</p>
        </div>
    </section>

    <section class="dashboard-section">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Quick access</p>
                <h2>Mulai dari mana?</h2>
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
        </div>
    </section>

    <section class="dashboard-section division-overview">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Team structure</p>
                <h2>Komposisi divisi</h2>
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
