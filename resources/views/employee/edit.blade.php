<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Karyawan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container mt-4">
    <h2>Edit Karyawan</h2>
    <a href="{{ route('employee.index') }}" class="btn btn-secondary mb-3">Kembali</a>

    <form action="{{ route('employee.update', $employee->e_code) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Kode Karyawan</label>
            <input type="text" class="form-control" value="{{ $employee->e_code }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Karyawan</label>
            <input type="text" name="e_name" class="form-control" value="{{ $employee->e_name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Divisi</label>
            <select name="e_d_code" class="form-select" required>
                @foreach($divisions as $div)
                    <option value="{{ $div->d_code }}" {{ $employee->e_d_code == $div->d_code ? 'selected' : '' }}>
                        {{ $div->d_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Karyawan</button>
    </form>
</body>

</html>