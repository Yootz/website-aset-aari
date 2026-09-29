@extends('layouts.app')

@section('title', 'Tambah Aset | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Master data / Inventory</p>
            <h1>Daftarkan aset baru.</h1>
            <p>Lengkapi identitas aset agar inventaris mudah dicari dan dipantau.</p>
        </div>
        <a href="{{ route('asset.index') }}" class="secondary-button"> Kembali ke daftar</a>
    </div>


        <section class="form-panel">
            <h2>Detail aset</h2>
            <p>Isi informasi sesuai identitas aset yang tercatat.</p>

            @if($errors->any())
                <div class="flash-message" data-swal-type="error" style="border-color: #f2b6ad; color: #9e3b30; background: #fff0ed;">
                    <span style="background: #f2b6ad;">!</span>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form action="{{ route('asset.store') }}" method="POST">
                @csrf
                <div class="field">
                    <label for="a_code">Kode aset <span class="required">*</span></label>
                    <input id="a_code" type="text" name="a_code" value="{{ old('a_code') }}" maxlength="255" placeholder="Contoh: AST-011" required autofocus>
                    <p class="field-hint">Kode aset harus unik.</p>
                    @error('a_code') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="a_name">Nama aset <span class="required">*</span></label>
                    <input id="a_name" type="text" name="a_name" value="{{ old('a_name') }}" maxlength="255" placeholder="Contoh: Laptop Lenovo ThinkPad" required>
                    @error('a_name') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="a_type">Jenis aset <span class="required">*</span></label>
                    <input id="a_type" type="text" name="a_type" value="{{ old('a_type') }}" maxlength="255" placeholder="Contoh: Laptop, Monitor, atau Kamera" required>
                    @error('a_type') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="a_desc">Deskripsi <span class="required">*</span></label>
                    <textarea id="a_desc" name="a_desc" maxlength="255" placeholder="Keterangan singkat tentang aset" required>{{ old('a_desc') }}</textarea>
                    <p class="field-hint">Maksimal 255 karakter.</p>
                    @error('a_desc') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="a_status">Status aset <span class="required">*</span></label>
                    <select id="a_status" name="a_status" required>
                        <option value="available" @selected(old('a_status', 'available') === 'available')>Available</option>
                        <option value="unavailable" @selected(old('a_status') === 'unavailable')>Unavailable</option>
                    </select>
                    @error('a_status') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('asset.index') }}" class="secondary-button">Batal</a>
                    <button type="submit" class="primary-button"><span class="button-symbol">+</span> Simpan aset</button>
                </div>
            </form>
        </section>


@endsection