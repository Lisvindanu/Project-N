<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Genre categories dari TMDB API
        // Ini mencakup genre untuk Movies dan TV Series
        $categories = [
            // Movie Genres
            'Action',
            'Adventure',
            'Animation',
            'Comedy',
            'Crime',
            'Documentary',
            'Drama',
            'Family',
            'Fantasy',
            'History',
            'Horror',
            'Music',
            'Mystery',
            'Romance',
            'Science Fiction',
            'TV Movie',
            'Thriller',
            'War',
            'Western',

            // TV Series Genres (yang unik/tambahan)
            'Action & Adventure',
            'Sci-Fi & Fantasy',
            'Soap',
            'Talk',
            'War & Politics',
            'News',
            'Reality',
            'Kids',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category]);
        }
    }
}
