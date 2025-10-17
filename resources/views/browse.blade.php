@extends('layouts.app')
@section('title', $pageTitle)
@section('content')

<div class="mb-4">
    <h2 class="text-white">{{ $pageTitle }}</h2>
    @if($category)
        <p class="text-muted">Showing {{ $videos->total() }} {{ $type === 'tv' ? 'series' : 'movies' }}</p>
    @endif
</div>

@if($videos->count() > 0)
    <div class="row">
        @foreach($videos as $video)
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4">
                <div class="card h-100 border-0" style="background: transparent;">
                    <a href="{{ route('videos.show', $video) }}" class="text-decoration-none">
                        <div class="position-relative" style="padding-top: 150%; overflow: hidden; border-radius: 4px;">
                            <img src="{{ $video->poster_url ?: 'https://via.placeholder.com/200x300' }}"
                                 class="position-absolute top-0 start-0 w-100 h-100"
                                 alt="{{ $video->title }}"
                                 style="object-fit: cover; transition: transform 0.3s ease;"
                                 onmouseover="this.style.transform='scale(1.05)'"
                                 onmouseout="this.style.transform='scale(1)'">
                        </div>
                    </a>
                    <div class="card-body p-2">
                        <a href="{{ route('videos.show', $video) }}" class="text-white text-decoration-none">
                            <h6 class="card-title mb-1" style="font-size: 14px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $video->title }}
                            </h6>
                        </a>
                        <div class="d-flex align-items-center justify-content-between">
                            @if($video->rating)
                                <small class="text-warning">★ {{ number_format($video->rating, 1) }}</small>
                            @endif
                            @if($video->release_year)
                                <small class="text-muted">{{ $video->release_year }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination">
                @if ($videos->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">Previous</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $videos->previousPageUrl() }}">Previous</a>
                    </li>
                @endif

                @foreach ($videos->getUrlRange(1, $videos->lastPage()) as $page => $url)
                    @if ($page == $videos->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                @if ($videos->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $videos->nextPageUrl() }}">Next</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">Next</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@else
    <div class="text-center py-5">
        <h4 class="text-muted">No videos found</h4>
        <p class="text-muted">Try browsing a different category or type</p>
        <a href="{{ route('home') }}" class="btn btn-netflix mt-3">Back to Home</a>
    </div>
@endif

<style>
    .pagination {
        --bs-pagination-bg: #181818;
        --bs-pagination-border-color: #404040;
        --bs-pagination-color: #fff;
        --bs-pagination-hover-bg: #2f2f2f;
        --bs-pagination-hover-border-color: #404040;
        --bs-pagination-hover-color: #fff;
        --bs-pagination-focus-bg: #2f2f2f;
        --bs-pagination-active-bg: #e50914;
        --bs-pagination-active-border-color: #e50914;
        --bs-pagination-disabled-bg: #181818;
        --bs-pagination-disabled-border-color: #404040;
    }

    .pagination .page-link {
        background: #181818;
        border: 1px solid #404040;
        color: #fff;
        padding: 0.5rem 0.75rem;
        margin: 0 2px;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .pagination .page-link:hover {
        background: #2f2f2f;
        border-color: #404040;
        color: #fff;
    }

    .pagination .page-item.active .page-link {
        background: #e50914;
        border-color: #e50914;
        color: #fff;
    }

    .pagination .page-item.disabled .page-link {
        background: #181818;
        border-color: #404040;
        color: #666;
    }

    .pagination .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
    }

    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        min-width: auto;
        padding: 0.5rem 1rem;
    }
</style>

@endsection
