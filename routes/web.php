<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\WatchHistoryController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/browse', [HomeController::class, 'browse'])->name('browse');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/videos/{video}', [HomeController::class, 'show'])->name('videos.show');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Playlists
    Route::resource('playlists', PlaylistController::class);
    Route::post('/playlists/{playlist}/add-video', [PlaylistController::class, 'addVideo'])->name('playlists.add-video');
    Route::delete('/playlists/{playlist}/remove-video/{video}', [PlaylistController::class, 'removeVideo'])->name('playlists.remove-video');

    // Comments
    Route::post('/videos/{video}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Watch history
    Route::post('/watch-history', [WatchHistoryController::class, 'store'])->name('watch-history.store');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/help', function () {
        return view('admin.help');
    })->name('help');
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('videos', VideoController::class);
    Route::post('videos/{video}/fetch-episodes', [VideoController::class, 'fetchEpisodes'])->name('videos.fetch-episodes');
    Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy']);
});
