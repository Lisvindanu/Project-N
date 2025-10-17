<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ProjectN - Netflix Wannabe')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #141414; color: #fff; }
        .navbar { background: #000 !important; }
        .card { background: #181818; border: 1px solid #333; }
        .btn-netflix { background: #e50914; border: none; }
        .btn-netflix:hover { background: #f40612; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand text-danger fw-bold" href="{{ route('home') }}">ProjectN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Movies
                        </a>
                        <ul class="dropdown-menu" style="background: #181818; border-color: #404040;">
                            <li><a class="dropdown-item text-white" href="{{ route('browse', ['type' => 'movie']) }}" style="background: transparent;" onmouseover="this.style.background='#2f2f2f'" onmouseout="this.style.background='transparent'">All Movies</a></li>
                            <li><hr class="dropdown-divider" style="border-color: #404040;"></li>
                            @php
                                $categories = \App\Models\Category::has('videos')->orderBy('name')->get();
                            @endphp
                            @foreach($categories as $category)
                                <li><a class="dropdown-item text-white" href="{{ route('browse', ['type' => 'movie', 'category' => $category->id]) }}" style="background: transparent;" onmouseover="this.style.background='#2f2f2f'" onmouseout="this.style.background='transparent'">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            TV Series
                        </a>
                        <ul class="dropdown-menu" style="background: #181818; border-color: #404040;">
                            <li><a class="dropdown-item text-white" href="{{ route('browse', ['type' => 'tv']) }}" style="background: transparent;" onmouseover="this.style.background='#2f2f2f'" onmouseout="this.style.background='transparent'">All Series</a></li>
                            <li><hr class="dropdown-divider" style="border-color: #404040;"></li>
                            @foreach($categories as $category)
                                <li><a class="dropdown-item text-white" href="{{ route('browse', ['type' => 'tv', 'category' => $category->id]) }}" style="background: transparent;" onmouseover="this.style.background='#2f2f2f'" onmouseout="this.style.background='transparent'">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>

                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('playlists.index') }}">My Playlists</a></li>
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Admin</a>
                                <ul class="dropdown-menu" style="background: #181818; border-color: #404040;">
                                    <li><a class="dropdown-item text-white" href="{{ route('admin.dashboard') }}" style="background: transparent;" onmouseover="this.style.background='#2f2f2f'" onmouseout="this.style.background='transparent'">Dashboard</a></li>
                                    <li><hr class="dropdown-divider" style="border-color: #404040;"></li>
                                    <li><a class="dropdown-item text-white" href="{{ route('admin.categories.index') }}" style="background: transparent;" onmouseover="this.style.background='#2f2f2f'" onmouseout="this.style.background='transparent'">Categories</a></li>
                                    <li><a class="dropdown-item text-white" href="{{ route('admin.videos.index') }}" style="background: transparent;" onmouseover="this.style.background='#2f2f2f'" onmouseout="this.style.background='transparent'">Videos</a></li>
                                    <li><a class="dropdown-item text-white" href="{{ route('admin.users.index') }}" style="background: transparent;" onmouseover="this.style.background='#2f2f2f'" onmouseout="this.style.background='transparent'">Users</a></li>
                                    <li><hr class="dropdown-divider" style="border-color: #404040;"></li>
                                    <li><a class="dropdown-item text-warning" href="{{ route('admin.help') }}" style="background: transparent;" onmouseover="this.style.background='#2f2f2f'" onmouseout="this.style.background='transparent'">Help Guide</a></li>
                                </ul>
                            </li>
                        @endif
                    @endauth
                </ul>

                <form class="d-flex mx-3" action="{{ route('search') }}" method="GET">
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text"
                               class="form-control"
                               name="q"
                               placeholder="Search movies, series..."
                               value="{{ request('q') }}"
                               style="background: #2f2f2f; border: 1px solid #404040; color: white;"
                               onfocus="this.style.borderColor='#e50914'"
                               onblur="this.style.borderColor='#404040'">
                        <button class="btn btn-netflix" type="submit">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                        </button>
                    </div>
                </form>

                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                @if(auth()->user()->profile_photo)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary me-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 14px;">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                @endif
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Edit Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
