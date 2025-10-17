<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ScrapeTmdbContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmdb:scrape {--type=all : Type of content to scrape (anime|movies|kdrama|all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape trending movies, anime series, and K-Drama from TMDB';

    private $apiKey;
    private $baseUrl;
    private $imageBaseUrl;

    public function __construct()
    {
        parent::__construct();
        $this->apiKey = config('services.tmdb.api_key');
        $this->baseUrl = config('services.tmdb.base_url');
        $this->imageBaseUrl = config('services.tmdb.image_base_url');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');

        $this->info('🎬 Starting TMDB content scraping...');

        if ($type === 'all' || $type === 'movies') {
            $this->scrapePopularMovies();
        }

        if ($type === 'all' || $type === 'anime') {
            $this->scrapeAnime();
        }

        if ($type === 'all' || $type === 'kdrama') {
            $this->scrapeKDrama();
        }

        $this->info('✅ Scraping completed!');
    }

    private function scrapePopularMovies()
    {
        $this->info('📽️  Scraping popular movies...');

        $response = Http::get("{$this->baseUrl}/movie/popular", [
            'api_key' => $this->apiKey,
            'language' => 'en-US',
            'page' => 1
        ]);

        if ($response->successful()) {
            $movies = $response->json()['results'];
            $this->processMovies($movies);
            $count = count($movies);
            $this->info("✓ Scraped {$count} popular movies");
        } else {
            $this->error('Failed to fetch popular movies');
        }
    }

    private function scrapeAnime()
    {
        $this->info('🎌 Scraping anime series...');

        // Anime biasanya punya genre Animation (ID: 16) dan origin country JP
        $response = Http::get("{$this->baseUrl}/discover/tv", [
            'api_key' => $this->apiKey,
            'language' => 'en-US',
            'sort_by' => 'popularity.desc',
            'with_genres' => '16', // Animation
            'with_origin_country' => 'JP', // Japan
            'page' => 1
        ]);

        if ($response->successful()) {
            $series = $response->json()['results'];
            $this->processTVSeries($series, 'anime');
            $count = count($series);
            $this->info("✓ Scraped {$count} anime series");
        } else {
            $this->error('Failed to fetch anime series');
        }
    }

    private function scrapeKDrama()
    {
        $this->info('🇰🇷 Scraping K-Drama...');

        // K-Drama punya origin country KR
        $response = Http::get("{$this->baseUrl}/discover/tv", [
            'api_key' => $this->apiKey,
            'language' => 'en-US',
            'sort_by' => 'popularity.desc',
            'with_origin_country' => 'KR', // Korea
            'page' => 1
        ]);

        if ($response->successful()) {
            $series = $response->json()['results'];
            $this->processTVSeries($series, 'kdrama');
            $count = count($series);
            $this->info("✓ Scraped {$count} K-Drama series");
        } else {
            $this->error('Failed to fetch K-Drama');
        }
    }

    private function processMovies($movies)
    {
        foreach ($movies as $movie) {
            // Get detailed info untuk genre
            $details = Http::get("{$this->baseUrl}/movie/{$movie['id']}", [
                'api_key' => $this->apiKey,
            ])->json();

            // Ambil genre pertama sebagai category
            $categoryName = $details['genres'][0]['name'] ?? 'Drama';
            $category = Category::firstOrCreate(['name' => $categoryName]);

            Video::updateOrCreate(
                ['tmdb_id' => $movie['id'], 'type' => 'movie'],
                [
                    'title' => $movie['title'],
                    'description' => $movie['overview'],
                    'poster_url' => $movie['poster_path'] ? "{$this->imageBaseUrl}/w500{$movie['poster_path']}" : null,
                    'backdrop_url' => $movie['backdrop_path'] ? "{$this->imageBaseUrl}/original{$movie['backdrop_path']}" : null,
                    'release_year' => $movie['release_date'] ? date('Y', strtotime($movie['release_date'])) : null,
                    'rating' => $movie['vote_average'],
                    'category_id' => $category->id,
                ]
            );

            $this->line("  • {$movie['title']}");
        }
    }

    private function processTVSeries($series, $tag = '')
    {
        foreach ($series as $show) {
            // Get detailed info untuk genre
            $details = Http::get("{$this->baseUrl}/tv/{$show['id']}", [
                'api_key' => $this->apiKey,
            ])->json();

            // Ambil genre pertama sebagai category
            $categoryName = $details['genres'][0]['name'] ?? 'Drama';
            $category = Category::firstOrCreate(['name' => $categoryName]);

            Video::updateOrCreate(
                ['tmdb_id' => $show['id'], 'type' => 'tv'],
                [
                    'title' => $show['name'],
                    'description' => $show['overview'],
                    'poster_url' => $show['poster_path'] ? "{$this->imageBaseUrl}/w500{$show['poster_path']}" : null,
                    'backdrop_url' => $show['backdrop_path'] ? "{$this->imageBaseUrl}/original{$show['backdrop_path']}" : null,
                    'release_year' => $show['first_air_date'] ? date('Y', strtotime($show['first_air_date'])) : null,
                    'rating' => $show['vote_average'],
                    'category_id' => $category->id,
                ]
            );

            $this->line("  • {$show['name']}");
        }
    }
}
