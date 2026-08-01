<?php

namespace Modules\Home\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Home\Models\HeroSlider;

class HeroSliderSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        HeroSlider::truncate();
        
        $sliders = [
            [
                'title' => 'Welcome to Rick Development LTD',
                'subtitle' => 'Enterprise-Grade Solutions',
                'description' => 'Building the future with cutting-edge technology and innovative solutions for global enterprises.',
                'button_text' => 'Learn More',
                'button_link' => '/about',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'AI-Powered Development',
                'subtitle' => 'Innovation at Scale',
                'description' => 'Leveraging artificial intelligence and machine learning to deliver transformative business solutions.',
                'button_text' => 'Our Services',
                'button_link' => '/services',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Global Reach, Local Expertise',
                'subtitle' => 'Trusted Worldwide',
                'description' => 'Serving clients across continents with localized solutions and global standards of excellence.',
                'button_text' => 'Contact Us',
                'button_link' => '/contact',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $slider) {
            HeroSlider::create($slider);
        }
    }
}
