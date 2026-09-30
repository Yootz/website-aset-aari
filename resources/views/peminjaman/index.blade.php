@extends('layouts.app')

@section('title', 'Peminjaman | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow"></p>
            <h1>Daftar Peminjaman</h1>
            <p>Lihat transaksi peminjaman aset dalam sistem manajemen AARI.</p>
        </div>
        <div class="heading-actions">
            @if(auth()->user()?->is_admin)
                <a href="{{ route('peminjaman.monitoring') }}" class="secondary-button">Monitoring</a>
            @endif
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
                <p class="panel-caption">Riwayat peminjaman aset, dari yang terbaru.</p>
            </div>
            <span class="count-badge">{{ $peminjaman->total() }} transaksi</span>
        </div>

        @if($peminjaman->isEmpty())
            <div class="empty-state">
                <div class="empty-symbol">+</div>
                <h3>Belum ada peminjaman</h3>
                <p>Transaksi peminjaman yang tercatat akan muncul di sini.</p>
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
                    <tbody id="peminjaman-table-body">
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
                                <td class="action-column">
                                    <!-- Fitur edit untuk peminjaman (muncul jika status peminjaman adalah pending) -->
                                    @if(strtolower(trim($loan->p_status)) === 'pending')
                                        <a href="{{ route('peminjaman.edit', $loan->p_code) }}" class="action-link action-edit">Edit</a>
                                    @endif
                                    <a href="{{ route('peminjaman.show', $loan->p_code) }}" class="action-link action-edit">Detail</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Show More Button --}}
            @if($peminjaman->hasMorePages())
                <div class="show-more-container" id="peminjaman-show-more">
                    <button type="button" class="show-more-btn" data-url="{{ route('peminjaman.loadMore') }}" data-page="2" data-has-more="true">
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
    const showMoreBtn = document.querySelector('#peminjaman-show-more .show-more-btn');
    const tableBody = document.querySelector('#peminjaman-table-body');
    
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
                    data.data.forEach(loan => {
                        const row = document.createElement('tr');
                        const employeeName = loan.employee?.e_name ?? 'Karyawan tidak ditemukan';
                        const employeeCode = loan.e_code;
                        const tglPinjam = loan.tgl_pinjam ? new Date(loan.tgl_pinjam).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'}) : '-';
                        const tglBalik = loan.tgl_balik ? new Date(loan.tgl_balik).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'}) : '-';
                        const detailsCount = loan.details?.length ?? 0;
                        
                        row.innerHTML = `
                            <td><span class="code-chip">${loan.p_code}</span></td>
                            <td>
                                <strong class="division-name">${employeeName}</strong>
                                <span class="table-subline">${employeeCode}</span>
                            </td>
                            <td>
                                <strong>${tglPinjam}</strong>
                                <span class="table-subline">Kembali ${tglBalik}</span>
                            </td>
                            <td>${detailsCount} item</td>
                            <td><span class="status-chip status-${loan.p_status}">${loan.p_status.charAt(0).toUpperCase() + loan.p_status.slice(1)}</span></td>
                            <td class="action-column"><a href="/peminjaman/${loan.p_code}" class="action-link action-edit">Detail</a></td>
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
                console.error('Error loading more peminjaman:', error);
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
