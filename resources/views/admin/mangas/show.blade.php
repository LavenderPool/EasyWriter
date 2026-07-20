@extends('layouts.admin')

@section('title', $manga->title)
@section('heading', $manga->title)

@section('content')
    <div class="toolbar">
        <a href="{{ route('admin.mangas.edit', $manga) }}" class="btn btn-ghost">{{ __('admin.common.edit') }}</a>
        <a href="{{ route('admin.mangas.pages.index', $manga) }}" class="btn btn-primary">{{ __('admin.common.pages') }}</a>
        <a href="{{ route('admin.mangas.links.index', $manga) }}" class="btn btn-primary">{{ __('admin.mangas.share_links') }}</a>
        <form method="POST" action="{{ route('admin.mangas.destroy', $manga) }}" onsubmit="return confirm(@js(__('admin.mangas.confirm_delete')))">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">{{ __('admin.common.delete') }}</button>
        </form>
    </div>

    <div class="panel">
        <div class="meta-grid">
            <div>
                <span class="meta-label">{{ __('admin.common.status') }}</span>
                <span class="badge {{ $manga->is_published ? 'badge-ok' : 'badge-muted' }}">
                    {{ $manga->is_published ? __('admin.common.published_private') : __('admin.common.draft') }}
                </span>
            </div>
            <div>
                <span class="meta-label">{{ __('admin.common.pages') }}</span>
                <strong>{{ $manga->pages->count() }}</strong>
            </div>
            <div>
                <span class="meta-label">{{ __('admin.dashboard.share_links') }}</span>
                <strong>{{ $manga->shareLinks->count() }}</strong>
            </div>
            <div>
                <span class="meta-label">{{ __('admin.mangas.slug') }}</span>
                <code>{{ $manga->slug }}</code>
            </div>
        </div>
        @if ($manga->description)
            <p class="desc">{{ $manga->description }}</p>
        @endif
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>{{ __('admin.mangas.latest_pages') }}</h2>
            <a href="{{ route('admin.mangas.pages.create', $manga) }}" class="btn btn-primary">{{ __('admin.mangas.upload_pages') }}</a>
        </div>
        @if ($manga->pages->isEmpty())
            <p class="muted">{{ __('admin.mangas.no_pages') }}</p>
        @else
            <div class="page-grid">
                @foreach ($manga->pages->take(12) as $page)
                    <a class="page-thumb" href="{{ route('admin.mangas.pages.edit', [$manga, $page]) }}">
                        <img src="{{ route('admin.mangas.pages.image', [$manga, $page]) }}" alt="{{ __('admin.common.page_n', ['number' => $page->page_number]) }}">
                        <span>{{ __('admin.common.page_n', ['number' => $page->page_number]) }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
