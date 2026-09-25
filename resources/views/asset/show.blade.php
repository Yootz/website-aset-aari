@extends('layouts.app')

@section('title', 'Detail Aset | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Aset / Riwayat peminjaman</p>
            <h1>{{ $asset->a_name }}</h1>
            <p>Informasi aset dan seluruh detail peminjaman yang pernah tercatat.</p>
        </div>
        <a href="{{ route('asset.index') }}" class="secondary-button"><span class="button-symbol"><-</span> Semua aset</a>
    </div>

    <section class="detail-grid">
        <div class="data-panel">
            <div class="panel-head">
                <div><h2 class="panel-title">Riwayat peminjaman</h2><p class="panel-caption">{{ $asset->details->count() }} detail tercatat untuk aset ini.</p></div>
                <span class="status-chip status-{{ $asset->a_status }}">{{ ucfirst($asset->a_status) }}</span>
            </div>
            <div class="table-wrap">
                <table class="division-table">
                    <thead><tr><th scope="col">Peminjaman</th><th scope="col">Peminjam</th><th scope="col">Tanggal</th><th scope="col">Status</th><th scope="col" class="action-column">Aksi</th></tr></thead>
                    <tbody>
                        @forelse($asset->details as $detail)
                            <tr>
                                <td><span class="code-chip">{{ $detail->p_code }}</span></td>
                                <td class="division-name">{{ $detail->peminjaman?->employee?->e_name ?? '-' }}</td>
                                <td>{{ $detail->peminjaman?->tgl_pinjam?->format('d M Y') ?? '-' }}</td>
                                <td><span class="status-chip status-{{ $detail->dt_status }}">{{ ucfirst($detail->dt_status) }}</span></td>
                                <td class="action-column"><a href="{{ route('peminjaman.show', $detail->p_code) }}" class="action-link action-edit">Detail</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="description">Aset ini belum pernah tercatat dalam peminjaman.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <aside class="detail-aside">
            <div class="form-note">
                <span class="form-note-mark">A</span>
                <h3>{{ $asset->a_code }}</h3>
                <dl class="detail-list">
                    <div><dt>Nama aset</dt><dd>{{ $asset->a_name }}</dd></div>
                    <div><dt>Jenis</dt><dd>{{ $asset->a_type }}</dd></div>
                    <div><dt>Deskripsi</dt><dd>{{ $asset->a_desc ?: 'Tidak ada deskripsi.' }}</dd></div>
                    <div><dt>Status saat ini</dt><dd>{{ ucfirst($asset->a_status) }}</dd></div>
                </dl>
                <a href="{{ route('createqr.index', ['a_code' => $asset->a_code]) }}" class="secondary-button detail-button">Generate QR aset</a>
            </div>
        </aside>
    </section>
@endsection
