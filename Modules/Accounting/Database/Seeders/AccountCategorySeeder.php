<?php

namespace Modules\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Accounting\Models\AccountCategory;

class AccountCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Assets',
                'code' => 'A',
                'type' => 'debit',
                'description' => 'Resources owned by the business',
            ],
            [
                'name' => 'Liabilities',
                'code' => 'L',
                'type' => 'credit',
                'description' => 'Obligations owed to others',
            ],
            [
                'name' => 'Equity',
                'code' => 'E',
                'type' => 'credit',
                'description' => 'Owner\'s residual interest',
            ],
            [
                'name' => 'Revenue',
                'code' => 'R',
                'type' => 'credit',
                'description' => 'Income generated from business activities',
            ],
            [
                'name' => 'Expenses',
                'code' => 'X',
                'type' => 'debit',
                'description' => 'Costs incurred in business operations',
            ],
        ];

        foreach ($categories as $category) {
            AccountCategory::firstOrCreate(
                ['code' => $category['code']],
                $category
            );
        }
    }
}
