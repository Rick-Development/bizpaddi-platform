<?php

namespace Modules\Home\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Home\Models\HomeFeature;

class HomeFeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'title' => 'Quality Assured',
                'description' => 'Rigorous testing and quality control processes ensure flawless delivery.',
                'icon' => 'fas fa-check-circle', // Assuming FontAwesome or similar, or will use SVG logic in view if null
                'sort_order' => 1,
            ],
            [
                'title' => 'Fast Delivery',
                'description' => 'Agile methodology ensures rapid development and timely project completion.',
                'icon' => 'fas fa-rocket',
                'sort_order' => 2,
            ],
            [
                'title' => 'Cost Effective',
                'description' => 'Competitive pricing without compromising on quality or features.',
                'icon' => 'fas fa-dollar-sign',
                'sort_order' => 3,
            ],
            [
                'title' => '24/7 Support',
                'description' => 'Round-the-clock support to ensure your business never stops.',
                'icon' => 'fas fa-headset',
                'sort_order' => 4,
            ],
        ];

        foreach ($features as $feature) {
            HomeFeature::create($feature);
        }
    }
}
