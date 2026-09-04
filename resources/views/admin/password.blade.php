@extends('admin.layouts.app')

@section('title', 'Ubah Password')

@section('content')
    <h1 class="page-title">Ubah Password</h1>

    <div class="card" style="max-width: 480px;">
        <h2>Ganti Password Akun</h2>
        <form method="POST" action="{{ route('admin.password.update') }}">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="current_password">Password Saat Ini</label>
                <input type="password" name="current_password" id="current_password" required autocomplete="current-password">
            </div>

            <div class="field">
                <label for="password">Password Baru</label>
                <input type="password" name="password" id="password" minlength="8" required autocomplete="new-password">
                <small class="hint">Minimal 8 karakter.</small>
            </div>

            <div class="field">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-red"><i class="fa-solid fa-key"></i> Simpan Password Baru</button>
        </form>
    </div>
@endsection