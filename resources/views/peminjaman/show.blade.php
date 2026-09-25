@extends('layouts.app')

@section('title', 'Detail Peminjaman | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Peminjaman / Detail transaksi</p>
            <h1>{{ $peminjaman->p_code }}</h1>
            <p>Rincian aset, peminjam, dan jadwal pengembalian untuk transaksi ini.</p>
        </div>
        <a href="{{ route('peminjaman.index') }}" class="secondary-button"><span class="button-symbol"><-</span> Semua peminjaman</a>
    </div>

    <section class="detail-grid">
        <div class="data-panel">
            <div class="panel-head">
                <div>
                    <h2 class="panel-title">Aset yang dipinjam</h2>
                    <p class="panel-caption">{{ $peminjaman->details->count() }} aset terhubung ke transaksi ini.</p>
                </div>
                <span class="status-chip status-{{ $peminjaman->p_status }}">{{ ucfirst($peminjaman->p_status) }}</span>
            </div>
            <div class="table-wrap">
                <table class="division-table">
                    <thead>
                        <tr><th scope="col">Kode aset</th><th scope="col">Nama aset</th><th scope="col">Jenis</th><th scope="col">Status detail</th></tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman->details as $detail)
                            <tr>
                                <td><a href="{{ route('asset.show', $detail->a_code) }}" class="code-chip">{{ $detail->a_code }}</a></td>
                                <td class="division-name">{{ $detail->asset?->a_name ?? 'Aset tidak ditemukan' }}</td>
                                <td class="description">{{ $detail->asset?->a_type ?? '-' }}</td>
                                <td><span class="status-chip status-{{ $detail->dt_status }}">{{ ucfirst($detail->dt_status) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="description">Belum ada detail aset pada transaksi ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <aside class="detail-aside">
            <div class="form-note">
                <span class="form-note-mark">i</span>
                <h3>Informasi transaksi</h3>
                <dl class="detail-list">
                    <div><dt>Peminjam</dt><dd>{{ $peminjaman->employee?->e_name ?? '-' }}</dd></div>
                    <div><dt>Divisi</dt><dd>{{ $peminjaman->employee?->division?->d_name ?? '-' }}</dd></div>
                    <div><dt>Tanggal pinjam</dt><dd>{{ $peminjaman->tgl_pinjam?->format('d M Y') ?? '-' }}</dd></div>
                    <div><dt>Rencana kembali</dt><dd>{{ $peminjaman->tgl_balik?->format('d M Y') ?? '-' }}</dd></div>
                    <div><dt>Catatan</dt><dd>{{ $peminjaman->p_desc ?: 'Tidak ada catatan.' }}</dd></div>
                </dl>
            </div>
        </aside>
    </section>
@endsection
