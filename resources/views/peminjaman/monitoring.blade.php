@extends('layouts.app')

@section('title', 'Monitoring Peminjaman | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Asset operations / Monitoring</p>
            <h1>Pastikan setiap peminjaman bergerak.</h1>
            <p>Perbarui status transaksi dan pantau aset yang masih berada di luar inventaris.</p>
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
            <span class="count-badge">{{ $peminjaman->count() }} transaksi</span>
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
                                    <form action="{{ route('peminjaman.status', $loan->p_code) }}" method="POST" class="status-form">
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
        @endif
    </section>
@endsection
