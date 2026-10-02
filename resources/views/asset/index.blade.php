@extends('layouts.app')

@section('title', 'Aset | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow"></p>
            <h1>Daftar Aset</h1>
            <p>{{ auth()->user()?->is_admin ? 'Kelola inventaris aset yang terdaftar di sistem manajemen AARI.' : 'Lihat inventaris aset yang terdaftar di sistem manajemen AARI.' }}
            </p>
        </div>
        @if(auth()->user()?->is_admin)
            <div class="heading-actions">
                <a href="{{ route('asset.create') }}" class="primary-button"><span class="button-symbol">+</span> Tambah
                    aset</a>
                <a href="{{ route('createqr.index') }}" class="secondary-button"><span class="button-symbol">⌁</span> Buat QR
                    aset</a>
                <a href="{{ route('laporan.aset') }}" class="secondary-button"><span class="button-symbol"
                        aria-hidden="true">▧</span> Cetak daftar aset</a>
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

        <form action="{{ route('asset.index') }}" method="GET" class="report-toolbar asset-filter"
            aria-label="Filter daftar aset">
            <div class="report-period-field">
                <label for="asset-status-filter">Status aset</label>
                <select id="asset-status-filter" name="a_status" class="status-select">
                    @foreach(['all' => 'Semua status', 'available' => 'Available', 'unavailable' => 'Unavailable', 'pending' => 'Pending', 'maintenance' => 'Maintenance'] as $value => $label)
                        <option value="{{ $value }}" @selected($assetStatus === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="primary-button">Terapkan filter</button>
        </form>

        @if($errors->has('asset'))
            <div class="flash-message flash-error">{{ $errors->first('asset') }}</div>
        @endif

        @if($assets->isEmpty())
            <div class="empty-state">
                <div class="empty-symbol">+</div>
                <h3>Belum ada aset</h3>
                <p>Belum ada data aset yang dapat ditampilkan.</p>
            </div>
        @else
            <div class="table-wrap">
                <table class="division-table asset-table">
                    <thead>
                        <tr>
                            <th scope="col">Kode</th>
                            <th scope="col">Nama aset</th>
                            <th scope="col">Jenis</th>
                            <th scope="col">Status</th>
                            <th scope="col">Riwayat</th>
                            <th scope="col" class="action-column asset-action-column">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="asset-table-body">
                        @foreach($assets as $asset)
                            <tr>
                                <td><span class="code-chip">{{ $asset->a_code }}</span></td>
                                <td class="division-name">{{ $asset->a_name }}</td>
                                <td class="description">{{ $asset->a_type }}</td>
                                <td><span class="status-chip status-{{ $asset->a_status }}">{{ ucfirst($asset->a_status) }}</span>
                                </td>
                                <td>{{ $asset->details_count }} transaksi</td>
                                <td class="action-column asset-action-column">
                                    <div class="asset-action-list">
                                        <a href="{{ route('asset.show', $asset->a_code) }}" class="action-link action-edit">Lihat
                                            detail</a>
                                        @if(auth()->user()?->is_admin && in_array(strtolower(trim($asset->a_status)), ['available', 'maintenance'], true))
                                            <form action="{{ route('asset.maintenance', $asset->a_code) }}" method="POST"
                                                class="inline-action-form" data-swal-confirm
                                                data-swal-title="{{ strtolower(trim($asset->a_status)) === 'maintenance' ? 'Aktifkan aset?' : 'Set aset ke maintenance?' }}"
                                                data-swal-text="{{ strtolower(trim($asset->a_status)) === 'maintenance' ? 'Status aset akan dikembalikan menjadi available.' : 'Aset ini tidak akan tersedia untuk peminjaman.' }}"
                                                data-swal-icon="warning">
                                                @csrf
                                                <button type="submit"
                                                    class="action-link action-maintenance">{{ strtolower(trim($asset->a_status)) === 'maintenance' ? 'Jadikan available' : 'Maintenance' }}</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Show More Button --}}
            @if($assets->hasMorePages())
                <div class="show-more-container" id="asset-show-more">
                    <button type="button" class="show-more-btn"
                        data-url="{{ route('asset.loadMore', ['a_status' => $assetStatus]) }}"
                        data-maintenance-url="{{ route('asset.maintenance', '__ASSET__') }}" data-csrf-token="{{ csrf_token() }}"
                        data-is-admin="{{ auth()->user()?->is_admin ? 'true' : 'false' }}" data-page="2" data-has-more="true">
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
        document.addEventListener('DOMContentLoaded', function () {
            const showMoreBtn = document.querySelector('#asset-show-more .show-more-btn');
            const tableBody = document.querySelector('#asset-table-body');

            if (showMoreBtn && tableBody) {
                showMoreBtn.addEventListener('click', function () {
                    const btn = this;
                    const url = btn.dataset.url;
                    const page = parseInt(btn.dataset.page);
                    const hasMore = btn.dataset.hasMore === 'true';

                    if (!hasMore) return;

                    btn.disabled = true;
                    btn.querySelector('.btn-text').style.display = 'none';
                    btn.querySelector('.btn-loading').style.display = 'inline-block';

                    const nextPageUrl = new URL(url, window.location.origin);
                    nextPageUrl.searchParams.set('page', page);

                    fetch(nextPageUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Gagal memuat aset berikutnya.');
                            }

                            return response.json();
                        })
                        .then(data => {
                            if (data.data && data.data.length > 0) {
                                data.data.forEach(asset => {
                                    const row = document.createElement('tr');
                                    const normalizedStatus = String(asset.a_status || '').toLowerCase().trim();
                                    const maintenanceAction = btn.dataset.isAdmin !== 'true' || !['available', 'maintenance'].includes(normalizedStatus)
                                        ? ''
                                        : `<form action="${btn.dataset.maintenanceUrl.replace('__ASSET__', encodeURIComponent(asset.a_code))}" method="POST" class="inline-action-form">
                                        <input type="hidden" name="_token" value="${btn.dataset.csrfToken}">
                                        <button type="submit" class="action-link action-maintenance">${normalizedStatus === 'maintenance' ? 'Jadikan available' : 'Maintenance'}</button>
                                    </form>`;
                                    row.innerHTML = `
                                    <td><span class="code-chip">${asset.a_code}</span></td>
                                    <td class="division-name">${asset.a_name}</td>
                                    <td class="description">${asset.a_type}</td>
                                    <td><span class="status-chip status-${asset.a_status}">${asset.a_status.charAt(0).toUpperCase() + asset.a_status.slice(1)}</span></td>
                                    <td>${asset.details_count} transaksi</td>
                                    <td class="action-column asset-action-column"><div class="asset-action-list"><a href="/asset/${encodeURIComponent(asset.a_code)}" class="action-link action-edit">Lihat detail</a>${maintenanceAction}</div></td>
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