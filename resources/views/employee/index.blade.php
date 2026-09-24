@extends('layouts.app')

@section('title', 'Karyawan | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Master data / People</p>
            <h1>Kenali orang di balik kerja.</h1>
            <p>Kelola anggota tim dan hubungkan setiap orang dengan divisinya.</p>
        </div>
        <a href="{{ route('employee.create') }}" class="primary-button"><span class="button-symbol">+</span> Tambah karyawan</a>
    </div>

    @if(session('success'))
        <div class="flash-message"><span>✓</span> {{ session('success') }}</div>
    @endif

    <section class="data-panel">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Daftar karyawan</h2>
                <p class="panel-caption">Anggota tim yang terdaftar di workspace.</p>
            </div>
            <span class="count-badge">{{ $employees->count() }} orang</span>
        </div>

        @if($employees->isEmpty())
            <div class="empty-state">
                <div class="empty-symbol">+</div>
                <h3>Belum ada karyawan</h3>
                <p>Tambahkan anggota tim pertama untuk mulai mengisi workspace.</p>
                <a href="{{ route('employee.create') }}" class="primary-button">Tambah karyawan pertama</a>
            </div>
        @else
            <div class="table-wrap">
                <table class="division-table">
                    <thead>
                        <tr>
                            <th scope="col">Kode</th>
                            <th scope="col">Nama karyawan</th>
                            <th scope="col">Divisi</th>
                            <th scope="col" class="action-column">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $emp)
                            <tr>
                                <td><span class="code-chip">{{ $emp->e_code }}</span></td>
                                <td class="division-name">{{ $emp->e_name }}</td>
                                <td class="description">{{ $emp->division->d_name ?? 'Belum ditentukan' }}</td>
                                <td class="action-column">
                                    <div class="action-group">
                                        <a href="{{ route('employee.edit', $emp->e_code) }}" class="action-link action-edit" aria-label="Edit {{ $emp->e_name }}">Edit</a>
                                        <form action="{{ route('employee.destroy', $emp->e_code) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-link action-delete" aria-label="Hapus {{ $emp->e_name }}">Hapus</button>
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