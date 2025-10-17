@extends('layouts.app')
@section('content')
<h2>Edit Playlist</h2>
<form method="POST" action="{{ route('playlists.update', $playlist) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Playlist Name</label>
        <input type="text" name="name" class="form-control" value="{{ $playlist->name }}" required>
    </div>
    <button type="submit" class="btn btn-netflix">Update</button>
    <a href="{{ route('playlists.index') }}" class="btn btn-outline-light">Cancel</a>
</form>
@endsection
