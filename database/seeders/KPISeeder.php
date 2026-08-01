<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KPISeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Modules\ProjectManagement\Models\KPI::updateOrCreate(
            ['key' => 'tasks_completed'],
            [
                'name' => 'Monthly Task Volume',
                'target' => 20,
                'unit' => ' tasks',
                'description' => 'Target count of tasks to be completed in one month.'
            ]
        );

        \Modules\ProjectManagement\Models\KPI::updateOrCreate(
            ['key' => 'on_time_delivery'],
            [
                'name' => 'Deadlines Reliability',
                'target' => 95,
                'unit' => '%',
                'description' => 'Percentage of tasks completed on or before the due date.'
            ]
        );
    }
}
