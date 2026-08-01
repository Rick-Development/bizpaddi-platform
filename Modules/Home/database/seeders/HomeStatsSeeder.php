<?php

namespace Modules\Home\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Home\Models\HomeStat;

class HomeStatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stats = [
            [
                'value' => '500+',
                'label' => 'Projects Completed',
                'sort_order' => 1,
            ],
            [
                'value' => '98%',
                'label' => 'Client Satisfaction',
                'sort_order' => 2,
            ],
            [
                'value' => '50+',
                'label' => 'Team Members',
                'sort_order' => 3,
            ],
            [
                'value' => '15+',
                'label' => 'Years Experience',
                'sort_order' => 4,
            ],
        ];

        foreach ($stats as $stat) {
            HomeStat::create($stat);
        }
    }
}
