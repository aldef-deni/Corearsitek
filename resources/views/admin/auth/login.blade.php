<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-body">
<div class="login-card">
    <div class="login-brand">
        <span class="brand-emblem"><i class="fa-solid fa-crown"></i></span>
        <h1>COREARSITEK ADMIN</h1>
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
        <button type="submit" class="btn btn-gold btn-block">MASUK</button>
    </form>

    <p class="login-hint">Akun default: <code>admin@corearsitek.com</code> / <code>admin123</code></p>
    <p class="login-back"><a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left"></i> Kembali ke situs</a></p>
</div>
</body>
</html>