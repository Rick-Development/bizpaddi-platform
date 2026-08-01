<?php

namespace Modules\Home\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Home\Models\HomeCta;

class HomeCtaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HomeCta::create([
            'title' => 'Ready to start your project?',
            'description' => 'Let\'s work together to bring your vision to life with cutting-edge technology and expert craftsmanship.',
            'button1_text' => 'Contact Us Today',
            'button1_link' => route('contact.index'),
            'button2_text' => 'View Our Work',
            'button2_link' => route('portfolio.index'),
            'is_active' => true,
        ]);
    }
}
