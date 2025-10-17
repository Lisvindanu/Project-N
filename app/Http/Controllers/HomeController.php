<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Video;
use App\Models\WatchHistory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with('videos')->get();
        $continueWatching = [];

        if (auth()->check()) {
            $continueWatching = WatchHistory::where('user_id', auth()->id())
                ->with('video')
                ->where('progress', '>', 0)
                ->latest()
                ->limit(6)
                ->get();
        }

        return view('home', compact('categories', 'continueWatching'));
    }

    public function show(Video $video)
    {
        $video->load(['category', 'comments.user', 'seasons.episodes']);
        return view('videos.show', compact('video'));
    }

    public function browse(Request $request)
    {
        $type = $request->get('type');
        $categoryId = $request->get('category');

        $query = Video::with('category');

        if ($type) {
            $query->where('type', $type);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $videos = $query->orderBy('created_at', 'desc')->paginate(24);

        // Maintain query parameters in pagination
        $videos->appends($request->only(['type', 'category']));

        $category = $categoryId ? Category::find($categoryId) : null;
        $pageTitle = $this->getPageTitle($type, $category);

        return view('browse', compact('videos', 'type', 'category', 'pageTitle'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return redirect()->route('home');
        }

        $videos = Video::with('category')
            ->where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orderBy('rating', 'desc')
            ->paginate(24);

        $videos->appends(['q' => $query]);

        return view('search', compact('videos', 'query'));
    }

    private function getPageTitle($type, $category)
    {
        if ($category) {
            return ($type === 'tv' ? 'TV Series - ' : 'Movies - ') . $category->name;
        }

        if ($type === 'tv') {
            return 'All TV Series';
        }

        if ($type === 'movie') {
            return 'All Movies';
        }

        return 'Browse';
    }
}
