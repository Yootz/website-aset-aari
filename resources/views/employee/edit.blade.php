@extends('layouts.app')

@section('title', 'Edit Karyawan | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Master data / People</p>
            <h1>Perbarui data anggota tim.</h1>
            <p>Pastikan informasi karyawan dan divisinya tetap sesuai.</p>
        </div>
        <a href="{{ route('employee.index') }}" class="secondary-button"><span class="button-symbol">←</span> Kembali ke daftar</a>
    </div>

    <div class="form-layout">
        <section class="form-panel">
            <h2>Edit detail karyawan</h2>
            <p>Kode karyawan adalah identitas tetap dan tidak dapat diubah.</p>

            @if($errors->any())
                <div class="flash-message" style="border-color: #f2b6ad; color: #9e3b30; background: #fff0ed;">
                    <span style="background: #f2b6ad;">!</span>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form action="{{ route('employee.update', $employee->e_code) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="field">
                    <label for="e_code">Kode karyawan</label>
                    <input id="e_code" type="text" value="{{ $employee->e_code }}" readonly class="field-readonly">
                    <p class="field-hint">Kode digunakan sebagai identitas unik karyawan.</p>
                </div>

                <div class="field">
                    <label for="e_name">Nama karyawan <span class="required">*</span></label>
                    <input id="e_name" type="text" name="e_name" value="{{ old('e_name', $employee->e_name) }}" required autofocus>
                    @error('e_name') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="e_d_code">Divisi <span class="required">*</span></label>
                    <select id="e_d_code" name="e_d_code" required>
                        @foreach($divisions as $div)
                            <option value="{{ $div->d_code }}" @selected(old('e_d_code', $employee->e_d_code) === $div->d_code)>{{ $div->d_name }}</option>
                        @endforeach
                    </select>
                    @error('e_d_code') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('employee.index') }}" class="secondary-button">Batal</a>
                    <button type="submit" class="primary-button"><span class="button-symbol">✓</span> Simpan perubahan</button>
                </div>
            </form>
        </section>

        <aside class="form-note">
            <div class="form-note-mark">✦</div>
            <h3>Perubahan kecil tetap berarti.</h3>
            <p>Jaga informasi tim tetap akurat agar dashboard selalu bisa dipercaya.</p>
            <ul>
                <li>Periksa nama sebelum menyimpan.</li>
                <li>Pastikan divisi sudah sesuai.</li>
                <li>Kode karyawan tetap tidak berubah.</li>
            </ul>
        </aside>
    </div>
@endsection