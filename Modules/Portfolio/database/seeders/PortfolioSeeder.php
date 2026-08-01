<?php
namespace Modules\Portfolio\Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\Portfolio\Models\{Industry, Project};

class PortfolioSeeder extends Seeder {
    public function run(): void {
        $finance = Industry::create(['name' => 'Finance', 'slug' => 'finance']);
        $healthcare = Industry::create(['name' => 'Healthcare', 'slug' => 'healthcare']);
        $retail = Industry::create(['name' => 'Retail', 'slug' => 'retail']);
        
        Project::create(['industry_id' => $finance->id, 'title' => 'Banking Platform Modernization', 'client' => 'Global Bank Corp', 'description' => 'Complete digital transformation of legacy banking systems', 'impact_metrics' => '500% faster transactions, 99.99% uptime', 'year' => 2024, 'is_featured' => true]);
        Project::create(['industry_id' => $healthcare->id, 'title' => 'AI-Powered Patient Care System', 'client' => 'MedTech Solutions', 'description' => 'ML-based patient diagnosis and care management platform', 'impact_metrics' => '40% faster diagnosis, 95% accuracy', 'year' => 2023, 'is_featured' => true]);
    }
}
