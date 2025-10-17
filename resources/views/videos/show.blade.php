@extends('layouts.app')
@section('title', $video->title)
@section('content')
<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-body p-0">
                @php
                    $embedUrl = $video->type === 'tv'
                        ? "https://www.vidking.net/embed/tv/{$video->tmdb_id}/1/1?color=e50914&autoPlay=true&nextEpisode=true&episodeSelector=true"
                        : "https://www.vidking.net/embed/movie/{$video->tmdb_id}?color=e50914&autoPlay=true";
                @endphp
                <iframe id="videoPlayer" src="{{ $embedUrl }}" width="100%" height="600" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>

        <div class="mt-4">
            <h2>{{ $video->title }}</h2>
            @if($video->rating)
                <span class="text-warning">★ {{ $video->rating }}</span>
            @endif
            @if($video->release_year)
                <span class="text-muted">| {{ $video->release_year }}</span>
            @endif
            @if($video->category)
                <span class="badge bg-secondary">{{ $video->category->name }}</span>
            @endif
            <p class="mt-3">{{ $video->description }}</p>
        </div>

        @auth
        <div class="mt-4">
            <form method="POST" action="{{ route('comments.store', $video) }}">
                @csrf
                <div class="mb-3">
                    <textarea name="content" class="form-control" rows="3" placeholder="Add a comment..." required></textarea>
                </div>
                <button type="submit" class="btn btn-netflix">Post Comment</button>
            </form>
        </div>
        @endauth

        <div class="mt-4">
            <h5 class="text-white">Comments</h5>
            @forelse($video->comments as $comment)
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            @if($comment->user->profile_photo)
                                <img src="{{ asset('storage/' . $comment->user->profile_photo) }}" alt="Profile" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary me-3 d-inline-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px;">
                                    {{ substr($comment->user->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <strong class="text-white">{{ $comment->user->name }}</strong>
                                <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                                <p class="mb-0 mt-2 text-white">{{ $comment->content }}</p>
                                @auth
                                    @if($comment->user_id === auth()->id())
                                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="mt-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this comment?')">Delete</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No comments yet</p>
            @endforelse
        </div>
    </div>

    <div class="col-md-3">
        @if($video->type === 'tv' && $video->seasons->count() > 0)
        <div class="card mb-3 border-0" style="background: #181818;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-white mb-0">Episodes</h5>
                    <select class="form-select form-select-sm" style="width: auto; background: #2f2f2f; color: white; border: 1px solid #404040;" id="seasonSelector">
                        @foreach($video->seasons as $season)
                            <option value="{{ $season->season_number }}" {{ $loop->first ? 'selected' : '' }}>
                                Season {{ $season->season_number }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @foreach($video->seasons as $season)
                    <div class="episodes-container" data-season="{{ $season->season_number }}" style="display: {{ $loop->first ? 'block' : 'none' }};">
                        @foreach($season->episodes as $episode)
                            <div class="episode-item mb-3 p-2 rounded episode-link"
                                 style="cursor: pointer; transition: background 0.2s; border-bottom: 1px solid #404040;"
                                 data-season="{{ $season->season_number }}"
                                 data-episode="{{ $episode->episode_number }}"
                                 data-tmdb-id="{{ $video->tmdb_id }}"
                                 onmouseover="this.style.background='#2f2f2f'"
                                 onmouseout="this.style.background='transparent'">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div style="width: 120px; height: 70px; background: #2f2f2f; border-radius: 4px; overflow: hidden;">
                                            @if($episode->still_path)
                                                <img src="https://image.tmdb.org/t/p/w300{{ $episode->still_path }}"
                                                     class="w-100 h-100"
                                                     style="object-fit: cover;"
                                                     alt="{{ $episode->name }}">
                                            @else
                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white">
                                                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                        <path d="M6.271 5.055a.5.5 0 0 1 .52.038l3.5 2.5a.5.5 0 0 1 0 .814l-3.5 2.5A.5.5 0 0 1 6 10.5v-5a.5.5 0 0 1 .271-.445z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="mb-0 text-white" style="font-size: 14px;">{{ $episode->episode_number }}. {{ $episode->name }}</h6>
                                            @if($episode->runtime)
                                                <small class="text-muted ms-2">{{ $episode->runtime }}m</small>
                                            @endif
                                        </div>
                                        @if($episode->overview)
                                            <p class="mb-0 text-muted small" style="font-size: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                {{ $episode->overview }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        @auth
        <div class="card mb-3">
            <div class="card-body">
                <h6>Add to Playlist</h6>
                @foreach(auth()->user()->playlists as $playlist)
                    <form method="POST" action="{{ route('playlists.add-video', $playlist) }}" class="mb-2">
                        @csrf
                        <input type="hidden" name="video_id" value="{{ $video->id }}">
                        <button type="submit" class="btn btn-sm btn-outline-light w-100">{{ $playlist->name }}</button>
                    </form>
                @endforeach
                <a href="{{ route('playlists.create') }}" class="btn btn-sm btn-netflix w-100 mt-2">Create New Playlist</a>
            </div>
        </div>
        @endauth
    </div>
</div>

@push('scripts')
<script>
// Handle season selector
const seasonSelector = document.getElementById('seasonSelector');
if (seasonSelector) {
    seasonSelector.addEventListener('change', function() {
        const selectedSeason = this.value;

        // Hide all episodes containers
        document.querySelectorAll('.episodes-container').forEach(container => {
            container.style.display = 'none';
        });

        // Show selected season's episodes
        document.querySelector(`.episodes-container[data-season="${selectedSeason}"]`).style.display = 'block';
    });
}

// Handle episode switching
document.querySelectorAll('.episode-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const season = this.dataset.season;
        const episode = this.dataset.episode;
        const tmdbId = this.dataset.tmdbId;

        const newUrl = `https://www.vidking.net/embed/tv/${tmdbId}/${season}/${episode}?color=e50914&autoPlay=true&nextEpisode=true&episodeSelector=true`;
        document.getElementById('videoPlayer').src = newUrl;

        // Remove active class from all episodes
        document.querySelectorAll('.episode-link').forEach(l => {
            l.style.background = 'transparent';
            l.style.border = 'none';
        });

        // Add active state to clicked episode
        this.style.background = '#2f2f2f';
        this.style.borderLeft = '3px solid #e50914';

        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});

@auth
window.addEventListener("message", function (event) {
    if (typeof event.data === "string") {
        try {
            const data = JSON.parse(event.data);
            if (data.type === "PLAYER_EVENT" && data.data) {
                const eventData = data.data;

                // Save progress for timeupdate events
                if (eventData.event === "timeupdate" || eventData.event === "pause") {
                    saveWatchHistory(eventData.currentTime, eventData.duration);
                }
            }
        } catch (e) {
            console.error('Error parsing player event:', e);
        }
    }
});

let saveTimeout;
function saveWatchHistory(progress, duration) {
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => {
        fetch('{{ route('watch-history.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                video_id: {{ $video->id }},
                progress: Math.floor(progress),
                duration: Math.floor(duration)
            })
        });
    }, 5000); // Save every 5 seconds
}
@endauth
</script>
@endpush
@endsection
