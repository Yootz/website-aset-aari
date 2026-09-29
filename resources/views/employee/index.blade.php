@extends('layouts.app')

@section('title', 'Karyawan | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow"></p>
            <h1>Daftar Karyawan</h1>
            <p>{{ auth()->user()?->is_admin ? 'Kelola anggota karyawan dalam sistem manajemen aset.' : 'Lihat daftar karyawan dalam sistem manajemen aset.' }}</p>
        </div>
        @if(auth()->user()?->is_admin)
            <a href="{{ route('employee.create') }}" class="primary-button"><span class="button-symbol">+</span> Tambah karyawan</a>
        @endif
    </div>

    @if(session('success'))
        <div class="flash-message"><span>✓</span> {{ session('success') }}</div>
    @endif

    <section class="data-panel">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Daftar karyawan</h2>
            </div>
            <span class="count-badge">{{ $employees->total() }} orang</span>
        </div>

        @if($employees->isEmpty())
            <div class="empty-state">
                <div class="empty-symbol">+</div>
                <h3>Belum ada karyawan</h3>
                <p>Tambahkan anggota tim pertama untuk mulai mengisi workspace.</p>
                @if(auth()->user()?->is_admin)
                    <a href="{{ route('employee.create') }}" class="primary-button">Tambah karyawan pertama</a>
                @endif
            </div>
        @else
            <div class="table-wrap">
                <table class="division-table">
                    <thead>
                        <tr>
                            <th scope="col">Kode</th>
                            <th scope="col">Nama karyawan</th>
                            <th scope="col">Divisi</th>
                            @if(auth()->user()?->is_admin)
                                <th scope="col" class="action-column">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="employee-table-body">
                        @foreach($employees as $emp)
                            <tr>
                                <td><span class="code-chip">{{ $emp->e_code }}</span></td>
                                <td class="division-name">{{ $emp->e_name }}</td>
                                <td class="description">{{ $emp->division->d_name ?? 'Belum ditentukan' }}</td>
                                @if(auth()->user()?->is_admin)
                                    <td class="action-column">
                                        <div class="action-group">
                                            <a href="{{ route('employee.edit', $emp->e_code) }}" class="action-link action-edit" aria-label="Edit {{ $emp->e_name }}">Edit</a>
                                            <form action="{{ route('employee.destroy', $emp->e_code) }}" method="POST" data-swal-confirm data-swal-title="Hapus karyawan?" data-swal-text="Data karyawan ini akan dihapus. Tindakan ini tidak dapat dibatalkan." data-swal-confirm-color="#b42318">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-link action-delete" aria-label="Hapus {{ $emp->e_name }}">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Show More Button --}}
            @if($employees->hasMorePages())
                <div class="show-more-container" id="employee-show-more">
                    <button type="button" class="show-more-btn" data-url="{{ route('employee.loadMore') }}" data-page="2" data-has-more="true">
                        <span class="btn-text">Muat lebih banyak</span>
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
    const showMoreBtn = document.querySelector('#employee-show-more .show-more-btn');
    const tableBody = document.querySelector('#employee-table-body');
    const isAdmin = {{ auth()->user()?->is_admin ? 'true' : 'false' }};
    
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
                    data.data.forEach(emp => {
                        const row = document.createElement('tr');
                        let actionHtml = '';
                        if (isAdmin) {
                            actionHtml = `
                                <td class="action-column">
                                    <div class="action-group">
                                        <a href="/employee/${emp.e_code}/edit" class="action-link action-edit" aria-label="Edit ${emp.e_name}">Edit</a>
                                        <form action="/employee/${emp.e_code}" method="POST" data-swal-confirm data-swal-title="Hapus karyawan?" data-swal-text="Data karyawan ini akan dihapus. Tindakan ini tidak dapat dibatalkan." data-swal-confirm-color="#b42318">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="action-link action-delete" aria-label="Hapus ${emp.e_name}">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            `;
                        }
                        row.innerHTML = `
                            <td><span class="code-chip">${emp.e_code}</span></td>
                            <td class="division-name">${emp.e_name}</td>
                            <td class="description">${emp.division?.d_name ?? 'Belum ditentukan'}</td>
                            ${actionHtml}
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
                console.error('Error loading more employees:', error);
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