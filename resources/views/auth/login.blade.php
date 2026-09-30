@extends('layouts.app')

@section('title', 'Login Admin | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow"></p>
            <h1>Masuk sebagai admin.</h1>
            <p>Masukkan password admin untuk membuka pengelolaan workspace.</p>
        </div>
    </div>

    <section class="form-panel auth-panel">
        <h2>Autentikasi admin</h2>
        <p>Akses admin dibutuhkan untuk mengubah data dan mengelola peminjaman.</p>

        @if($errors->any())
            <div class="flash-message" data-swal-type="error" style="border-color: #f2b6ad; color: #9e3b30; background: #fff0ed;">
                <span style="background: #f2b6ad;">!</span>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST">
            @csrf
            <div class="field">
                <label for="password">Password admin <span class="required">*</span></label>
                <input id="password" type="password" name="password" autocomplete="current-password" required autofocus>
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-actions">
                <a href="{{ route('asset.index') }}" class="secondary-button">Kembali ke aset</a>
                <button type="submit" class="primary-button">Masuk</button>
            </div>
        </form>
    </section>
@endsection