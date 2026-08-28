<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | AAP Engineerings</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=3">
</head>
<body class="admin-body">
<div class="login-wrap">
    <div class="panel login-card">
        <h1 style="font-family:var(--font-display);margin:0 0 .4rem;">AAP Admin</h1>
        <p style="color:var(--muted);margin:0 0 1.2rem;">Sign in to manage projects and enquiries.</p>

        @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="form-field" style="margin-bottom:1rem;">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-field" style="margin-bottom:1rem;">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>
            <label class="checkbox-row" style="margin-bottom:1rem;">
                <input type="checkbox" name="remember" value="1">
                <span>Remember me</span>
            </label>
            <button class="btn btn-primary" type="submit" style="width:100%;">Login</button>
        </form>
    </div>
</div>
</body>
</html>
