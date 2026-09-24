<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Karyawan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Tambah Karyawan Baru</h2>
    <a href="{{ route('employee.index') }}" class="btn btn-secondary mb-3">Kembali</a>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('employee.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Kode Karyawan (e_code)</label>
            <input type="text" name="e_code" class="form-control" placeholder="Contoh: EMP001" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Karyawan (e_name)</label>
            <input type="text" name="e_name" class="form-control" placeholder="Nama Lengkap" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Divisi</label>
            <select name="e_d_code" class="form-select" required>
                <option value="">-- Pilih Divisi --</option>
                @foreach($divisions as $div)
                    <option value="{{ $div->d_code }}">{{ $div->d_name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan Karyawan</button>
    </form>
</body>
</html>