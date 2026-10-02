@extends('layouts.app')

@section('title', 'Laporan Daftar Aset | AARI')
@section('body-class', 'report-page')

@section('content')
    <div class="report-print-root">
        <div class="page-heading">
            <div>
                <p class="eyebrow">Asset operations / Report</p>
                <h1>Laporan daftar aset</h1>
                <p>Daftar status inventaris per {{ now()->format('d M Y H:i') }}.</p>
            </div>
            <button id="asset-report-print" class="secondary-button report-print-button" type="button">
                <span class="button-symbol" aria-hidden="true">▧</span> Cetak laporan
            </button>
        </div>

        <form action="{{ route('laporan.aset') }}" method="GET" class="report-toolbar" aria-label="Filter laporan aset">
            <div class="report-period-field">
                <label for="asset-report-status">Status aset</label>
                <select id="asset-report-status" name="a_status" class="status-select">
                    @foreach(['all' => 'Semua status', 'available' => 'Available', 'unavailable' => 'Unavailable', 'pending' => 'Pending', 'maintenance' => 'Maintenance'] as $value => $label)
                        <option value="{{ $value }}" @selected($assetStatus === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="primary-button">Terapkan filter</button>
        </form>

        <p class="report-filter-summary">Filter diterapkan: <strong>{{ $assetStatusLabel }}</strong> ·
            {{ $assets->count() }} aset</p>

        <section class="data-panel report-panel">
            <div class="panel-head">
                <div>
                    <h2 class="panel-title">Daftar aset saat ini</h2>
                </div>
                <span class="count-badge">{{ $assets->count() }} aset</span>
            </div>
            <div class="table-wrap">
                <table class="division-table report-table">
                    <thead>
                        <tr>
                            <th scope="col">Kode aset</th>
                            <th scope="col">Nama aset</th>
                            <th scope="col">Status aset</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                            <tr>
                                <td><span class="code-chip">{{ $asset->a_code }}</span></td>
                                <td class="division-name">{{ $asset->a_name }}</td>
                                <td><span
                                        class="status-chip status-{{ strtolower(trim($asset->a_status)) }}">{{ ucfirst($asset->a_status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="report-empty-cell">Belum ada aset terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script>
        document.getElementById('asset-report-print').addEventListener('click', () => window.print());
    </script>
@endsection