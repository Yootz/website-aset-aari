@extends('layouts.app')

@section('title', 'Divisi | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Master data / Overview</p>
            <h1>Struktur tim, lebih mudah dilihat.</h1>
            <p>Kelola divisi yang menjadi dasar pembagian aset dan tanggung jawab di AARI.</p>
        </div>
        <a href="{{ route('division.create') }}" class="primary-button"><span class="button-symbol">+</span> Tambah divisi</a>
    </div>

    @if(session('success'))
        <div class="flash-message"><span>✓</span> {{ session('success') }}</div>
    @endif

    <section class="data-panel">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Daftar divisi</h2>
                <p class="panel-caption">Informasi unit kerja yang tersedia saat ini.</p>
            </div>
            <span class="count-badge">{{ $divisions->count() }} unit</span>
        </div>

        @if($divisions->isEmpty())
            <div class="empty-state">
                <div class="empty-symbol">+</div>
                <h3>Belum ada divisi</h3>
                <p>Mulai dengan menambahkan divisi pertama untuk workspace ini.</p>
                <a href="{{ route('division.create') }}" class="primary-button">Tambah divisi pertama</a>
            </div>
        @else
            <div class="table-wrap">
                <table class="division-table">
                    <thead>
                        <tr>
                            <th scope="col">Kode</th>
                            <th scope="col">Nama divisi</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col" class="action-column">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($divisions as $div)
                            <tr>
                                <td><span class="code-chip">{{ $div->d_code }}</span></td>
                                <td class="division-name">{{ $div->d_name }}</td>
                                <td class="description">{{ $div->d_desc ?: 'Belum ada keterangan.' }}</td>
                                <td class="action-column">
                                    <div class="action-group">
                                        <a href="{{ route('division.edit', $div->d_code) }}" class="action-link action-edit" aria-label="Edit {{ $div->d_name }}">Edit</a>
                                        <form action="{{ route('division.destroy', $div->d_code) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus divisi ini? Data karyawan di dalamnya juga akan terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-link action-delete" aria-label="Hapus {{ $div->d_name }}">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
