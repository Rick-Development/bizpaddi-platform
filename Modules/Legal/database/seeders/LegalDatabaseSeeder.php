<?php

namespace Modules\Legal\Database\Seeders;

use Illuminate\Database\Seeder;

class LegalDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            LegalSeeder::class,
        ]);
    }
}
