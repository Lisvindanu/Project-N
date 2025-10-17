@extends('layouts.app')
@section('content')
@if(auth()->check() && $continueWatching->count() > 0)
    <h4 class="mb-3">Continue Watching</h4>
    <div class="row mb-5">
        @foreach($continueWatching as $history)
            <div class="col-md-2 mb-3">
                <div class="card">
                    <a href="{{ route('videos.show', $history->video) }}">
                        <img src="{{ $history->video->poster_url ?: 'https://via.placeholder.com/200x300' }}" class="card-img-top" alt="{{ $history->video->title }}" style="cursor: pointer;">
                    </a>
                    <div class="card-body p-2">
                        <small class="card-title d-block text-truncate"><a href="{{ route('videos.show', $history->video) }}" class="text-white text-decoration-none">{{ $history->video->title }}</a></small>
                        <div class="progress" style="height: 3px;">
                            <div class="progress-bar bg-danger" style="width: {{ ($history->progress / $history->duration) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@foreach($categories as $category)
    @if($category->videos->count() > 0)
        <h4 class="mb-3">{{ $category->name }}</h4>
        <div class="row mb-4">
            @foreach($category->videos as $video)
                <div class="col-md-2 mb-3">
                    <div class="card">
                        <a href="{{ route('videos.show', $video) }}">
                            <img src="{{ $video->poster_url ?: 'https://via.placeholder.com/200x300' }}" class="card-img-top" alt="{{ $video->title }}" style="cursor: pointer;">
                        </a>
                        <div class="card-body p-2">
                            <small class="card-title d-block text-truncate"><a href="{{ route('videos.show', $video) }}" class="text-white text-decoration-none">{{ $video->title }}</a></small>
                            @if($video->rating)
                                <small class="text-warning">★ {{ $video->rating }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endforeach

@if($categories->sum(fn($cat) => $cat->videos->count()) == 0)
    <div class="text-center py-5">
        <h3>No videos available yet</h3>
        @if(auth()->check() && auth()->user()->isAdmin())
            <a href="{{ route('admin.videos.create') }}" class="btn btn-netflix mt-3">Add Videos</a>
        @endif
    </div>
@endif
@endsection
