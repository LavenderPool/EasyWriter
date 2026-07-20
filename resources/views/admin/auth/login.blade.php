<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('admin.login.title') }} — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-body">
    <div class="login-shell">
        <div class="login-panel">
            <div class="login-brand">
                <h1>{{ config('app.name') }}</h1>
                <p>{{ __('admin.login.subtitle') }}</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="login-form">
                @csrf
                <label>
                    <span>{{ __('admin.login.email') }}</span>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                </label>
                <label>
                    <span>{{ __('admin.login.password') }}</span>
                    <input type="password" name="password" required autocomplete="current-password">
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="remember" value="1">
                    <span>{{ __('admin.login.remember') }}</span>
                </label>
                <button type="submit" class="btn btn-primary btn-block">{{ __('admin.login.submit') }}</button>
            </form>
        </div>
    </div>
</body>
</html>
