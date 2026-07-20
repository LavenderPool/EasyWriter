@extends('layouts.admin')

@section('title', __('admin.mangas.edit'))
@section('heading', __('admin.mangas.edit'))

@section('content')
    <div class="panel narrow">
        <form method="POST" action="{{ route('admin.mangas.update', $manga) }}" enctype="multipart/form-data" class="form">
            @csrf
            @method('PUT')
            <label>
                <span>{{ __('admin.common.title') }}</span>
                <input type="text" name="title" value="{{ old('title', $manga->title) }}" required maxlength="255">
            </label>
            <label>
                <span>{{ __('admin.mangas.description') }}</span>
                <textarea name="description" rows="4">{{ old('description', $manga->description) }}</textarea>
            </label>
            <label>
                <span>{{ __('admin.mangas.cover') }}</span>
                <input type="file" name="cover" accept="image/*">
            </label>
            <label class="checkbox">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $manga->is_published) ? 'checked' : '' }}>
                <span>{{ __('admin.mangas.publish') }}</span>
            </label>
            <div class="form-actions">
                <a href="{{ route('admin.mangas.show', $manga) }}" class="btn btn-ghost">{{ __('admin.common.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('admin.common.save') }}</button>
            </div>
        </form>
    </div>
@endsection
