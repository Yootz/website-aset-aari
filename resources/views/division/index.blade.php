@extends('layouts.app')

@section('title', 'Divisi | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow"></p>
            <h1>Daftar Divisi</h1>
            <p>{{ auth()->user()?->is_admin ? 'Kelola divisi dalam sistem manajemen aset.' : 'Lihat daftar divisi dalam sistem manajemen aset.' }}</p>
        </div>
        @if(auth()->user()?->is_admin)
            <a href="{{ route('division.create') }}" class="primary-button"><span class="button-symbol">+</span> Tambah divisi</a>
        @endif
    </div>

    @if(session('success'))
        <div class="flash-message"><span>✓</span> {{ session('success') }}</div>
    @endif

    <section class="data-panel">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Daftar divisi</h2>
            </div>
            <span class="count-badge">{{ $divisions->total() }} unit</span>
        </div>

        @if($divisions->isEmpty())
            <div class="empty-state">
                <div class="empty-symbol">+</div>
                <h3>Belum ada divisi</h3>
                <p>Mulai dengan menambahkan divisi pertama untuk workspace ini.</p>
                @if(auth()->user()?->is_admin)
                    <a href="{{ route('division.create') }}" class="primary-button">Tambah divisi pertama</a>
                @endif
            </div>
        @else
            <div class="table-wrap">
                <table class="division-table">
                    <thead>
                        <tr>
                            <th scope="col">Kode</th>
                            <th scope="col">Nama divisi</th>
                            <th scope="col">Keterangan</th>
                            @if(auth()->user()?->is_admin)
                                <th scope="col" class="action-column">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="division-table-body">
                        @foreach($divisions as $div)
                            <tr>
                                <td><span class="code-chip">{{ $div->d_code }}</span></td>
                                <td class="division-name">{{ $div->d_name }}</td>
                                <td class="description">{{ $div->d_desc ?: 'Belum ada keterangan.' }}</td>
                                @if(auth()->user()?->is_admin)
                                    <td class="action-column">
                                        <div class="action-group">
                                            <a href="{{ route('division.edit', $div->d_code) }}" class="action-link action-edit" aria-label="Edit {{ $div->d_name }}">Edit</a>
                                            <form action="{{ route('division.destroy', $div->d_code) }}" method="POST" data-swal-confirm data-swal-title="Hapus divisi?" data-swal-text="Menghapus divisi ini juga akan menghapus data karyawan di dalamnya. Tindakan ini tidak dapat dibatalkan." data-swal-confirm-color="#b42318">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-link action-delete" aria-label="Hapus {{ $div->d_name }}">Hapus</button>
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
            @if($divisions->hasMorePages())
                <div class="show-more-container" id="division-show-more">
                    <button type="button" class="show-more-btn" data-url="{{ route('division.loadMore') }}" data-page="2" data-has-more="true">
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
    const showMoreBtn = document.querySelector('#division-show-more .show-more-btn');
    const tableBody = document.querySelector('#division-table-body');
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
                    data.data.forEach(div => {
                        const row = document.createElement('tr');
                        let actionHtml = '';
                        if (isAdmin) {
                            actionHtml = `
                                <td class="action-column">
                                    <div class="action-group">
                                        <a href="/division/${div.d_code}/edit" class="action-link action-edit" aria-label="Edit ${div.d_name}">Edit</a>
                                        <form action="/division/${div.d_code}" method="POST" data-swal-confirm data-swal-title="Hapus divisi?" data-swal-text="Menghapus divisi ini juga akan menghapus data karyawan di dalamnya. Tindakan ini tidak dapat dibatalkan." data-swal-confirm-color="#b42318">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="action-link action-delete" aria-label="Hapus ${div.d_name}">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            `;
                        }
                        row.innerHTML = `
                            <td><span class="code-chip">${div.d_code}</span></td>
                            <td class="division-name">${div.d_name}</td>
                            <td class="description">${div.d_desc ?? 'Belum ada keterangan.'}</td>
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
                console.error('Error loading more divisions:', error);
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
