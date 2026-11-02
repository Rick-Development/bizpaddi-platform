<?php

namespace Database\Seeders;

use App\Models\SaasPlan;
use Illuminate\Database\Seeder;

class SaasPlanSeeder extends Seeder
{
    public function run(): void
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE saas_plans MODIFY billing_type VARCHAR(30) NOT NULL DEFAULT 'flat_tiered'");
        }

        $plans = [
            [
                'name' => 'Pay-As-You-Transact (Micro-Fees)',
                'code' => 'pay_as_you_go',
                'description' => 'Zero monthly commitment. Top up your operating wallet from ₦500 and only pay tiny micro-fees per action (₦50/invoice, ₦30/quote, ₦20/team action). Zero usage = ₦0.',
                'billing_type' => 'payg',
                'price' => 0.00,
                'price_per_user' => 0.00,
                'billing_interval' => 'monthly',
                'included_users' => 5,
                'max_users' => null,
                'enable_ai' => false,
                'enable_custom_domain' => false,
                'features' => [
                    'model' => 'pay_per_action',
                    'invoice_fee' => 50,
                    'quote_fee' => 30,
                    'team_action_fee' => 20,
                    'inflow_rate' => '1% (Capped at ₦1,000)',
                    'invoicing' => true,
                    'quotes' => true,
                    'projects' => true,
                    'tasks_kanban' => true,
                    'team_attendance' => true,
                    'payroll' => true,
                    'client_portal' => true,
                    'crm_leads' => true,
                    'storefront_website' => true,
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Starter Business',
                'code' => 'starter_monthly',
                'description' => 'Ideal for small businesses and service providers getting digitized.',
                'billing_type' => 'flat_tiered',
                'price' => 15000.00,
                'price_per_user' => 0.00,
                'billing_interval' => 'monthly',
                'included_users' => 5,
                'max_users' => 5,
                'enable_ai' => false,
                'enable_custom_domain' => false,
                'features' => [
                    'invoicing' => true,
                    'quotes' => true,
                    'projects' => true,
                    'tasks_kanban' => true,
                    'team_attendance' => true,
                    'payroll' => true,
                    'client_portal' => true,
                    'crm_leads' => true,
                    'storefront_website' => true,
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Professional (Per-Seat)',
                'code' => 'pro_per_seat_monthly',
                'description' => 'Pay-as-you-grow model with base platform access + cost per active team seat.',
                'billing_type' => 'per_seat',
                'price' => 25000.00,
                'price_per_user' => 3000.00,
                'billing_interval' => 'monthly',
                'included_users' => 3,
                'max_users' => 50,
                'enable_ai' => false,
                'enable_custom_domain' => true,
                'features' => [
                    'invoicing' => true,
                    'quotes' => true,
                    'projects' => true,
                    'tasks_kanban' => true,
                    'team_attendance' => true,
                    'payroll' => true,
                    'client_portal' => true,
                    'crm_leads' => true,
                    'storefront_website' => true,
                    'custom_domain' => true,
                    'contracts_management' => true,
                    'social_media_hub' => true,
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise & AI Workforce',
                'code' => 'enterprise_ai_monthly',
                'description' => 'Complete business management suite including autonomous AI workforce and unlimited team seats.',
                'billing_type' => 'flat_tiered',
                'price' => 85000.00,
                'price_per_user' => 0.00,
                'billing_interval' => 'monthly',
                'included_users' => 100,
                'max_users' => null, // Unlimited
                'enable_ai' => true,
                'enable_custom_domain' => true,
                'features' => [
                    'invoicing' => true,
                    'quotes' => true,
                    'projects' => true,
                    'tasks_kanban' => true,
                    'team_attendance' => true,
                    'payroll' => true,
                    'client_portal' => true,
                    'crm_leads' => true,
                    'storefront_website' => true,
                    'custom_domain' => true,
                    'contracts_management' => true,
                    'social_media_hub' => true,
                    'ai_center' => true,
                    'ai_workforce' => true,
                    'priority_support' => true,
                ],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SaasPlan::updateOrCreate(['code' => $plan['code']], $plan);
        }
    }
}
