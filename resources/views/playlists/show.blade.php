@extends('layouts.app')
@section('content')
<h2>{{ $playlist->name }}</h2>
<div class="row">
    @forelse($playlist->videos as $video)
        <div class="col-md-2 mb-3">
            <div class="card">
                <img src="{{ $video->poster_url ?: 'https://via.placeholder.com/200x300' }}" class="card-img-top" alt="{{ $video->title }}">
                <div class="card-body p-2">
                    <small class="d-block text-truncate"><a href="{{ route('videos.show', $video) }}" class="text-white text-decoration-none">{{ $video->title }}</a></small>
                    <form method="POST" action="{{ route('playlists.remove-video', [$playlist, $video]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger w-100 mt-2">Remove</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center">No videos in this playlist yet</p>
    @endforelse
</div>
@endsection
