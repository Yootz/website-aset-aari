<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Karyawan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container mt-4">
    <h2>Data Karyawan</h2>
    <a href="{{ route('employee.create') }}" class="btn btn-primary mb-3">+ Tambah Karyawan</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Kode Karyawan</th>
                <th>Nama Karyawan</th>
                <th>Divisi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $emp)
                <tr>
                    <td>{{ $emp->e_code }}</td>
                    <td>{{ $emp->e_name }}</td>
                    <td>{{ $emp->division->d_name ?? '-' }}</td>
                    <td>{{ $emp->division->d_name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('employee.edit', $emp->e_code) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('employee.destroy', $emp->e_code) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
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