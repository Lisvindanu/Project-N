<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database for production deployment.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding database for production...');
        $this->command->info('');

        // 1. Seed categories first
        $this->call(CategorySeeder::class);
        $this->command->info('✓ Categories seeded');
        $this->command->info('');

        // 2. Seed admin users
        $this->call(AdminUserSeeder::class);
        $this->command->info('✓ Admin users created');
        $this->command->info('');

        // 3. Seed videos, seasons, and episodes
        $this->call(VideoSeeder::class);
        $this->command->info('✓ Videos and episodes seeded');
        $this->command->info('');

        $this->command->info('✅ Database seeding completed!');
        $this->command->info('');
        $this->command->info('Admin credentials:');
        $this->command->info('  Email: admin@projectN');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('  Email: irvanbayu@admin.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('Next steps:');
        $this->command->info('  1. Login as admin');
        $this->command->info('  2. Go to /admin/videos/create');
        $this->command->info('  3. Add movies/series from TMDB');
    }
}
