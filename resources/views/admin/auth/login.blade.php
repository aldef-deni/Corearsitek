<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <meta name="theme-color" content="#060607">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-body">
<div class="login-card">
    <div class="login-brand">
        <img src="{{ asset('images/logo.png') }}" alt="CoreArsitek" class="login-logo">
        <h1>ADMIN PANEL</h1>
        <p>Masuk untuk mengelola situs</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        <div class="field">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <label class="checkbox">
            <input type="checkbox" name="remember"> Ingat saya
        </label>
        <button type="submit" class="btn btn-red btn-block">MASUK</button>
    </form>

    <p class="login-back"><a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left"></i> Kembali ke situs</a></p>
</div>

{{-- Dipakai untuk tombol lihat password; berkas yang sama dengan dashboard. --}}
<script src="{{ asset('js/admin.js') }}?v={{ @filemtime(public_path('js/admin.js')) ?: 1 }}"></script>
</body>
</html>