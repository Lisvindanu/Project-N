<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with('category')->latest()->paginate(20);
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.videos.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tmdb_id' => 'required|string',
            'type' => 'required|in:movie,tv',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        // Fetch metadata from TMDB API
        $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));
        $type = $validated['type'];
        $tmdbId = $validated['tmdb_id'];

        try {
            $response = Http::get("https://api.themoviedb.org/3/{$type}/{$tmdbId}", [
                'api_key' => $apiKey,
                'language' => 'en-US'
            ]);

            if ($response->successful()) {
                $data = $response->json();

                Video::create([
                    'tmdb_id' => $tmdbId,
                    'type' => $type,
                    'title' => $data['title'] ?? $data['name'] ?? 'Unknown',
                    'description' => $data['overview'] ?? null,
                    'poster_url' => $data['poster_path'] ? 'https://image.tmdb.org/t/p/w500' . $data['poster_path'] : null,
                    'backdrop_url' => $data['backdrop_path'] ? 'https://image.tmdb.org/t/p/original' . $data['backdrop_path'] : null,
                    'release_year' => isset($data['release_date']) ? date('Y', strtotime($data['release_date'])) : (isset($data['first_air_date']) ? date('Y', strtotime($data['first_air_date'])) : null),
                    'rating' => $data['vote_average'] ?? null,
                    'category_id' => $validated['category_id'],
                ]);

                return redirect()->route('admin.videos.index')
                    ->with('success', 'Video added successfully.');
            } else {
                // Log the actual error from TMDB
                \Log::error('TMDB API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return back()->withErrors(['error' => 'TMDB API returned error: ' . $response->status() . ' - ' . $response->body()]);
            }
        } catch (\Exception $e) {
            \Log::error('TMDB Exception', ['message' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Failed to fetch video data from TMDB: ' . $e->getMessage()]);
        }
    }

    public function edit(Video $video)
    {
        $categories = Category::all();
        return view('admin.videos.edit', compact('video', 'categories'));
    }

    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'rating' => 'nullable|numeric|min:0|max:10',
        ]);

        $video->update($validated);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video updated successfully.');
    }

    public function destroy(Video $video)
    {
        $video->delete();

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video deleted successfully.');
    }

    public function fetchEpisodes(Video $video)
    {
        if ($video->type !== 'tv') {
            return back()->withErrors(['error' => 'Only TV series can fetch episodes.']);
        }

        try {
            \Artisan::call('tmdb:fetch-seasons', [
                '--video_id' => $video->id,
                '--lang' => 'en-US'
            ]);

            return back()->with('success', 'Episodes fetched successfully for ' . $video->title);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to fetch episodes: ' . $e->getMessage()]);
        }
    }
}
