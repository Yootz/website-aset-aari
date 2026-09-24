<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Divisi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Edit Divisi</h2>
    <a href="{{ route('division.index') }}" class="btn btn-secondary mb-3">Kembali</a>

    <form action="{{ route('division.update', $division->d_code) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label class="form-label">Kode Divisi</label>
            <input type="text" class="form-control" value="{{ $division->d_code }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Divisi</label>
            <input type="text" name="d_name" class="form-control" value="{{ $division->d_name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea name="d_desc" class="form-control" rows="3">{{ $division->d_desc }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Divisi</button>
    </form>
</body>
</html>