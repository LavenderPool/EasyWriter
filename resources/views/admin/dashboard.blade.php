@extends('layouts.admin')

@section('title', __('admin.dashboard.title'))
@section('heading', __('admin.dashboard.title'))

@section('content')
    <div class="stats-grid">
        <div class="stat">
            <span class="stat-label">{{ __('admin.dashboard.mangas') }}</span>
            <strong>{{ $mangasCount }}</strong>
        </div>
        <div class="stat">
            <span class="stat-label">{{ __('admin.dashboard.published') }}</span>
            <strong>{{ $publishedCount }}</strong>
        </div>
        <div class="stat">
            <span class="stat-label">{{ __('admin.dashboard.share_links') }}</span>
            <strong>{{ $linksCount }}</strong>
        </div>
        <div class="stat">
            <span class="stat-label">{{ __('admin.dashboard.total_views') }}</span>
            <strong>{{ $viewsCount }}</strong>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>{{ __('admin.dashboard.recent_mangas') }}</h2>
            <a href="{{ route('admin.mangas.create') }}" class="btn btn-primary">{{ __('admin.mangas.new') }}</a>
        </div>

        @if ($recentMangas->isEmpty())
            <p class="muted">{{ __('admin.dashboard.empty') }}</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('admin.common.title') }}</th>
                        <th>{{ __('admin.common.pages') }}</th>
                        <th>{{ __('admin.common.links') }}</th>
                        <th>{{ __('admin.common.status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentMangas as $manga)
                        <tr>
                            <td>{{ $manga->title }}</td>
                            <td>{{ $manga->pages_count }}</td>
                            <td>{{ $manga->share_links_count }}</td>
                            <td>
                                <span class="badge {{ $manga->is_published ? 'badge-ok' : 'badge-muted' }}">
                                    {{ $manga->is_published ? __('admin.common.published') : __('admin.common.draft') }}
                                </span>
                            </td>
                            <td><a href="{{ route('admin.mangas.show', $manga) }}">{{ __('admin.common.open') }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
