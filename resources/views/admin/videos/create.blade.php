@extends('layouts.app')
@section('content')
<h2>Add Video</h2>
<form method="POST" action="{{ route('admin.videos.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">TMDB ID</label>
        <input type="text" name="tmdb_id" class="form-control" placeholder="e.g., 1078605" required>
        <small class="text-muted">Get TMDB ID from themoviedb.org</small>
    </div>
    <div class="mb-3">
        <label class="form-label">Type</label>
        <select name="type" class="form-select" required>
            <option value="movie">Movie</option>
            <option value="tv">TV Series</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select">
            <option value="">None</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-netflix">Add Video</button>
    <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-light">Cancel</a>
</form>
@endsection
