@extends('layouts.app')

@section('title', 'Laporan | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Asset operations / Reports</p>
            <h1>Laporan</h1>
            <p>Pilih jenis laporan yang ingin ditinjau atau dicetak.</p>
        </div>
    </div>

    <section class="report-choice-grid" aria-label="Jenis laporan">
        <a href="{{ route('laporan.peminjaman.bulanan') }}" class="report-choice report-choice-loans">
            <span class="report-choice-index">01 / TRANSAKSI</span>
            <span class="report-choice-icon" aria-hidden="true">↗</span>
            <h2>Laporan bulanan peminjaman</h2>
            <p>Tinjau transaksi berdasarkan periode, peminjam, aset, durasi, dan status.</p>
            <span class="report-choice-action">Buka laporan <span aria-hidden="true">→</span></span>
        </a>

        <a href="{{ route('laporan.aset') }}" class="report-choice report-choice-assets">
            <span class="report-choice-index">02 / INVENTARIS</span>
            <span class="report-choice-icon" aria-hidden="true">▤</span>
            <h2>Laporan aset saat ini</h2>
            <p>Filter inventaris berdasarkan status dan cetak daftar aset terkini.</p>
            <span class="report-choice-action">Buka laporan <span aria-hidden="true">→</span></span>
        </a>
    </section>
@endsection