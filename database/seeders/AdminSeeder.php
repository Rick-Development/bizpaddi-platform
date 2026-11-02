<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Admin::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // Create super admin
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@bizpaddi.com',
            'password' => Hash::make('password'), // Change this in production!
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create regular admin
        Admin::create([
            'name' => 'Admin User',
            'email' => 'user@bizpaddi.com',
            'password' => Hash::make('password'), // Change this in production!
            'role' => 'admin',
            'is_active' => true,
        ]);
    }
}
