<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use App\Models\Video;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_videos' => Video::count(),
            'total_categories' => Category::count(),
            'total_comments' => Comment::count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_videos' => Video::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', $stats);
    }
}
