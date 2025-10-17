<?php

namespace App\Console\Commands;

use App\Models\Episode;
use App\Models\Season;
use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchTvSeasons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmdb:fetch-seasons {--video_id= : Specific video ID to fetch} {--lang=ja-JP : Language for episode data (ja-JP for Japanese, en-US for English)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch seasons and episodes data from TMDB for TV series';

    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        parent::__construct();
        $this->apiKey = config('services.tmdb.api_key');
        $this->baseUrl = config('services.tmdb.base_url');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $videoId = $this->option('video_id');

        if ($videoId) {
            $videos = Video::where('id', $videoId)->where('type', 'tv')->get();
        } else {
            $videos = Video::where('type', 'tv')->get();
        }

        if ($videos->isEmpty()) {
            $this->error('No TV series found');
            return;
        }

        $this->info("Found {$videos->count()} TV series to process...");

        foreach ($videos as $video) {
            $this->info("Processing: {$video->title}");
            $this->fetchSeasons($video);
        }

        $this->info('✅ Completed!');
    }

    private function fetchSeasons(Video $video)
    {
        $language = $this->option('lang') ?? 'ja-JP';

        // Get TV show details
        $response = Http::get("{$this->baseUrl}/tv/{$video->tmdb_id}", [
            'api_key' => $this->apiKey,
            'language' => $language,
        ]);

        if (!$response->successful()) {
            $this->error("Failed to fetch details for {$video->title}");
            return;
        }

        $tvShow = $response->json();

        if (empty($tvShow['seasons'])) {
            $this->warn("No seasons found for {$video->title}");
            return;
        }

        foreach ($tvShow['seasons'] as $seasonData) {
            // Skip season 0 (specials)
            if ($seasonData['season_number'] == 0) {
                continue;
            }

            $season = Season::updateOrCreate(
                [
                    'video_id' => $video->id,
                    'season_number' => $seasonData['season_number']
                ],
                [
                    'name' => $seasonData['name'],
                    'overview' => $seasonData['overview'],
                    'episode_count' => $seasonData['episode_count'],
                    'poster_path' => $seasonData['poster_path'],
                    'air_date' => $seasonData['air_date'] ?? null,
                ]
            );

            $this->line("  • Season {$seasonData['season_number']}");

            // Fetch episodes for this season
            $this->fetchEpisodes($video->tmdb_id, $season);

            // Small delay to avoid rate limiting
            usleep(250000); // 250ms
        }
    }

    private function fetchEpisodes($tmdbId, Season $season)
    {
        $language = $this->option('lang') ?? 'ja-JP';

        $response = Http::get("{$this->baseUrl}/tv/{$tmdbId}/season/{$season->season_number}", [
            'api_key' => $this->apiKey,
            'language' => $language,
        ]);

        if (!$response->successful()) {
            $this->error("    Failed to fetch episodes for Season {$season->season_number}");
            return;
        }

        $seasonData = $response->json();

        if (empty($seasonData['episodes'])) {
            return;
        }

        foreach ($seasonData['episodes'] as $episodeData) {
            Episode::updateOrCreate(
                [
                    'season_id' => $season->id,
                    'episode_number' => $episodeData['episode_number']
                ],
                [
                    'name' => $episodeData['name'],
                    'overview' => $episodeData['overview'],
                    'still_path' => $episodeData['still_path'],
                    'air_date' => $episodeData['air_date'] ?? null,
                    'runtime' => $episodeData['runtime'] ?? null,
                    'vote_average' => $episodeData['vote_average'] ?? null,
                ]
            );
        }

        $this->line("    Added {$season->episode_count} episodes");
    }
}
