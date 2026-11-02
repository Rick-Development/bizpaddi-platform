<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables that belong to a tenant business.
     */
    protected array $tenantTables = [
        'users',
        'clients',
        'invoices',
        'quotes',
        'accounts',
        'transactions',
        'virtual_accounts',
        'pm_projects',
        'pm_tasks',
        'pm_milestones',
        'pm_attendance',
        'work_logs',
        'staff_salaries',
        'departments',
        'leads',
        'lead_sources',
        'contacts',
        'contact_lists',
        'campaigns',
        'contracts',
        'contract_templates',
        'services',
        'service_categories',
        'service_orders',
        'posts',
        'categories',
        'hero_sliders',
        'testimonials',
        'support_tickets',
        'bug_reports',
        'career_jobs',
        'career_applications',
        'ai_employees',
        'ai_tasks',
        'ai_pending_actions',
        'ai_knowledge_documents',
        'smm_social_accounts',
        'smm_posts',
        'smm_content_calendars',
        'legal_pages',
        'settings',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tenantTables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                    $tableBlueprint->unsignedBigInteger('tenant_id')->nullable()->after('id')->index();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tenantTables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->dropColumn('tenant_id');
                });
            }
        }
    }
};
