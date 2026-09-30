@extends('layouts.app')

@section('title', 'Tambah Divisi | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Master data / Divisi</p>
            <h1>Tambahkan divisi baru.</h1>
            <p>Lengkapi identitas divisi agar pengelolaan aset dan karyawan tetap rapi.</p>
        </div>
        <a href="{{ route('division.index') }}" class="secondary-button"> Kembali ke daftar</a>
    </div>


        <section class="form-panel">
            <h2>Detail divisi</h2>
            <p>Gunakan nama yang mudah dikenali oleh seluruh tim.</p>

            @if($errors->any())
                <div class="flash-message" data-swal-type="error" style="border-color: #f2b6ad; color: #9e3b30; background: #fff0ed;">
                    <span style="background: #f2b6ad;">!</span>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form action="{{ route('division.store') }}" method="POST">
                @csrf
                <div class="field">
                    <label for="d_code">Kode divisi <span class="required">*</span></label>
                    <input id="d_code" type="text" name="d_code" value="{{ old('d_code') }}" placeholder="Contoh: DIV004" required autofocus>
                    <p class="field-hint">Kode harus unik, misalnya DIV004 atau OPS01.</p>
                    @error('d_code') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="d_name">Nama divisi <span class="required">*</span></label>
                    <input id="d_name" type="text" name="d_name" value="{{ old('d_name') }}" placeholder="Contoh: Operasional" required>
                    @error('d_name') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="d_desc">Keterangan</label>
                    <textarea id="d_desc" name="d_desc" placeholder="Deskripsi singkat tentang fungsi divisi (opsional)">{{ old('d_desc') }}</textarea>
                    <p class="field-hint">Keterangan membantu tim memahami cakupan divisi.</p>
                </div>

                <div class="form-actions">
                    <a href="{{ route('division.index') }}" class="secondary-button">Batal</a>
                    <button type="submit" class="primary-button"><span class="button-symbol">+</span> Simpan divisi</button>
                </div>
            </form>
        </section>
@endsection