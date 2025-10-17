@extends('layouts.app')
@section('title', 'Admin Help - How to Add Movies & Series')
@section('content')

<div class="row">
    <div class="col-lg-10 mx-auto">
        <h2 class="mb-4">📚 Admin Help Guide</h2>

        <!-- Quick Links -->
        <div class="card mb-4" style="background: #181818; border: 1px solid #404040;">
            <div class="card-body">
                <h5 class="text-white">Quick Links</h5>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="#add-movie" class="btn btn-sm btn-outline-light">Add Movie</a>
                    <a href="#add-series" class="btn btn-sm btn-outline-light">Add TV Series</a>
                    <a href="#tmdb-guide" class="btn btn-sm btn-outline-light">Find TMDB ID</a>
                    <a href="{{ route('admin.videos.create') }}" class="btn btn-sm btn-netflix">Add Video Now</a>
                </div>
            </div>
        </div>

        <!-- Section 1: Finding TMDB ID -->
        <div class="card mb-4" style="background: #181818; border: 1px solid #404040;" id="tmdb-guide">
            <div class="card-body">
                <h4 class="text-white mb-3">🔍 Step 1: Find TMDB ID</h4>

                <div class="mb-4">
                    <h6 class="text-warning">What is TMDB?</h6>
                    <p class="text-muted">
                        TMDB (The Movie Database) is a database that contains information about movies and TV series.
                        We use TMDB to automatically get movie details like title, poster, description, and ratings.
                    </p>
                </div>

                <div class="mb-4">
                    <h6 class="text-warning">How to get TMDB ID:</h6>
                    <ol class="text-white">
                        <li class="mb-3">
                            <strong>Go to TMDB website:</strong><br>
                            <a href="https://www.themoviedb.org" target="_blank" class="text-info">https://www.themoviedb.org</a>
                        </li>
                        <li class="mb-3">
                            <strong>Search for your movie or series:</strong><br>
                            <span class="text-muted">Use the search bar at the top. For example, search "Jujutsu Kaisen" or "Interstellar"</span>
                        </li>
                        <li class="mb-3">
                            <strong>Open the movie/series page:</strong><br>
                            <span class="text-muted">Click on the result to open its detail page</span>
                        </li>
                        <li class="mb-3">
                            <strong>Get the ID from URL:</strong><br>
                            <span class="text-muted">Look at the URL in your browser address bar</span><br>
                            <code style="background: #2f2f2f; padding: 8px; display: inline-block; margin-top: 8px; border-radius: 4px;">
                                https://www.themoviedb.org/movie/<span class="text-warning">157336</span>-interstellar
                            </code><br>
                            <span class="text-muted">The number <strong class="text-warning">157336</strong> is the TMDB ID!</span>
                        </li>
                    </ol>
                </div>

                <div class="alert alert-info">
                    <strong>💡 Tip:</strong> Make sure you're on the correct page:
                    <ul class="mb-0 mt-2">
                        <li>Movies: URL starts with <code>/movie/</code></li>
                        <li>TV Series: URL starts with <code>/tv/</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Section 2: Adding a Movie -->
        <div class="card mb-4" style="background: #181818; border: 1px solid #404040;" id="add-movie">
            <div class="card-body">
                <h4 class="text-white mb-3">🎬 Step 2: Add a Movie</h4>

                <ol class="text-white">
                    <li class="mb-3">
                        <strong>Go to Add Video page:</strong><br>
                        <a href="{{ route('admin.videos.create') }}" class="btn btn-sm btn-netflix mt-2">Add Video</a>
                    </li>
                    <li class="mb-3">
                        <strong>Enter the TMDB ID:</strong><br>
                        <span class="text-muted">Paste the ID you got from Step 1 (e.g., 157336 for Interstellar)</span>
                    </li>
                    <li class="mb-3">
                        <strong>Select Type:</strong><br>
                        <span class="text-muted">Choose <span class="badge bg-primary">Movie</span></span>
                    </li>
                    <li class="mb-3">
                        <strong>Select Category (Optional):</strong><br>
                        <span class="text-muted">Choose a category like Action, Drama, Comedy, etc. If unsure, leave it blank - the system will auto-detect from TMDB.</span>
                    </li>
                    <li class="mb-3">
                        <strong>Click "Add Video":</strong><br>
                        <span class="text-muted">The system will automatically fetch all movie details from TMDB!</span>
                    </li>
                </ol>

                <div class="alert alert-success">
                    <strong>✅ Done!</strong> The movie will appear on the homepage and in the Movies section.
                </div>
            </div>
        </div>

        <!-- Section 3: Adding a TV Series -->
        <div class="card mb-4" style="background: #181818; border: 1px solid #404040;" id="add-series">
            <div class="card-body">
                <h4 class="text-white mb-3">📺 Step 3: Add a TV Series</h4>

                <ol class="text-white">
                    <li class="mb-3">
                        <strong>Follow Steps 1-4 from "Add a Movie"</strong><br>
                        <span class="text-muted">But select <span class="badge bg-danger">TV Series</span> instead of Movie</span>
                    </li>
                    <li class="mb-3">
                        <strong>After adding the series, fetch episodes:</strong><br>
                        <ol type="a" class="mt-2">
                            <li class="mb-2">
                                <span class="text-muted">Go to <a href="{{ route('admin.videos.index') }}" class="text-info">Video Management</a></span>
                            </li>
                            <li class="mb-2">
                                <span class="text-muted">Find your TV series in the list</span>
                            </li>
                            <li class="mb-2">
                                <span class="text-muted">Click the <span class="badge bg-info">Episodes</span> button</span>
                            </li>
                            <li class="mb-2">
                                <span class="text-muted">Confirm to fetch episodes (this may take a few minutes)</span>
                            </li>
                        </ol>
                    </li>
                    <li class="mb-3">
                        <strong>Wait for completion:</strong><br>
                        <span class="text-muted">The system will fetch all seasons and episodes from TMDB. You'll see a success message when done.</span>
                    </li>
                </ol>

                <div class="alert alert-success">
                    <strong>✅ Done!</strong> Users can now watch the series with episode selector on the video page!
                </div>
            </div>
        </div>

        <!-- FAQs -->
        <div class="card mb-4" style="background: #181818; border: 1px solid #404040;">
            <div class="card-body">
                <h4 class="text-white mb-3">❓ Frequently Asked Questions</h4>

                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item" style="background: #2f2f2f; border-color: #404040;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" style="background: #2f2f2f; color: white;">
                                What if I enter the wrong TMDB ID?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white" style="background: #2f2f2f;">
                                You can edit or delete the video from the <a href="{{ route('admin.videos.index') }}" class="text-info">Video Management</a> page. Click "Edit" to update details or "Delete" to remove it.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item" style="background: #2f2f2f; border-color: #404040;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" style="background: #2f2f2f; color: white;">
                                Do I need to enter all the movie details manually?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white" style="background: #2f2f2f;">
                                No! Just enter the TMDB ID and select the type. The system will automatically fetch title, description, poster, rating, and all other details from TMDB.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item" style="background: #2f2f2f; border-color: #404040;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" style="background: #2f2f2f; color: white;">
                                Why don't I see episodes for my TV series?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white" style="background: #2f2f2f;">
                                You need to click the <span class="badge bg-info">Episodes</span> button to fetch episodes after adding a TV series. This is a separate step to avoid slowing down the initial add process.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item" style="background: #2f2f2f; border-color: #404040;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" style="background: #2f2f2f; color: white;">
                                How long does it take to fetch episodes?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white" style="background: #2f2f2f;">
                                It depends on the number of seasons. Usually takes 1-3 minutes for most series. Series with many seasons (like Naruto with 20+ seasons) may take longer.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card" style="background: #181818; border: 1px solid #404040;">
            <div class="card-body text-center">
                <h5 class="text-white mb-3">Ready to add content?</h5>
                <a href="{{ route('admin.videos.create') }}" class="btn btn-netflix btn-lg">Add Movie/Series Now</a>
            </div>
        </div>
    </div>
</div>

@endsection
