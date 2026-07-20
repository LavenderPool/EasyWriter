@extends('layouts.admin')

@section('title', __('admin.pages.edit', ['number' => $page->page_number]))
@section('heading', __('admin.pages.edit', ['number' => $page->page_number]))

@section('content')
    <div class="panel narrow">
        <div class="preview-block">
            <img src="{{ route('admin.mangas.pages.image', [$manga, $page]) }}" alt="{{ __('admin.common.page_n', ['number' => $page->page_number]) }}">
        </div>
        <form method="POST" action="{{ route('admin.mangas.pages.update', [$manga, $page]) }}" enctype="multipart/form-data" class="form">
            @csrf
            @method('PUT')
            <label>
                <span>{{ __('admin.pages.page_number') }}</span>
                <input type="number" name="page_number" min="1" value="{{ old('page_number', $page->page_number) }}" required>
            </label>
            <label>
                <span>{{ __('admin.pages.replace_image') }}</span>
                <input type="file" name="image" accept="image/*">
            </label>
            <div class="form-actions">
                <a href="{{ route('admin.mangas.pages.index', $manga) }}" class="btn btn-ghost">{{ __('admin.common.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('admin.common.save') }}</button>
            </div>
        </form>
    </div>
@endsection
