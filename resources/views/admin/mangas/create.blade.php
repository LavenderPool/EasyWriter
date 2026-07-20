@extends('layouts.admin')

@section('title', 'New manga')
@section('heading', 'New manga')

@section('content')
    <div class="panel narrow">
        <form method="POST" action="{{ route('admin.mangas.store') }}" enctype="multipart/form-data" class="form">
            @csrf
            <label>
                <span>Title</span>
                <input type="text" name="title" value="{{ old('title') }}" required maxlength="255">
            </label>
            <label>
                <span>Description</span>
                <textarea name="description" rows="4">{{ old('description') }}</textarea>
            </label>
            <label>
                <span>Cover (optional)</span>
                <input type="file" name="cover" accept="image/*">
            </label>
            <label class="checkbox">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                <span>Publish privately (enable share links)</span>
            </label>
            <div class="form-actions">
                <a href="{{ route('admin.mangas.index') }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
@endsection
