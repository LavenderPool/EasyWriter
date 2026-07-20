@extends('layouts.admin')

@section('title', $manga->title)
@section('heading', $manga->title)

@section('content')
    <div class="toolbar">
        <a href="{{ route('admin.mangas.edit', $manga) }}" class="btn btn-ghost">Edit</a>
        <a href="{{ route('admin.mangas.pages.index', $manga) }}" class="btn btn-primary">Pages</a>
        <a href="{{ route('admin.mangas.links.index', $manga) }}" class="btn btn-primary">Share links</a>
        <form method="POST" action="{{ route('admin.mangas.destroy', $manga) }}" onsubmit="return confirm('Delete this manga and all pages?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>

    <div class="panel">
        <div class="meta-grid">
            <div>
                <span class="meta-label">Status</span>
                <span class="badge {{ $manga->is_published ? 'badge-ok' : 'badge-muted' }}">
                    {{ $manga->is_published ? 'Published (private)' : 'Draft' }}
                </span>
            </div>
            <div>
                <span class="meta-label">Pages</span>
                <strong>{{ $manga->pages->count() }}</strong>
            </div>
            <div>
                <span class="meta-label">Share links</span>
                <strong>{{ $manga->shareLinks->count() }}</strong>
            </div>
            <div>
                <span class="meta-label">Slug</span>
                <code>{{ $manga->slug }}</code>
            </div>
        </div>
        @if ($manga->description)
            <p class="desc">{{ $manga->description }}</p>
        @endif
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>Latest pages</h2>
            <a href="{{ route('admin.mangas.pages.create', $manga) }}" class="btn btn-primary">Upload pages</a>
        </div>
        @if ($manga->pages->isEmpty())
            <p class="muted">No pages yet. Upload images — one image equals one page.</p>
        @else
            <div class="page-grid">
                @foreach ($manga->pages->take(12) as $page)
                    <a class="page-thumb" href="{{ route('admin.mangas.pages.edit', [$manga, $page]) }}">
                        <img src="{{ route('admin.mangas.pages.image', [$manga, $page]) }}" alt="Page {{ $page->page_number }}">
                        <span>Page {{ $page->page_number }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
