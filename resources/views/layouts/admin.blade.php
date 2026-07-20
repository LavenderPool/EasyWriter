<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('admin.nav.dashboard')) — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-body">
    <aside class="sidebar">
        <div class="brand">
            <a href="{{ route('admin.dashboard') }}">{{ config('app.name') }}</a>
            <span>{{ __('admin.brand_subtitle') }}</span>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">{{ __('admin.nav.dashboard') }}</a>
            <a href="{{ route('admin.mangas.index') }}" class="{{ request()->routeIs('admin.mangas.*') ? 'active' : '' }}">{{ __('admin.nav.mangas') }}</a>
            <a href="{{ route('admin.password.edit') }}" class="{{ request()->routeIs('admin.password.*') ? 'active' : '' }}">{{ __('admin.nav.password') }}</a>
        </nav>
        <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
            @csrf
            <button type="submit">{{ __('admin.nav.logout') }}</button>
        </form>
    </aside>

    <main class="main">
        <header class="topbar">
            <h1>@yield('heading', __('admin.nav.dashboard'))</h1>
            <div class="topbar-user">{{ auth()->user()->name }}</div>
        </header>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="content">
            @yield('content')
        </div>
    </main>
</body>
</html>
