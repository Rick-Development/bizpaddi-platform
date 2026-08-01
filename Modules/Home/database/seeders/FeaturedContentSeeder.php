<?php

namespace Modules\Home\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Home\Models\FeaturedContent;

class FeaturedContentSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        FeaturedContent::truncate();
        
        $contents = [
            [
                'title' => 'Enterprise Cloud Solutions',
                'excerpt' => 'Scalable cloud infrastructure designed for enterprise-grade performance and reliability.',
                'type' => 'service',
                'link' => '/services/cloud-solutions',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'AI & Machine Learning Platform',
                'excerpt' => 'Advanced AI solutions that transform business operations and drive innovation.',
                'type' => 'service',
                'link' => '/services/ai-ml',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Digital Transformation Success Story',
                'excerpt' => 'How we helped Fortune 500 companies achieve 300% ROI through digital transformation.',
                'type' => 'project',
                'link' => '/portfolio/digital-transformation',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Rick Development Launches New AI Platform',
                'excerpt' => 'Introducing our latest AI-powered platform for automated content generation and management.',
                'type' => 'news',
                'link' => '/blog/ai-platform-launch',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Global Expansion Announcement',
                'excerpt' => 'Rick Development opens new offices in Asia-Pacific region to better serve our growing client base.',
                'type' => 'news',
                'link' => '/blog/global-expansion',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Custom Software Development',
                'excerpt' => 'Tailored software solutions built to your exact specifications and business requirements.',
                'type' => 'service',
                'link' => '/services/custom-development',
                'order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($contents as $content) {
            FeaturedContent::create($content);
        }
    }
}
