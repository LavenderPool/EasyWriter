@extends('layouts.admin')

@section('title', 'Pages — '.$manga->title)
@section('heading', 'Pages: '.$manga->title)

@section('content')
    <div class="toolbar">
        <a href="{{ route('admin.mangas.show', $manga) }}" class="btn btn-ghost">Back</a>
        <a href="{{ route('admin.mangas.pages.create', $manga) }}" class="btn btn-primary">Upload pages</a>
    </div>

    <div class="panel">
        @if ($pages->isEmpty())
            <p class="muted">No pages yet.</p>
        @else
            <div class="page-grid">
                @foreach ($pages as $page)
                    <div class="page-card">
                        <a href="{{ route('admin.mangas.pages.edit', [$manga, $page]) }}">
                            <img src="{{ route('admin.mangas.pages.image', [$manga, $page]) }}" alt="Page {{ $page->page_number }}">
                        </a>
                        <div class="page-card-meta">
                            <strong>Page {{ $page->page_number }}</strong>
                            <div class="actions">
                                <a href="{{ route('admin.mangas.pages.edit', [$manga, $page]) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.mangas.pages.destroy', [$manga, $page]) }}" onsubmit="return confirm('Delete this page?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="link-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $pages->links() }}
        @endif
    </div>
@endsection
