@extends('layouts.admin')

@section('title', 'Mangas')
@section('heading', 'Mangas')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <h2>All mangas</h2>
            <a href="{{ route('admin.mangas.create') }}" class="btn btn-primary">New manga</a>
        </div>

        @if ($mangas->isEmpty())
            <p class="muted">No mangas yet.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Pages</th>
                        <th>Links</th>
                        <th>Status</th>
                        <th>Updated</th>
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
                                    {{ $manga->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td>{{ $manga->updated_at->diffForHumans() }}</td>
                            <td class="actions">
                                <a href="{{ route('admin.mangas.show', $manga) }}">Open</a>
                                <a href="{{ route('admin.mangas.edit', $manga) }}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $mangas->links() }}
        @endif
    </div>
@endsection
