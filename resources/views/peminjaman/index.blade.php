@extends('layouts.app')

@section('title', 'Peminjaman | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Asset operations / Loans</p>
            <h1>Semua peminjaman, satu pandangan.</h1>
            <p>Periksa status transaksi dan buka detail aset yang terhubung pada setiap peminjaman.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('peminjaman.monitoring') }}" class="secondary-button">Monitoring</a>
            <a href="{{ route('peminjaman.create') }}" class="primary-button"><span class="button-symbol">+</span> Buat peminjaman</a>
        </div>
    </div>

    @if(session('success'))
        <div class="flash-message"><span>✓</span> {{ session('success') }}</div>
    @endif

    <section class="data-panel">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Daftar peminjaman</h2>
                <p class="panel-caption">Riwayat peminjaman aset dari yang terbaru.</p>
            </div>
            <span class="count-badge">{{ $peminjaman->count() }} transaksi</span>
        </div>

        @if($peminjaman->isEmpty())
            <div class="empty-state">
                <div class="empty-symbol">+</div>
                <h3>Belum ada peminjaman</h3>
                <p>Transaksi dari API peminjaman akan muncul di sini.</p>
            </div>
        @else
            <div class="table-wrap">
                <table class="division-table">
                    <thead>
                        <tr>
                            <th scope="col">Kode</th>
                            <th scope="col">Peminjam</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Aset</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="action-column">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman as $loan)
                            <tr>
                                <td><span class="code-chip">{{ $loan->p_code }}</span></td>
                                <td>
                                    <strong class="division-name">{{ $loan->employee?->e_name ?? 'Karyawan tidak ditemukan' }}</strong>
                                    <span class="table-subline">{{ $loan->e_code }}</span>
                                </td>
                                <td>
                                    <strong>{{ $loan->tgl_pinjam?->format('d M Y') }}</strong>
                                    <span class="table-subline">Kembali {{ $loan->tgl_balik?->format('d M Y') ?? '-' }}</span>
                                </td>
                                <td>{{ $loan->details->count() }} item</td>
                                <td><span class="status-chip status-{{ $loan->p_status }}">{{ ucfirst($loan->p_status) }}</span></td>
                                <td class="action-column"><a href="{{ route('peminjaman.show', $loan->p_code) }}" class="action-link action-edit">Detail</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
