@extends('layouts.app')
@section('content')
<h2>Edit Video</h2>
<form method="POST" action="{{ route('admin.videos.update', $video) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" value="{{ $video->title }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ $video->description }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select">
            <option value="">None</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $video->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Rating</label>
        <input type="number" name="rating" class="form-control" step="0.1" min="0" max="10" value="{{ $video->rating }}">
    </div>
    <button type="submit" class="btn btn-netflix">Update</button>
    <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-light">Cancel</a>
</form>
@endsection
