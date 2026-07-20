@extends('layouts.admin')

@section('title', __('admin.pages.upload'))
@section('heading', __('admin.pages.upload_heading', ['title' => $manga->title]))

@section('content')
    <div class="panel narrow">
        <p class="muted">{{ __('admin.pages.upload_hint') }}</p>
        <form method="POST" action="{{ route('admin.mangas.pages.store', $manga) }}" enctype="multipart/form-data" class="form">
            @csrf
            <label>
                <span>{{ __('admin.pages.images') }}</span>
                <input type="file" name="images[]" accept="image/*" multiple required>
            </label>
            <label>
                <span>{{ __('admin.pages.start_number') }}</span>
                <input type="number" name="start_number" min="1" value="{{ old('start_number', $nextNumber) }}">
            </label>
            <div class="form-actions">
                <a href="{{ route('admin.mangas.pages.index', $manga) }}" class="btn btn-ghost">{{ __('admin.common.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('admin.pages.upload') }}</button>
            </div>
        </form>
    </div>
@endsection
