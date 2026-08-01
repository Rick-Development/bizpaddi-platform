<?php
namespace Modules\About\Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\About\Models\Award;

class AwardSeeder extends Seeder {
    public function run(): void {
        Award::create(['title' => 'Best Enterprise Solution Provider', 'organization' => 'Tech Excellence Awards', 'year' => 2024, 'description' => 'Recognized for outstanding innovation in enterprise technology.']);
        Award::create(['title' => 'AI Innovation Award', 'organization' => 'Global AI Summit', 'year' => 2023, 'description' => 'Awarded for groundbreaking AI-powered solutions.']);
        Award::create(['title' => 'Top Workplace Culture', 'organization' => 'Fortune Magazine', 'year' => 2024, 'description' => 'Ranked among top 100 best places to work.']);
    }
}
