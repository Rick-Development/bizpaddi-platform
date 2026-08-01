<?php

namespace Modules\Home\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Home\Models\NavigationItem;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['label' => 'Home', 'url' => '/', 'order' => 1],
            ['label' => 'About', 'url' => '/about', 'order' => 2],
            ['label' => 'Services', 'url' => '/services', 'order' => 3],
            ['label' => 'Portfolio', 'url' => '/portfolio', 'order' => 4],
            ['label' => 'Blog', 'url' => '/blog', 'order' => 5],
            ['label' => 'Store', 'route_name' => 'store.index', 'order' => 6],
            ['label' => 'Investors', 'url' => '/investor', 'order' => 7],
            ['label' => 'Team', 'url' => '/team', 'order' => 8],
            ['label' => 'Client Portal', 'url' => '/client', 'order' => 9],
            ['label' => 'Contact', 'url' => '/contact', 'order' => 10],
        ];

        foreach ($items as $item) {
            NavigationItem::create(array_merge($item, [
                'location' => 'header',
                'is_active' => true,
            ]));
        }
    }
}
