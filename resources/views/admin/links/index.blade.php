@extends('layouts.admin')

@section('title', 'Share links — '.$manga->title)
@section('heading', 'Share links: '.$manga->title)

@section('content')
    <div class="toolbar">
        <a href="{{ route('admin.mangas.show', $manga) }}" class="btn btn-ghost">Back</a>
    </div>

    <div class="panel narrow">
        <h2>Create link</h2>
        @unless ($manga->is_published)
            <p class="alert alert-error">Publish the manga first to create share links.</p>
        @endunless
        <form method="POST" action="{{ route('admin.mangas.links.store', $manga) }}" class="form inline-form">
            @csrf
            <label>
                <span>Label (e.g. Telegram channel, friends)</span>
                <input type="text" name="label" maxlength="255" placeholder="Optional label" {{ $manga->is_published ? '' : 'disabled' }}>
            </label>
            <button type="submit" class="btn btn-primary" {{ $manga->is_published ? '' : 'disabled' }}>Create link</button>
        </form>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>All links</h2>
        </div>
        @if ($links->isEmpty())
            <p class="muted">No share links yet. Each link can be shared separately and tracks its own views.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>URL</th>
                        <th>Views</th>
                        <th>Status</th>
                        <th>Last viewed</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($links as $link)
                        <tr>
                            <td>{{ $link->label ?: '—' }}</td>
                            <td class="url-cell">
                                <code id="link-{{ $link->id }}">{{ $link->publicUrl() }}</code>
                                <button type="button" class="btn btn-ghost btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('link-{{ $link->id }}').textContent)">Copy</button>
                            </td>
                            <td>{{ $link->views_count }}</td>
                            <td>
                                <span class="badge {{ $link->is_active ? 'badge-ok' : 'badge-muted' }}">
                                    {{ $link->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td>{{ $link->last_viewed_at?->diffForHumans() ?? '—' }}</td>
                            <td class="actions">
                                <a href="{{ route('admin.mangas.links.show', [$manga, $link]) }}">Stats</a>
                                <form method="POST" action="{{ route('admin.mangas.links.destroy', [$manga, $link]) }}" onsubmit="return confirm('Delete this link?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="link-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
