<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            'pm.view-dashboard' => 'View Project Manager dashboard',
            'pm.manage-projects' => 'Manage assigned projects',
            'pm.assign-tasks' => 'Assign tasks to team members',
            'pm.view-team-performance' => 'View team member performance metrics',
            'pm.manage-milestones' => 'Create and edit project milestones',
            'pm.approve-timesheets' => 'Approve team member time logs',
            'pm.manage-team-members' => 'Add/remove team members from projects',
            'pm.view-reports' => 'View project reports and analytics',
        ];

        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web']
            );
        }

        $role = Role::firstOrCreate(
            ['name' => 'project_manager', 'guard_name' => 'web']
        );

        $role->syncPermissions(array_keys($permissions));

        if (class_exists(\Spatie\Permission\PermissionRegistrar::class)) {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe to leave permissions
    }
};

