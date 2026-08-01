<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ProjectManagerPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create PM permissions
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
                ['name' => $name],
                ['guard_name' => 'web']
            );
        }

        // Create or update project_manager role
        $role = Role::firstOrCreate(
            ['name' => 'project_manager'],
            ['guard_name' => 'web']
        );

        // Assign all PM permissions to the role
        $role->syncPermissions(array_keys($permissions));

        $this->command->info('Project Manager permissions and role created successfully!');
    }
}
