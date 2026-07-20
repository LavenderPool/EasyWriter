@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
    <div class="stats-grid">
        <div class="stat">
            <span class="stat-label">Mangas</span>
            <strong>{{ $mangasCount }}</strong>
        </div>
        <div class="stat">
            <span class="stat-label">Published</span>
            <strong>{{ $publishedCount }}</strong>
        </div>
        <div class="stat">
            <span class="stat-label">Share links</span>
            <strong>{{ $linksCount }}</strong>
        </div>
        <div class="stat">
            <span class="stat-label">Total views</span>
            <strong>{{ $viewsCount }}</strong>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>Recent mangas</h2>
            <a href="{{ route('admin.mangas.create') }}" class="btn btn-primary">New manga</a>
        </div>

        @if ($recentMangas->isEmpty())
            <p class="muted">No mangas yet. Create your first private book.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Pages</th>
                        <th>Links</th>
                        <th>Status</th>
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
                                    {{ $manga->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td><a href="{{ route('admin.mangas.show', $manga) }}">Open</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
