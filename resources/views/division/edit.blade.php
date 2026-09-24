@extends('layouts.app')

@section('title', 'Edit Divisi | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Master data / Divisi</p>
            <h1>Perbarui identitas divisi.</h1>
            <p>Jaga struktur organisasi tetap akurat dan mudah dipahami.</p>
        </div>
        <a href="{{ route('division.index') }}" class="secondary-button"><span class="button-symbol">←</span> Kembali ke daftar</a>
    </div>

    <div class="form-layout">
        <section class="form-panel">
            <h2>Edit detail divisi</h2>
            <p>Kode divisi adalah identitas tetap dan tidak dapat diubah.</p>

            @if($errors->any())
                <div class="flash-message" style="border-color: #f2b6ad; color: #9e3b30; background: #fff0ed;">
                    <span style="background: #f2b6ad;">!</span>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form action="{{ route('division.update', $division->d_code) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="field">
                    <label for="d_code">Kode divisi</label>
                    <input id="d_code" type="text" value="{{ $division->d_code }}" readonly class="field-readonly">
                    <p class="field-hint">Kode digunakan sebagai identitas unik divisi.</p>
                </div>

                <div class="field">
                    <label for="d_name">Nama divisi <span class="required">*</span></label>
                    <input id="d_name" type="text" name="d_name" value="{{ old('d_name', $division->d_name) }}" required autofocus>
                    @error('d_name') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="d_desc">Keterangan</label>
                    <textarea id="d_desc" name="d_desc" placeholder="Deskripsi singkat tentang fungsi divisi (opsional)">{{ old('d_desc', $division->d_desc) }}</textarea>
                    <p class="field-hint">Keterangan membantu tim memahami cakupan divisi.</p>
                </div>

                <div class="form-actions">
                    <a href="{{ route('division.index') }}" class="secondary-button">Batal</a>
                    <button type="submit" class="primary-button"><span class="button-symbol">✓</span> Simpan perubahan</button>
                </div>
            </form>
        </section>

        <aside class="form-note">
            <div class="form-note-mark">✦</div>
            <h3>Struktur yang selalu relevan.</h3>
            <p>Deskripsi yang jelas membantu seluruh tim memahami fungsi divisi.</p>
            <ul>
                <li>Gunakan nama divisi yang konsisten.</li>
                <li>Perbarui keterangan bila fungsi berubah.</li>
                <li>Kode divisi tetap tidak berubah.</li>
            </ul>
        </aside>
    </div>
@endsection