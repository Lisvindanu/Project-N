@extends('layouts.app')
@section('content')
<h2>Create Playlist</h2>
<form method="POST" action="{{ route('playlists.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Playlist Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-netflix">Create</button>
    <a href="{{ route('playlists.index') }}" class="btn btn-outline-light">Cancel</a>
</form>
@endsection
