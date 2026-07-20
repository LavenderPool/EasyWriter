@extends('layouts.admin')

@section('title', 'Link stats')
@section('heading', 'Link stats')

@section('content')
    <div class="toolbar">
        <a href="{{ route('admin.mangas.links.index', $manga) }}" class="btn btn-ghost">Back to links</a>
    </div>

    <div class="panel">
        <div class="meta-grid">
            <div>
                <span class="meta-label">Label</span>
                <strong>{{ $link->label ?: 'Untitled link' }}</strong>
            </div>
            <div>
                <span class="meta-label">Views</span>
                <strong>{{ $link->views_count }}</strong>
            </div>
            <div>
                <span class="meta-label">Status</span>
                <span class="badge {{ $link->is_active ? 'badge-ok' : 'badge-muted' }}">
                    {{ $link->is_active ? 'Active' : 'Disabled' }}
                </span>
            </div>
            <div>
                <span class="meta-label">URL</span>
                <code>{{ $link->publicUrl() }}</code>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.mangas.links.update', [$manga, $link]) }}" class="form inline-form" style="margin-top:1.25rem">
            @csrf
            @method('PUT')
            <label>
                <span>Label</span>
                <input type="text" name="label" value="{{ old('label', $link->label) }}">
            </label>
            <label class="checkbox">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ $link->is_active ? 'checked' : '' }}>
                <span>Active</span>
            </label>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>

        <div class="form-actions" style="margin-top:1rem">
            <form method="POST" action="{{ route('admin.mangas.links.regenerate', [$manga, $link]) }}" onsubmit="return confirm('Regenerate token? Old URL will stop working.')">
                @csrf
                <button type="submit" class="btn btn-ghost">Regenerate token</button>
            </form>
            <form method="POST" action="{{ route('admin.mangas.links.destroy', [$manga, $link]) }}" onsubmit="return confirm('Delete this link?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete link</button>
            </form>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>Views by country</h2>
        </div>
        @if ($countryStats->isEmpty())
            <p class="muted">No views yet.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Country</th>
                        <th>Code</th>
                        <th>Views</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($countryStats as $stat)
                        <tr>
                            <td>{{ $stat->country_name ?: 'Unknown' }}</td>
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
            <h2>Recent views</h2>
        </div>
        @if ($recentViews->isEmpty())
            <p class="muted">No views recorded.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>When</th>
                        <th>Country</th>
                        <th>User agent</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentViews as $view)
                        <tr>
                            <td>{{ $view->viewed_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $view->country_name ?: 'Unknown' }} {{ $view->country_code ? '('.$view->country_code.')' : '' }}</td>
                            <td class="ua">{{ \Illuminate\Support\Str::limit($view->user_agent, 80) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
