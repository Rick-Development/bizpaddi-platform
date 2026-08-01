<?php
namespace Modules\About\Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\About\Models\Timeline;

class TimelineSeeder extends Seeder {
    public function run(): void {
        Timeline::create(['year' => 2010, 'title' => 'Company Founded', 'description' => 'Rick Development LTD established with a vision to transform enterprise technology.', 'order' => 1]);
        Timeline::create(['year' => 2015, 'title' => 'Global Expansion', 'description' => 'Opened offices in 5 countries across 3 continents.', 'order' => 2]);
        Timeline::create(['year' => 2020, 'title' => 'AI Innovation Lab', 'description' => 'Launched dedicated AI research and development center.', 'order' => 3]);
        Timeline::create(['year' => 2025, 'title' => 'Industry Leadership', 'description' => 'Recognized as top 10 enterprise solution provider globally.', 'order' => 4]);
    }
}
