@extends('layouts.app')

@section('title', 'Aset | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow"></p>
            <h1>Daftar Aset</h1>
            <p>{{ auth()->user()?->is_admin ? 'Kelola inventaris aset yang terdaftar di sistem manajemen AARI.' : 'Lihat inventaris aset yang terdaftar di sistem manajemen AARI.' }}</p>
        </div>
        @if(auth()->user()?->is_admin)
            <div class="heading-actions">
                <a href="{{ route('asset.create') }}" class="primary-button"><span class="button-symbol">+</span> Tambah aset</a>
                <a href="{{ route('createqr.index') }}" class="secondary-button"><span class="button-symbol">⌁</span> Buat QR aset</a>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="flash-message"><span>✓</span> {{ session('success') }}</div>
    @endif

    <section class="data-panel">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Daftar aset</h2>
                <p class="panel-caption">Inventaris aset yang terdaftar di workspace.</p>
            </div>
            <span class="count-badge">{{ $assets->total() }} aset</span>
        </div>

        @if($assets->isEmpty())
            <div class="empty-state"><div class="empty-symbol">+</div><h3>Belum ada aset</h3><p>Belum ada data aset yang dapat ditampilkan.</p></div>
        @else
            <div class="table-wrap">
                <table class="division-table">
                    <thead><tr><th scope="col">Kode</th><th scope="col">Nama aset</th><th scope="col">Jenis</th><th scope="col">Status</th><th scope="col">Riwayat</th><th scope="col" class="action-column">Aksi</th></tr></thead>
                    <tbody id="asset-table-body">
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

            {{-- Show More Button --}}
            @if($assets->hasMorePages())
                <div class="show-more-container" id="asset-show-more">
                    <button type="button" class="show-more-btn" data-url="{{ route('asset.loadMore') }}" data-page="2" data-has-more="true">
                        <span class="btn-text">Tampilkan lebih banyak</span>
                        <span class="btn-loading" style="display:none;">Memuat data...</span>
                    </button>
                </div>
            @endif
        @endif
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const showMoreBtn = document.querySelector('#asset-show-more .show-more-btn');
    const tableBody = document.querySelector('#asset-table-body');
    
    if (showMoreBtn && tableBody) {
        showMoreBtn.addEventListener('click', function() {
            const btn = this;
            const url = btn.dataset.url;
            const page = parseInt(btn.dataset.page);
            const hasMore = btn.dataset.hasMore === 'true';
            
            if (!hasMore) return;
            
            btn.disabled = true;
            btn.querySelector('.btn-text').style.display = 'none';
            btn.querySelector('.btn-loading').style.display = 'inline-block';
            
            fetch(`${url}?page=${page}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.data && data.data.length > 0) {
                    data.data.forEach(asset => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td><span class="code-chip">${asset.a_code}</span></td>
                            <td class="division-name">${asset.a_name}</td>
                            <td class="description">${asset.a_type}</td>
                            <td><span class="status-chip status-${asset.a_status}">${asset.a_status.charAt(0).toUpperCase() + asset.a_status.slice(1)}</span></td>
                            <td>${asset.details_count} transaksi</td>
                            <td class="action-column"><a href="/asset/${asset.a_code}" class="action-link action-edit">Lihat detail</a></td>
                        `;
                        tableBody.appendChild(row);
                    });
                    
                    btn.dataset.page = data.current_page + 1;
                    btn.dataset.hasMore = data.has_more;
                    
                    if (!data.has_more) {
                        btn.style.display = 'none';
                    }
                } else {
                    btn.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error loading more assets:', error);
            })
            .finally(() => {
                btn.disabled = false;
                btn.querySelector('.btn-text').style.display = 'inline';
                btn.querySelector('.btn-loading').style.display = 'none';
            });
        });
    }
});
</script>
@endpush
