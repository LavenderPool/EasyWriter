@extends('layouts.admin')

@section('title', __('admin.pages.title', ['title' => $manga->title]))
@section('heading', __('admin.pages.heading', ['title' => $manga->title]))

@section('content')
    <div class="toolbar">
        <a href="{{ route('admin.mangas.show', $manga) }}" class="btn btn-ghost">{{ __('admin.common.back') }}</a>
        <a href="{{ route('admin.mangas.pages.create', $manga) }}" class="btn btn-primary">{{ __('admin.pages.upload') }}</a>
    </div>

    <div class="panel">
        @if ($pages->isEmpty())
            <p class="muted">{{ __('admin.pages.empty') }}</p>
        @else
            <div class="page-grid">
                @foreach ($pages as $page)
                    <div class="page-card">
                        <a href="{{ route('admin.mangas.pages.edit', [$manga, $page]) }}">
                            <img src="{{ route('admin.mangas.pages.image', [$manga, $page]) }}" alt="{{ __('admin.common.page_n', ['number' => $page->page_number]) }}">
                        </a>
                        <div class="page-card-meta">
                            <strong>{{ __('admin.common.page_n', ['number' => $page->page_number]) }}</strong>
                            <div class="actions">
                                <a href="{{ route('admin.mangas.pages.edit', [$manga, $page]) }}">{{ __('admin.common.edit') }}</a>
                                <form method="POST" action="{{ route('admin.mangas.pages.destroy', [$manga, $page]) }}" onsubmit="return confirm(@js(__('admin.pages.confirm_delete')))">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="link-danger">{{ __('admin.common.delete') }}</button>
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
