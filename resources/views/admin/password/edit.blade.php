@extends('layouts.admin')

@section('title', __('admin.password.title'))
@section('heading', __('admin.password.heading'))

@section('content')
    <div class="panel narrow">
        <p class="muted">{{ __('admin.password.hint') }}</p>
        <form method="POST" action="{{ route('admin.password.update') }}" class="form">
            @csrf
            @method('PUT')
            <label>
                <span>{{ __('admin.password.current') }}</span>
                <input type="password" name="current_password" required autocomplete="current-password">
            </label>
            <label>
                <span>{{ __('admin.password.new') }}</span>
                <input type="password" name="password" required autocomplete="new-password" minlength="12">
            </label>
            <label>
                <span>{{ __('admin.password.confirm') }}</span>
                <input type="password" name="password_confirmation" required autocomplete="new-password" minlength="12">
            </label>
            <div class="form-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost">{{ __('admin.common.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('admin.password.submit') }}</button>
            </div>
        </form>
    </div>
@endsection
