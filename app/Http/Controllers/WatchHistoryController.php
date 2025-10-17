<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\WatchHistory;
use Illuminate\Http\Request;

class WatchHistoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'video_id' => 'required|exists:videos,id',
            'progress' => 'required|integer|min:0',
            'duration' => 'nullable|integer|min:0',
        ]);

        WatchHistory::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'video_id' => $validated['video_id'],
            ],
            [
                'progress' => $validated['progress'],
                'duration' => $validated['duration'] ?? null,
            ]
        );

        return response()->json(['success' => true]);
    }
}
