@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Videos</h2>
    <a href="{{ route('admin.videos.create') }}" class="btn btn-netflix">Add Video</a>
</div>

<table class="table table-dark table-striped">
    <thead>
        <tr>
            <th>Poster</th>
            <th>Title</th>
            <th>Type</th>
            <th>Category</th>
            <th>Rating</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($videos as $video)
            <tr>
                <td><img src="{{ $video->poster_url ?: 'https://via.placeholder.com/50x75' }}" width="50" alt="{{ $video->title }}"></td>
                <td>{{ $video->title }}</td>
                <td><span class="badge bg-secondary">{{ strtoupper($video->type) }}</span></td>
                <td>{{ $video->category->name ?? 'N/A' }}</td>
                <td>{{ $video->rating ?? 'N/A' }}</td>
                <td>
                    <a href="{{ route('admin.videos.edit', $video) }}" class="btn btn-sm btn-outline-light">Edit</a>

                    @if($video->type === 'tv')
                        <form method="POST" action="{{ route('admin.videos.fetch-episodes', $video) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-info" onclick="return confirm('Fetch episodes for {{ $video->title }}? This may take a few minutes.')">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 4px;">
                                    <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                                    <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                                </svg>
                                Episodes
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.videos.destroy', $video) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $videos->links() }}
@endsection
