@extends('layouts.app')

@section('title', 'Monitoring Peminjaman | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow"></p>
            <h1>Monitoring Peminjaman</h1>
            <p>Kelola dan pantau status transaksi peminjaman aset dalam sistem manajemen AARI.</p>
        </div>
        <a href="{{ route('peminjaman.create') }}" class="primary-button"><span class="button-symbol">+</span> Buat peminjaman</a>
    </div>

    @if(session('success'))
        <div class="flash-message"><span>✓</span> {{ session('success') }}</div>
    @endif

    <section class="data-panel">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Monitoring status</h2>
                <p class="panel-caption">Ubah status sesuai proses persetujuan dan pengembalian aset.</p>
            </div>
            <span class="count-badge">{{ $peminjaman->total() }} transaksi</span>
        </div>

        @if($peminjaman->isEmpty())
            <div class="empty-state">
                <div class="empty-symbol">+</div>
                <h3>Belum ada transaksi</h3>
                <p>Data peminjaman baru akan muncul di halaman monitoring ini.</p>
                <a href="{{ route('peminjaman.create') }}" class="primary-button">Buat peminjaman pertama</a>
            </div>
        @else
            <div class="table-wrap">
                <table class="division-table monitoring-table">
                    <thead>
                        <tr>
                            <th scope="col">Kode</th>
                            <th scope="col">Peminjam</th>
                            <th scope="col">Aset</th>
                            <th scope="col">Jadwal</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="action-column">Simpan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman as $loan)
                            <tr>
                                <td><a href="{{ route('peminjaman.show', $loan->p_code) }}" class="code-chip">{{ $loan->p_code }}</a></td>
                                <td>
                                    <strong class="division-name">{{ $loan->employee?->e_name ?? 'Karyawan tidak ditemukan' }}</strong>
                                    <span class="table-subline">{{ $loan->e_code }}</span>
                                </td>
                                <td>
                                    <strong>{{ $loan->details->count() }} item</strong>
                                    <span class="table-subline">{{ $loan->details->pluck('a_code')->join(', ') }}</span>
                                </td>
                                <td>
                                    <strong>{{ $loan->tgl_pinjam?->format('d M Y') }}</strong>
                                    <span class="table-subline">s.d. {{ $loan->tgl_balik?->format('d M Y') ?? '-' }}</span>
                                </td>
                                <td><span class="status-chip status-{{ $loan->p_status }}">{{ ucfirst($loan->p_status) }}</span></td>
                                <td class="action-column">
                                    <form action="{{ route('peminjaman.status', $loan->p_code) }}" method="POST" class="status-form" data-swal-confirm data-swal-title="Perbarui status peminjaman?" data-swal-text="Status transaksi ini akan diubah. Lanjutkan?" data-swal-icon="question">
                                        @csrf
                                        @method('PATCH')
                                        <label class="sr-only" for="status-{{ $loan->p_code }}">Status {{ $loan->p_code }}</label>
                                        <select id="status-{{ $loan->p_code }}" name="p_status" class="status-select">
                                            @foreach(['pending', 'approved', 'returned', 'rejected'] as $status)
                                                <option value="{{ $status }}" @selected($loan->p_status === $status)>{{ ucfirst($status) }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="action-link action-edit">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($peminjaman->hasMorePages())
                <div class="show-more-container">
                    <button type="button" class="show-more-btn" id="load-more-btn" data-page="{{ $peminjaman->currentPage() + 1 }}">
                        <span class="btn-text">Muat Lebih Banyak</span>
                        <span class="btn-loading">Memuat...</span>
                    </button>
                </div>
            @endif
        @endif
    </section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('load-more-btn');
    if (!btn) return;

    btn.addEventListener('click', async function() {
        const page = parseInt(btn.dataset.page);
        if (!page) return;

        try {
            btn.disabled = true;
            btn.querySelector('.btn-text').style.display = 'none';
            btn.querySelector('.btn-loading').style.display = 'inline-block';

            const response = await fetch(`{{ route('peminjaman.monitoring.loadMore') }}?page=${page}`);
            if (!response.ok) throw new Error('Network response was not ok');

            const result = await response.json();
            const tbody = document.querySelector('.monitoring-table tbody');

            result.data.forEach(loan => {
                const tglPinjam = loan.tgl_pinjam ? new Date(loan.tgl_pinjam).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
                const tglBalik = loan.tgl_balik ? new Date(loan.tgl_balik).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
                
                const detailCodes = loan.details.map(d => d.a_code).join(', ');
                const employeeName = loan.employee?.e_name || 'Karyawan tidak ditemukan';

                const statusOptions = ['pending', 'approved', 'returned', 'rejected']
                    .map(status => `<option value="${status}" ${loan.p_status === status ? 'selected' : ''}>${status.charAt(0).toUpperCase() + status.slice(1)}</option>`)
                    .join('');

                const row = `
                    <tr>
                        <td><a href="/peminjaman/${loan.p_code}" class="code-chip">${loan.p_code}</a></td>
                        <td>
                            <strong class="division-name">${employeeName}</strong>
                            <span class="table-subline">${loan.e_code}</span>
                        </td>
                        <td>
                            <strong>${loan.details.length} item</strong>
                            <span class="table-subline">${detailCodes}</span>
                        </td>
                        <td>
                            <strong>${tglPinjam}</strong>
                            <span class="table-subline">s.d. ${tglBalik}</span>
                        </td>
                        <td><span class="status-chip status-${loan.p_status}">${loan.p_status.charAt(0).toUpperCase() + loan.p_status.slice(1)}</span></td>
                        <td class="action-column">
                            <form action="/peminjaman/${loan.p_code}/status" method="POST" class="status-form" data-swal-confirm data-swal-title="Perbarui status peminjaman?" data-swal-text="Status transaksi ini akan diubah. Lanjutkan?" data-swal-icon="question">
                                @csrf
                                @method('PATCH')
                                <label class="sr-only" for="status-${loan.p_code}">Status ${loan.p_code}</label>
                                <select id="status-${loan.p_code}" name="p_status" class="status-select">
                                    ${statusOptions}
                                </select>
                                <button type="submit" class="action-link action-edit">Simpan</button>
                            </form>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });

            if (result.has_more) {
                btn.dataset.page = result.current_page + 1;
                btn.disabled = false;
                btn.querySelector('.btn-text').style.display = 'inline-block';
                btn.querySelector('.btn-loading').style.display = 'none';
            } else {
                btn.parentElement.remove();
            }

        } catch (error) {
            console.error('Error loading more data:', error);
            btn.disabled = false;
            btn.querySelector('.btn-text').style.display = 'inline-block';
            btn.querySelector('.btn-loading').style.display = 'none';
            alert('Gagal memuat data. Silakan coba lagi.');
        }
    });
});
</script>
@endpush
@endsection
