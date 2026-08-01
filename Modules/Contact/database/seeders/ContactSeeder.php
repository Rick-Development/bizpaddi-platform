<?php
namespace Modules\Contact\Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\Contact\Models\{Lead, ContactSubmission};

class ContactSeeder extends Seeder {
    public function run(): void {
        Lead::create(['name' => 'John Smith', 'email' => 'john@example.com', 'phone' => '+1234567890', 'company' => 'Tech Corp', 'status' => 'new']);
        ContactSubmission::create(['name' => 'Jane Doe', 'email' => 'jane@example.com', 'subject' => 'Partnership Inquiry', 'message' => 'Interested in exploring partnership opportunities.', 'is_read' => false]);
    }
}
