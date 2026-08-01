<?php

namespace Modules\Home\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Home\Models\HomeProcess;

class HomeProcessesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $processes = [
            [
                'step_number' => 1,
                'title' => 'Discovery',
                'description' => 'We analyze your requirements and understand your business goals.',
            ],
            [
                'step_number' => 2,
                'title' => 'Design',
                'description' => 'Create wireframes and prototypes for your approval.',
            ],
            [
                'step_number' => 3,
                'title' => 'Development',
                'description' => 'Build your solution using cutting-edge technologies.',
            ],
            [
                'step_number' => 4,
                'title' => 'Launch',
                'description' => 'Deploy your project and provide ongoing support.',
            ],
        ];

        foreach ($processes as $process) {
            HomeProcess::create($process);
        }
    }
}
