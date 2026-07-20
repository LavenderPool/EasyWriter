@extends('layouts.admin')

@section('title', __('admin.links.stats_title'))
@section('heading', __('admin.links.stats_title'))

@section('content')
    <div class="toolbar">
        <a href="{{ route('admin.mangas.links.index', $manga) }}" class="btn btn-ghost">{{ __('admin.links.back_to_links') }}</a>
    </div>

    <div class="panel">
        <div class="meta-grid">
            <div>
                <span class="meta-label">{{ __('admin.links.label_short') }}</span>
                <strong>{{ $link->label ?: __('admin.links.untitled') }}</strong>
            </div>
            <div>
                <span class="meta-label">{{ __('admin.links.views') }}</span>
                <strong>{{ $link->views_count }}</strong>
            </div>
            <div>
                <span class="meta-label">{{ __('admin.common.status') }}</span>
                <span class="badge {{ $link->is_active ? 'badge-ok' : 'badge-muted' }}">
                    {{ $link->is_active ? __('admin.common.active') : __('admin.common.disabled') }}
                </span>
            </div>
            <div>
                <span class="meta-label">{{ __('admin.links.url') }}</span>
                <code>{{ $link->publicUrl() }}</code>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.mangas.links.update', [$manga, $link]) }}" class="form inline-form" style="margin-top:1.25rem">
            @csrf
            @method('PUT')
            <label>
                <span>{{ __('admin.links.label_short') }}</span>
                <input type="text" name="label" value="{{ old('label', $link->label) }}">
            </label>
            <label class="checkbox">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ $link->is_active ? 'checked' : '' }}>
                <span>{{ __('admin.common.active') }}</span>
            </label>
            <button type="submit" class="btn btn-primary">{{ __('admin.common.save') }}</button>
        </form>

        <div class="form-actions" style="margin-top:1rem">
            <form method="POST" action="{{ route('admin.mangas.links.regenerate', [$manga, $link]) }}" onsubmit="return confirm(@js(__('admin.links.confirm_regenerate')))">
                @csrf
                <button type="submit" class="btn btn-ghost">{{ __('admin.links.regenerate') }}</button>
            </form>
            <form method="POST" action="{{ route('admin.mangas.links.destroy', [$manga, $link]) }}" onsubmit="return confirm(@js(__('admin.links.confirm_delete')))">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">{{ __('admin.links.delete_link') }}</button>
            </form>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>{{ __('admin.links.by_country') }}</h2>
        </div>
        @if ($countryStats->isEmpty())
            <p class="muted">{{ __('admin.links.no_views') }}</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('admin.links.country') }}</th>
                        <th>{{ __('admin.links.code') }}</th>
                        <th>{{ __('admin.links.views') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($countryStats as $stat)
                        <tr>
                            <td>{{ $stat->country_name ?: __('admin.common.unknown') }}</td>
                            <td>{{ $stat->country_code ?: '—' }}</td>
                            <td>{{ $stat->views }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>{{ __('admin.links.recent_views') }}</h2>
        </div>
        @if ($recentViews->isEmpty())
            <p class="muted">{{ __('admin.links.no_views_recorded') }}</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('admin.links.when') }}</th>
                        <th>{{ __('admin.links.country') }}</th>
                        <th>{{ __('admin.links.user_agent') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentViews as $view)
                        <tr>
                            <td>{{ $view->viewed_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $view->country_name ?: __('admin.common.unknown') }} {{ $view->country_code ? '('.$view->country_code.')' : '' }}</td>
                            <td class="ua">{{ \Illuminate\Support\Str::limit($view->user_agent, 80) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
