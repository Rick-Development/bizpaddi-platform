<?php
namespace Modules\About\Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\About\Models\Leader;

class LeaderSeeder extends Seeder {
    public function run(): void {
        Leader::create(['name' => 'Richard Thompson', 'position' => 'CEO & Founder', 'bio' => 'Visionary leader with 20+ years of experience in enterprise technology solutions.', 'order' => 1]);
        Leader::create(['name' => 'Sarah Chen', 'position' => 'CTO', 'bio' => 'Technology innovator specializing in AI and cloud architecture.', 'order' => 2]);
        Leader::create(['name' => 'Michael Rodriguez', 'position' => 'CFO', 'bio' => 'Financial strategist with expertise in global enterprise finance.', 'order' => 3]);
    }
}
