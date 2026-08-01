<?php
namespace Modules\Clients\Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\Clients\Models\{Client, Testimonial};

class ClientSeeder extends Seeder {
    public function run(): void {
        $client1 = Client::create(['name' => 'Fortune 500 Bank', 'description' => 'Leading global financial institution', 'industry' => 'Finance', 'website' => 'https://bank.com']);
        $client2 = Client::create(['name' => 'HealthTech Corp', 'description' => 'Healthcare technology innovator', 'industry' => 'Healthcare', 'website' => 'https://healthtech.com']);
        
        Testimonial::create(['client_id' => $client1->id, 'content' => 'Rick Development transformed our digital infrastructure. Outstanding results!', 'author_name' => 'John CEO', 'author_position' => 'CEO', 'rating' => 5, 'is_featured' => true]);
        Testimonial::create(['client_id' => $client2->id, 'content' => 'Best technology partner we have ever worked with.', 'author_name' => 'Sarah CTO', 'author_position' => 'CTO', 'rating' => 5, 'is_featured' => true]);
    }
}
