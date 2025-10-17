@extends('layouts.app')
@section('title', 'My Playlists')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>My Playlists</h2>
    <a href="{{ route('playlists.create') }}" class="btn btn-netflix">Create Playlist</a>
</div>

<div class="row">
    @forelse($playlists as $playlist)
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5><a href="{{ route('playlists.show', $playlist) }}" class="text-white text-decoration-none">{{ $playlist->name }}</a></h5>
                    <p class="text-muted">{{ $playlist->videos_count }} videos</p>
                    <div class="btn-group">
                        <a href="{{ route('playlists.show', $playlist) }}" class="btn btn-sm btn-netflix">View</a>
                        <a href="{{ route('playlists.edit', $playlist) }}" class="btn btn-sm btn-outline-light">Edit</a>
                        <form method="POST" action="{{ route('playlists.destroy', $playlist) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete playlist?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center">No playlists yet. Create one to organize your videos!</p>
    @endforelse
</div>
@endsection
