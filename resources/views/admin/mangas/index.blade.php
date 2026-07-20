@extends('layouts.admin')

@section('title', __('admin.mangas.title'))
@section('heading', __('admin.mangas.title'))

@section('content')
    <div class="panel">
        <div class="panel-head">
            <h2>{{ __('admin.mangas.all') }}</h2>
            <a href="{{ route('admin.mangas.create') }}" class="btn btn-primary">{{ __('admin.mangas.new') }}</a>
        </div>

        @if ($mangas->isEmpty())
            <p class="muted">{{ __('admin.mangas.empty') }}</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('admin.common.title') }}</th>
                        <th>{{ __('admin.common.pages') }}</th>
                        <th>{{ __('admin.common.links') }}</th>
                        <th>{{ __('admin.common.status') }}</th>
                        <th>{{ __('admin.common.updated') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mangas as $manga)
                        <tr>
                            <td>{{ $manga->title }}</td>
                            <td>{{ $manga->pages_count }}</td>
                            <td>{{ $manga->share_links_count }}</td>
                            <td>
                                <span class="badge {{ $manga->is_published ? 'badge-ok' : 'badge-muted' }}">
                                    {{ $manga->is_published ? __('admin.common.published') : __('admin.common.draft') }}
                                </span>
                            </td>
                            <td>{{ $manga->updated_at->diffForHumans() }}</td>
                            <td class="actions">
                                <a href="{{ route('admin.mangas.show', $manga) }}">{{ __('admin.common.open') }}</a>
                                <a href="{{ route('admin.mangas.edit', $manga) }}">{{ __('admin.common.edit') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $mangas->links() }}
        @endif
    </div>
@endsection
