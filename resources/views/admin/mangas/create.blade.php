@extends('layouts.admin')

@section('title', __('admin.mangas.new'))
@section('heading', __('admin.mangas.new'))

@section('content')
    <div class="panel narrow">
        <form method="POST" action="{{ route('admin.mangas.store') }}" enctype="multipart/form-data" class="form">
            @csrf
            <label>
                <span>{{ __('admin.common.title') }}</span>
                <input type="text" name="title" value="{{ old('title') }}" required maxlength="255">
            </label>
            <label>
                <span>{{ __('admin.mangas.description') }}</span>
                <textarea name="description" rows="4">{{ old('description') }}</textarea>
            </label>
            <label>
                <span>{{ __('admin.mangas.cover') }}</span>
                <input type="file" name="cover" accept="image/*">
            </label>
            <label class="checkbox">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                <span>{{ __('admin.mangas.publish') }}</span>
            </label>
            <div class="form-actions">
                <a href="{{ route('admin.mangas.index') }}" class="btn btn-ghost">{{ __('admin.common.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('admin.common.create') }}</button>
            </div>
        </form>
    </div>
@endsection
