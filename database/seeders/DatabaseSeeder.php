<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin Module
        $this->call(\Modules\Admin\Database\Seeders\AdminDatabaseSeeder::class);

        // Home Module
        $this->call(\Modules\Home\Database\Seeders\HeroSliderSeeder::class);
        $this->call(\Modules\Home\Database\Seeders\FeaturedContentSeeder::class);
        
        // About Module
        $this->call(\Modules\About\Database\Seeders\LeaderSeeder::class);
        $this->call(\Modules\About\Database\Seeders\TimelineSeeder::class);
        $this->call(\Modules\About\Database\Seeders\AwardSeeder::class);
        
        // Services Module
        $this->call(\Modules\Services\Database\Seeders\ServiceSeeder::class);
        
        // Portfolio Module (if seeder exists)
        // $this->call(\Modules\Portfolio\Database\Seeders\ProjectSeeder::class);
        
        // Blog Module (if seeder exists)
        // $this->call(\Modules\Blog\Database\Seeders\PostSeeder::class);
        // $this->call(\Modules\Blog\Database\Seeders\CategorySeeder::class);
        
        // Permissions & Roles
        $this->call(\Database\Seeders\ProjectManagerPermissionsSeeder::class);

        // Clients Module
        $this->call(\Modules\Clients\Database\Seeders\TestimonialSeeder::class);
    }
}