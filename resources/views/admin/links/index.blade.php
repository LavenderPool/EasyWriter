@extends('layouts.admin')

@section('title', __('admin.links.title', ['title' => $manga->title]))
@section('heading', __('admin.links.heading', ['title' => $manga->title]))

@section('content')
    <div class="toolbar">
        <a href="{{ route('admin.mangas.show', $manga) }}" class="btn btn-ghost">{{ __('admin.common.back') }}</a>
    </div>

    <div class="panel narrow">
        <h2>{{ __('admin.links.create') }}</h2>
        @unless ($manga->is_published)
            <p class="alert alert-error">{{ __('admin.links.publish_first') }}</p>
        @endunless
        <form method="POST" action="{{ route('admin.mangas.links.store', $manga) }}" class="form inline-form">
            @csrf
            <label>
                <span>{{ __('admin.links.label') }}</span>
                <input type="text" name="label" maxlength="255" placeholder="{{ __('admin.links.label_placeholder') }}" {{ $manga->is_published ? '' : 'disabled' }}>
            </label>
            <button type="submit" class="btn btn-primary" {{ $manga->is_published ? '' : 'disabled' }}>{{ __('admin.links.create_button') }}</button>
        </form>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>{{ __('admin.links.all') }}</h2>
        </div>
        @if ($links->isEmpty())
            <p class="muted">{{ __('admin.links.empty') }}</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('admin.links.label_short') }}</th>
                        <th>{{ __('admin.links.url') }}</th>
                        <th>{{ __('admin.links.views') }}</th>
                        <th>{{ __('admin.common.status') }}</th>
                        <th>{{ __('admin.links.last_viewed') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($links as $link)
                        <tr>
                            <td>{{ $link->label ?: '—' }}</td>
                            <td class="url-cell">
                                <code id="link-{{ $link->id }}">{{ $link->publicUrl() }}</code>
                                <button type="button" class="btn btn-ghost btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('link-{{ $link->id }}').textContent)">{{ __('admin.common.copy') }}</button>
                            </td>
                            <td>{{ $link->views_count }}</td>
                            <td>
                                <span class="badge {{ $link->is_active ? 'badge-ok' : 'badge-muted' }}">
                                    {{ $link->is_active ? __('admin.common.active') : __('admin.common.disabled') }}
                                </span>
                            </td>
                            <td>{{ $link->last_viewed_at?->diffForHumans() ?? '—' }}</td>
                            <td class="actions">
                                <a href="{{ route('admin.mangas.links.show', [$manga, $link]) }}">{{ __('admin.links.stats') }}</a>
                                <form method="POST" action="{{ route('admin.mangas.links.destroy', [$manga, $link]) }}" onsubmit="return confirm(@js(__('admin.links.confirm_delete')))">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="link-danger">{{ __('admin.common.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
