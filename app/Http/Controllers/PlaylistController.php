<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $playlists = auth()->user()->playlists()->withCount('videos')->get();
        return view('playlists.index', compact('playlists'));
    }

    public function create()
    {
        return view('playlists.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        auth()->user()->playlists()->create($validated);

        return redirect()->route('playlists.index')
            ->with('success', 'Playlist created successfully.');
    }

    public function show(Playlist $playlist)
    {
        $this->authorize('view', $playlist);
        $playlist->load('videos');
        return view('playlists.show', compact('playlist'));
    }

    public function edit(Playlist $playlist)
    {
        $this->authorize('update', $playlist);
        return view('playlists.edit', compact('playlist'));
    }

    public function update(Request $request, Playlist $playlist)
    {
        $this->authorize('update', $playlist);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $playlist->update($validated);

        return redirect()->route('playlists.index')
            ->with('success', 'Playlist updated successfully.');
    }

    public function destroy(Playlist $playlist)
    {
        $this->authorize('delete', $playlist);
        $playlist->delete();

        return redirect()->route('playlists.index')
            ->with('success', 'Playlist deleted successfully.');
    }

    public function addVideo(Request $request, Playlist $playlist)
    {
        $this->authorize('update', $playlist);

        $request->validate([
            'video_id' => 'required|exists:videos,id',
        ]);

        if (!$playlist->videos()->where('video_id', $request->video_id)->exists()) {
            $playlist->videos()->attach($request->video_id);
        }

        return back()->with('success', 'Video added to playlist.');
    }

    public function removeVideo(Playlist $playlist, Video $video)
    {
        $this->authorize('update', $playlist);
        $playlist->videos()->detach($video->id);

        return back()->with('success', 'Video removed from playlist.');
    }
}
