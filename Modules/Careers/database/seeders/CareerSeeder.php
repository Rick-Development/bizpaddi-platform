<?php
namespace Modules\Careers\Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\Careers\Models\Job;

class CareerSeeder extends Seeder {
    public function run(): void {
        Job::create(['title' => 'Senior Full Stack Developer', 'department' => 'Engineering', 'location' => 'Remote', 'description' => 'Build scalable enterprise applications', 'requirements' => 'Laravel, React, 5+ years experience', 'type' => 'full-time', 'is_active' => true]);
        Job::create(['title' => 'AI/ML Engineer', 'department' => 'AI Research', 'location' => 'San Francisco, CA', 'description' => 'Develop cutting-edge AI solutions', 'requirements' => 'Python, TensorFlow, PhD preferred', 'type' => 'full-time', 'is_active' => true]);
        Job::create(['title' => 'DevOps Engineer', 'department' => 'Infrastructure', 'location' => 'Hybrid', 'description' => 'Manage cloud infrastructure and CI/CD', 'requirements' => 'AWS, Kubernetes, Docker', 'type' => 'full-time', 'is_active' => true]);
    }
}
