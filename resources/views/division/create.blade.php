<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Divisi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Tambah Divisi Baru</h2>
    <a href="{{ route('division.index') }}" class="btn btn-secondary mb-3">Kembali</a>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('division.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Kode Divisi (d_code)</label>
            <input type="text" name="d_code" class="form-control" placeholder="Contoh: DIV004" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Divisi (d_name)</label>
            <input type="text" name="d_name" class="form-control" placeholder="Contoh: Operasional" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Keterangan (d_desc)</label>
            <textarea name="d_desc" class="form-control" rows="3" placeholder="Deskripsi singkat divisi (opsional)"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Simpan Divisi</button>
    </form>
</body>
</html>