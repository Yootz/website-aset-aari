<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Divisi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container mt-4">
    <h2>Data Divisi</h2>
    <a href="{{ route('division.create') }}" class="btn btn-primary mb-3">+ Tambah Divisi</a>
    <a href="{{ route('employee.index') }}" class="btn btn-secondary mb-3 float-end">Kelola Karyawan</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Kode Divisi</th>
                <th>Nama Divisi</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($divisions as $div)
                <tr>
                    <td>{{ $div->d_code }}</td>
                    <td>{{ $div->d_name }}</td>
                    <td>{{ $div->d_desc ?? '-' }}</td>
                    <td>{{ $div->d_desc ?? '-' }}</td>
                    <td>
                        <a href="{{ route('division.edit', $div->d_code) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('division.destroy', $div->d_code) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus divisi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>