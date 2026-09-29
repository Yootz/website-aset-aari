@extends('layouts.app')

@section('title', 'Laporan Peminjaman Bulanan | AARI')
@section('body-class', 'report-page')

@section('content')
    <div class="report-print-root">
    <div class="page-heading">
        <div>
            <p class="eyebrow">Asset operations / Report</p>
            <h1>Laporan peminjaman bulanan.</h1>
        </div>
        <button id="report-print" class="secondary-button report-print-button" type="button"><span class="button-symbol" aria-hidden="true">▧</span> Cetak laporan</button>
    </div>

    <section class="report-toolbar" aria-label="Filter periode laporan">
        <form id="report-filter" class="report-filter">
            <div class="report-period-field">
                <label for="report-period">Periode laporan</label>
                <input id="report-period" name="period" type="month" value="{{ now()->format('Y-m') }}" required>
            </div>
            <button class="primary-button" type="submit">Muat laporan</button>
        </form>
        <p id="report-feedback" class="report-feedback" role="status" aria-live="polite">Memuat laporan...</p>
    </section>

    <div class="report-summary" aria-label="Ringkasan laporan">
        <div class="report-summary-item">
            <span>Transaksi</span>
            <strong id="report-total-loans">-</strong>
        </div>
        <div class="report-summary-item report-summary-assets">
            <span>Detail aset dipinjam</span>
            <strong id="report-total-assets">-</strong>
        </div>
    </div>

    <section class="data-panel report-panel">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Detail peminjaman</h2>
            </div>
            <span id="report-period-label" class="count-badge">-</span>
        </div>
        <div class="table-wrap">
            <table class="division-table report-table">
                <thead>
                    <tr>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Aset</th>
                        <th scope="col">Dipinjam oleh</th>
                        <th scope="col">Durasi</th>
                        <th scope="col">Status pinjam</th>
                        <th scope="col">Status aset</th>
                    </tr>
                </thead>
                <tbody id="report-rows">
                    <tr><td colspan="6" class="report-empty-cell">Memuat laporan...</td></tr>
                </tbody>
            </table>
        </div>
    </section>

    <script>
        document.getElementById('report-print').addEventListener('click', () => window.print());

        const reportForm = document.getElementById('report-filter');
        const reportPeriod = document.getElementById('report-period');
        const reportRows = document.getElementById('report-rows');
        const reportFeedback = document.getElementById('report-feedback');
        const reportTotalLoans = document.getElementById('report-total-loans');
        const reportTotalAssets = document.getElementById('report-total-assets');
        const reportPeriodLabel = document.getElementById('report-period-label');
        const reportEndpoint = @json(route('api.laporan.peminjaman.bulanan'));
        const knownStatuses = new Set(['pending', 'approved', 'returned', 'rejected', 'borrowed']);
        let reportWasFiltered = false;

        function notifyReport(type, message, title) {
            const notification = { icon: type, title, text: message, confirmButtonText: 'Tutup' };

            if (window.AARIAlerts) {
                window.AARIAlerts.show(notification);
                return;
            }

            window.__aariAlertsQueue ??= [];
            window.__aariAlertsQueue.push(notification);
        }

        function appendStackedCell(row, primaryText, secondaryText) {
            const cell = document.createElement('td');
            const primary = document.createElement('strong');
            primary.className = 'division-name';
            primary.textContent = primaryText || '-';
            cell.append(primary);

            if (secondaryText) {
                const secondary = document.createElement('span');
                secondary.className = 'table-subline';
                secondary.textContent = secondaryText;
                cell.append(secondary);
            }

            row.append(cell);
        }

        function appendStatusCell(row, status) {
            const cell = document.createElement('td');
            const badge = document.createElement('span');
            const normalizedStatus = String(status || '').toLowerCase();
            badge.className = `status-chip${knownStatuses.has(normalizedStatus) ? ` status-${normalizedStatus}` : ''}`;
            badge.textContent = status || '-';
            cell.append(badge);
            row.append(cell);
        }

        function formatDate(dateValue) {
            if (!dateValue) {
                return '-';
            }

            return new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium' })
                .format(new Date(`${dateValue}T00:00:00`));
        }

        function renderRows(rows) {
            reportRows.replaceChildren();

            if (rows.length === 0) {
                const row = document.createElement('tr');
                const cell = document.createElement('td');
                cell.colSpan = 6;
                cell.className = 'report-empty-cell';
                cell.textContent = 'Tidak ada peminjaman pada periode ini.';
                row.append(cell);
                reportRows.append(row);
                return;
            }

            rows.forEach((item) => {
                const row = document.createElement('tr');
                appendStackedCell(row, formatDate(item.tgl_pinjam), `Kembali ${formatDate(item.tgl_balik)} · ${item.p_code}`);
                appendStackedCell(row, item.aset?.nama, item.aset?.a_code);
                appendStackedCell(row, item.peminjam?.nama, item.peminjam?.e_code);
                appendStackedCell(row, item.durasi_hari === null ? 'Belum ditentukan' : `${item.durasi_hari} hari`);
                appendStatusCell(row, item.status_peminjaman);
                appendStatusCell(row, item.status_aset);
                reportRows.append(row);
            });
        }

        async function loadReport() {
            const shouldNotifySuccess = reportWasFiltered;
            reportWasFiltered = false;
            const [year, month] = reportPeriod.value.split('-').map(Number);
            const query = new URLSearchParams({ month: String(month), year: String(year) });

            reportFeedback.textContent = 'Memuat laporan...';
            reportFeedback.dataset.state = 'loading';
            reportForm.querySelector('button').disabled = true;

            try {
                const response = await fetch(`${reportEndpoint}?${query}`, {
                    headers: { Accept: 'application/json' },
                });

                if (!response.ok) {
                    throw new Error('Laporan tidak dapat dimuat. Silakan coba lagi.');
                }

                const report = await response.json();
                renderRows(report.data);
                reportTotalLoans.textContent = report.summary.total_transaksi;
                reportTotalAssets.textContent = report.summary.total_aset;
                reportPeriodLabel.textContent = new Intl.DateTimeFormat('id-ID', {
                    month: 'long',
                    year: 'numeric',
                }).format(new Date(report.period.year, report.period.month - 1, 1));
                reportFeedback.textContent = report.data.length
                    ? `${report.data.length} detail aset ditemukan.`
                    : 'Tidak ada data untuk periode ini.';
                reportFeedback.dataset.state = '';
                if (shouldNotifySuccess) {
                    notifyReport('success', 'Laporan berhasil diperbarui.', 'Berhasil');
                }
            } catch (error) {
                renderRows([]);
                reportTotalLoans.textContent = '0';
                reportTotalAssets.textContent = '0';
                reportFeedback.textContent = '';
                reportFeedback.dataset.state = 'error';
                notifyReport('error', error.message, 'Laporan gagal dimuat');
            } finally {
                reportForm.querySelector('button').disabled = false;
            }
        }

        reportForm.addEventListener('submit', (event) => {
            event.preventDefault();
            reportWasFiltered = true;
            loadReport();
        });

        loadReport();
    </script>
    </div>
@endsection