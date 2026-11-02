<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BdmRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maxId = \Spatie\Permission\Models\Role::max('id') ?? 0;
        $role = \Spatie\Permission\Models\Role::firstOrCreate(
            ['name' => 'bdm', 'guard_name' => 'web'],
            ['id' => $maxId + 1]
        );
        
        // Ensure a test BDM user exists
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'bdm@bizpaddi.com'],
            [
                'name' => 'Test BDM',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'bdm',
                'status' => 'active'
            ]
        );
        $user->assignRole($role);
    }
}
