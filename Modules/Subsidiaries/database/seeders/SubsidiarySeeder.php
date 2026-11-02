<?php
namespace Modules\Subsidiaries\Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\Subsidiaries\Models\{Subsidiary, Partner};

class SubsidiarySeeder extends Seeder {
    public function run(): void {
        Subsidiary::create(['name' => 'Bizpaddi Tech Solutions', 'description' => 'Technology consulting and implementation services', 'website' => 'https://bizpaddi.com', 'location' => 'Lagos, Nigeria']);
        Partner::create(['name' => 'Global Cloud Partners', 'description' => 'Strategic cloud infrastructure partnership', 'website' => 'https://cloudpartners.com', 'partnership_type' => 'Technology']);
    }
}
