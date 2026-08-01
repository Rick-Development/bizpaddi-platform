<?php

namespace Modules\Clients\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Clients\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing testimonials
        Testimonial::truncate();

        $testimonials = [
            [
                'content' => 'Rick Development transformed our digital presence completely. Their expertise in modern web technologies and attention to detail exceeded our expectations. Highly recommended!',
                'author_name' => 'Sarah Johnson',
                'author_position' => 'CEO, TechStart Inc',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'content' => 'Working with Rick Development was a game-changer for our company. They delivered a robust, scalable solution that perfectly aligned with our business goals.',
                'author_name' => 'Michael Chen',
                'author_position' => 'CTO, InnovateSoft',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'content' => 'Outstanding service and exceptional results. The team at Rick Development went above and beyond to ensure our project was a success. Their communication and professionalism are top-notch.',
                'author_name' => 'Emily Rodriguez',
                'author_position' => 'Product Manager, Digital Dynamics',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'content' => 'Rick Development delivered our cloud infrastructure migration flawlessly. Their technical expertise and project management skills are truly impressive.',
                'author_name' => 'David Thompson',
                'author_position' => 'Founder, CloudVentures',
                'rating' => 5,
                'is_featured' => false,
            ],
            [
                'content' => 'The mobile app they developed for us has received excellent feedback from our users. Rick Development truly understands user experience and modern design principles.',
                'author_name' => 'Lisa Anderson',
                'author_position' => 'Marketing Director, BrandBoost',
                'rating' => 5,
                'is_featured' => false,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
