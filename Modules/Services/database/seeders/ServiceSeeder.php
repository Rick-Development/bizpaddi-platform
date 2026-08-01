<?php
namespace Modules\Services\Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Services\Models\Service;
use Modules\Services\Models\CaseStudy;

class ServiceSeeder extends Seeder {
    public function run(): void {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing data
        Service::truncate();
        CaseStudy::truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $cloud = Service::create(['name' => 'Cloud Solutions', 'slug' => 'cloud-solutions', 'description' => 'Enterprise-grade cloud infrastructure and migration services.', 'is_active' => true, 'order' => 1]);
        $ai = Service::create(['name' => 'AI & Machine Learning', 'slug' => 'ai-ml', 'description' => 'Advanced AI solutions for business automation and intelligence.', 'is_active' => true, 'order' => 2]);
        $custom = Service::create(['name' => 'Custom Development', 'slug' => 'custom-development', 'description' => 'Tailored software solutions built to your specifications.', 'is_active' => true, 'order' => 3]);
        
        CaseStudy::create(['service_id' => $cloud->id, 'title' => 'Global Retail Cloud Migration', 'client_name' => 'RetailCorp International', 'challenge' => 'Legacy infrastructure limiting scalability', 'solution' => 'Complete cloud migration to AWS with microservices architecture', 'results' => '300% performance improvement, 60% cost reduction']);
    }
}
