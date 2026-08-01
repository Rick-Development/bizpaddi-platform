<?php
namespace Modules\Communications\Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\Communications\Models\{Subscriber, Campaign};

class CommunicationSeeder extends Seeder {
    public function run(): void {
        Subscriber::create(['email' => 'subscriber1@example.com', 'name' => 'Alice Johnson', 'is_active' => true]);
        Subscriber::create(['email' => 'subscriber2@example.com', 'name' => 'Bob Williams', 'is_active' => true]);
        
        Campaign::create(['name' => 'Monthly Newsletter', 'subject' => 'Rick Development - January Updates', 'content' => 'Latest news and updates from Rick Development', 'type' => 'email', 'status' => 'draft']);
    }
}
