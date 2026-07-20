@extends('layouts.admin')

@section('title', 'Edit manga')
@section('heading', 'Edit manga')

@section('content')
    <div class="panel narrow">
        <form method="POST" action="{{ route('admin.mangas.update', $manga) }}" enctype="multipart/form-data" class="form">
            @csrf
            @method('PUT')
            <label>
                <span>Title</span>
                <input type="text" name="title" value="{{ old('title', $manga->title) }}" required maxlength="255">
            </label>
            <label>
                <span>Description</span>
                <textarea name="description" rows="4">{{ old('description', $manga->description) }}</textarea>
            </label>
            <label>
                <span>Cover (optional)</span>
                <input type="file" name="cover" accept="image/*">
            </label>
            <label class="checkbox">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $manga->is_published) ? 'checked' : '' }}>
                <span>Publish privately (enable share links)</span>
            </label>
            <div class="form-actions">
                <a href="{{ route('admin.mangas.show', $manga) }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection
