@extends('layouts.app')

@section('title', 'Aset | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Asset operations / Inventory</p>
            <h1>Kenali setiap aset yang bergerak.</h1>
            <p>Lihat status aset, buka detailnya, dan telusuri siapa yang pernah meminjamnya.</p>
        </div>
        <a href="{{ route('createqr.index') }}" class="primary-button"><span class="button-symbol">⌁</span> Buat QR aset</a>
    </div>

    <section class="data-panel">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Daftar aset</h2>
                <p class="panel-caption">Inventaris aset yang terdaftar di workspace.</p>
            </div>
            <span class="count-badge">{{ $assets->count() }} aset</span>
        </div>

        @if($assets->isEmpty())
            <div class="empty-state"><div class="empty-symbol">+</div><h3>Belum ada aset</h3><p>Belum ada data aset yang dapat ditampilkan.</p></div>
        @else
            <div class="table-wrap">
                <table class="division-table">
                    <thead><tr><th scope="col">Kode</th><th scope="col">Nama aset</th><th scope="col">Jenis</th><th scope="col">Status</th><th scope="col">Riwayat</th><th scope="col" class="action-column">Aksi</th></tr></thead>
                    <tbody>
                        @foreach($assets as $asset)
                            <tr>
                                <td><span class="code-chip">{{ $asset->a_code }}</span></td>
                                <td class="division-name">{{ $asset->a_name }}</td>
                                <td class="description">{{ $asset->a_type }}</td>
                                <td><span class="status-chip status-{{ $asset->a_status }}">{{ ucfirst($asset->a_status) }}</span></td>
                                <td>{{ $asset->details_count }} transaksi</td>
                                <td class="action-column"><a href="{{ route('asset.show', $asset->a_code) }}" class="action-link action-edit">Lihat detail</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
