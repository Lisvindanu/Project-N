<?php

namespace Database\Seeders;

use App\Models\Video;
use App\Models\Season;
use App\Models\Episode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📽️  Seeding videos, seasons, and episodes...');

        // Load the exported data
        $videos = include(__DIR__ . '/data/videos_data.php');

        DB::transaction(function () use ($videos) {
            foreach ($videos as $videoData) {
                $this->command->info("  → Adding: {$videoData['title']}");

                // Extract seasons and episodes data
                $seasons = $videoData['seasons'] ?? [];
                unset($videoData['seasons'], $videoData['category'], $videoData['id'], $videoData['created_at'], $videoData['updated_at']);

                // Create video
                $video = Video::create($videoData);

                // Create seasons and episodes
                foreach ($seasons as $seasonData) {
                    $episodes = $seasonData['episodes'] ?? [];
                    unset($seasonData['episodes'], $seasonData['id'], $seasonData['video_id'], $seasonData['created_at'], $seasonData['updated_at']);

                    $season = $video->seasons()->create($seasonData);

                    // Create episodes
                    foreach ($episodes as $episodeData) {
                        unset($episodeData['id'], $episodeData['season_id'], $episodeData['created_at'], $episodeData['updated_at']);
                        $season->episodes()->create($episodeData);
                    }

                    if (count($episodes) > 0) {
                        $this->command->info("    ✓ Season {$season->season_number}: " . count($episodes) . " episodes");
                    }
                }
            }
        });

        $videoCount = Video::count();
        $seasonCount = Season::count();
        $episodeCount = Episode::count();

        $this->command->info('');
        $this->command->info("✅ Content seeded successfully!");
        $this->command->info("   Videos: {$videoCount}");
        $this->command->info("   Seasons: {$seasonCount}");
        $this->command->info("   Episodes: {$episodeCount}");
    }
}
