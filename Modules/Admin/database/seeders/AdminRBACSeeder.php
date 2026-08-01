<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Modules\Admin\Models\Department;
use Spatie\Permission\PermissionRegistrar;

class AdminRBACSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Base Departments
        $departments = [
            'Engineering' => 'Software Development and Engineering',
            'Design' => 'UI/UX and Graphic Design',
            'Sales & Marketing' => 'Sales, Marketing, and Business Development',
            'Finance' => 'Accounting and Finance',
            'HR' => 'Human Resources',
            'Operations' => 'Operations and Management',
            'Support' => 'Customer Support',
            'Management' => 'Executive Management',
        ];

        foreach ($departments as $name => $description) {
            Department::firstOrCreate(['name' => $name], ['description' => $description]);
        }

        // 2. Create Permissions
        $permissions = [
            // Invoices
            'invoices.view', 'invoices.create', 'invoices.edit', 'invoices.delete',
            // Quotes
            'quotes.view', 'quotes.create', 'quotes.edit', 'quotes.delete',
            // Services
            'services.view', 'services.create', 'services.edit', 'services.delete',
            // Clients
            'clients.view', 'clients.create', 'clients.edit', 'clients.delete',
            // Projects
            'projects.view', 'projects.create', 'projects.edit', 'projects.delete',
            // Content
            'landing_page.manage', 
            // Admin
            'roles.manage', 'departments.manage', 'users.manage'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. Create Default Roles & Assign Permissions
        // Super Admin
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Developer (Example)
        $developer = Role::firstOrCreate(['name' => 'developer']);
        $developer->givePermissionTo(['projects.view', 'projects.create', 'projects.edit']);
        // Developers don't see invoices/quotes by default as per recent rules

        // Project Manager
        $pm = Role::firstOrCreate(['name' => 'project_manager']);
        $pm->givePermissionTo([
            'quotes.view', 'quotes.create', 'quotes.edit',
            'services.view',
            'clients.view',
            'projects.view', 'projects.create', 'projects.edit', 'projects.delete'
        ]);

        // Sales
        $sales = Role::firstOrCreate(['name' => 'sales_marketing']);
        $sales->givePermissionTo([
            'quotes.view', 'quotes.create', 'quotes.edit', 'quotes.delete',
            'services.view',
            'clients.view', 'clients.create', 'clients.edit'
        ]);
        
        // Finance
        $finance = Role::firstOrCreate(['name' => 'accounting_finance']);
        $finance->givePermissionTo([
            'invoices.view', 'invoices.create', 'invoices.edit', 'invoices.delete',
            'quotes.view',
            'clients.view'
        ]);
    }
}
