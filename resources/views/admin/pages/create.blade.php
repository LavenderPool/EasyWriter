@extends('layouts.admin')

@section('title', 'Upload pages')
@section('heading', 'Upload pages: '.$manga->title)

@section('content')
    <div class="panel narrow">
        <p class="muted">One image = one page. You can select multiple images at once.</p>
        <form method="POST" action="{{ route('admin.mangas.pages.store', $manga) }}" enctype="multipart/form-data" class="form">
            @csrf
            <label>
                <span>Images</span>
                <input type="file" name="images[]" accept="image/*" multiple required>
            </label>
            <label>
                <span>Start page number</span>
                <input type="number" name="start_number" min="1" value="{{ old('start_number', $nextNumber) }}">
            </label>
            <div class="form-actions">
                <a href="{{ route('admin.mangas.pages.index', $manga) }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
@endsection
