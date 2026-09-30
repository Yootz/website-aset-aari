@extends('layouts.app')

@section('title', 'Tambah Karyawan | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Master data / People</p>
            <h1>Tambahkan anggota tim.</h1>
            <p>Hubungkan karyawan dengan divisinya agar struktur workspace tetap jelas.</p>
        </div>
        <a href="{{ route('employee.index') }}" class="secondary-button"> Kembali ke daftar</a>
    </div>


        <section class="form-panel">
            <h2>Detail karyawan</h2>
            <p>Gunakan identitas yang sesuai dengan data internal tim.</p>

            @if($errors->any())
                <div class="flash-message" data-swal-type="error" style="border-color: #f2b6ad; color: #9e3b30; background: #fff0ed;">
                    <span style="background: #f2b6ad;">!</span>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form action="{{ route('employee.store') }}" method="POST">
                @csrf
                <div class="field">
                    <label for="e_code">Kode karyawan <span class="required">*</span></label>
                    <input id="e_code" type="text" name="e_code" value="{{ old('e_code') }}" placeholder="Contoh: EMP001" required autofocus>
                    <p class="field-hint">Kode harus unik di dalam data karyawan.</p>
                    @error('e_code') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="e_name">Nama karyawan <span class="required">*</span></label>
                    <input id="e_name" type="text" name="e_name" value="{{ old('e_name') }}" placeholder="Nama lengkap" required>
                    @error('e_name') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="e_d_code">Divisi <span class="required">*</span></label>
                    <select id="e_d_code" name="e_d_code" required>
                        <option value="">Pilih divisi</option>
                        @foreach($divisions as $div)
                            <option value="{{ $div->d_code }}" @selected(old('e_d_code') === $div->d_code)>{{ $div->d_name }}</option>
                        @endforeach
                    </select>
                    <p class="field-hint">Belum ada divisi? <a href="{{ route('division.create') }}" class="inline-link">Buat divisi baru</a>.</p>
                    @error('e_d_code') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('employee.index') }}" class="secondary-button">Batal</a>
                    <button type="submit" class="primary-button"><span class="button-symbol">+</span> Simpan karyawan</button>
                </div>
            </form>
        </section>


@endsection